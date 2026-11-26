<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts a route group to one role and signs out accounts that were
 * deactivated while their session was still open.
 */
final class EnsureRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (! $user instanceof User) {
            return redirect()->guest(route('login'));
        }
        if (! $user->active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => 'Tu cuenta está desactivada. Comunícate con la asociación.']);
        }
        abort_unless($user->role === Role::from($role), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
