<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ContentTest extends TestCase
{
    public function test_admin_publishes_a_post_with_a_photo(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post('/admin/noticias', [
            'title' => 'Cosecha récord en la vereda',
            'excerpt' => 'Resumen corto',
            'body' => "Primer párrafo.\n\nSegundo párrafo.",
            'published_on' => now()->toDateString(),
            'image' => UploadedFile::fake()->image('cosecha.jpg', 1200, 800),
        ])->assertRedirect('/admin/noticias');

        $post = Post::query()->sole();
        $this->assertSame('cosecha-record-en-la-vereda', $post->slug);
        $this->assertNotNull($post->image_path);
        Storage::disk('public')->assertExists($post->image_path);

        $this->get('/noticias/cosecha-record-en-la-vereda')
            ->assertOk()
            ->assertSee('<p>Segundo párrafo.</p>', false)
            ->assertSee('"@type":"NewsArticle"', false);
    }

    public function test_replacing_and_removing_photos_cleans_the_disk(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $old = UploadedFile::fake()->image('a.png')->store('productos', 'public');
        $this->assertIsString($old);
        $product = Product::factory()->create(['image_path' => $old]);
        $data = ['name' => $product->name, 'description' => 'Fresco', 'price_cop' => '$ 18.000', 'unit' => 'kg', 'available' => '1'];

        $this->actingAs($admin)->put('/admin/productos/'.$product->id, [...$data, 'image' => UploadedFile::fake()->image('b.webp')]);
        Storage::disk('public')->assertMissing($old);
        $new = $product->refresh()->image_path;
        $this->assertNotNull($new);
        $this->assertSame(18000, $product->price_cop);

        $this->actingAs($admin)->put('/admin/productos/'.$product->id, [...$data, 'remove_image' => '1']);
        Storage::disk('public')->assertMissing($new);
        $this->assertNull($product->refresh()->image_path);
    }

    public function test_uploads_must_be_images(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post('/admin/recetas', [
            'title' => 'Sudado', 'summary' => 'Rico', 'ingredients' => 'Pescado', 'steps' => 'Cocinar',
            'image' => UploadedFile::fake()->create('shell.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors('image');
        $this->assertDatabaseCount('recipes', 0);
    }

    public function test_slugs_stay_unique(): void
    {
        $first = Recipe::factory()->create(['title' => 'Tilapia frita']);
        $second = Recipe::factory()->create(['title' => 'Tilapia frita']);

        $this->assertSame('tilapia-frita', $first->slug);
        $this->assertSame('tilapia-frita-2', $second->slug);
    }

    public function test_public_site_lists_available_products_and_published_posts(): void
    {
        Product::factory()->create(['name' => 'Filete de tilapia', 'price_cop' => 32000]);
        Product::factory()->create(['name' => 'Producto oculto', 'available' => false]);
        Post::factory()->create(['title' => 'Noticia publicada']);
        $scheduled = Post::factory()->create(['title' => 'Noticia programada', 'published_on' => now()->addWeek()->toDateString()]);

        $this->get('/')->assertOk()->assertSee('Filete de tilapia')->assertSee('$ 32.000')
            ->assertDontSee('Producto oculto')->assertSee('Noticia publicada')->assertDontSee('Noticia programada');
        $this->get('/noticias/'.$scheduled->slug)->assertNotFound();
    }

    public function test_recipe_page_has_structured_data(): void
    {
        $recipe = Recipe::factory()->create(['title' => 'Sudado de cachama', 'ingredients' => "Cachama\n\nTomate\n"]);

        $this->get('/recetas/'.$recipe->slug)
            ->assertOk()
            ->assertSee('"@type":"Recipe"', false)
            ->assertSee('"recipeIngredient":["Cachama","Tomate"]', false);
    }

    public function test_sitemap_and_robots_point_search_engines_to_public_pages(): void
    {
        $recipe = Recipe::factory()->create();

        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('site.recipe', $recipe), false);
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('Sitemap: '.route('sitemap'));
    }
}
