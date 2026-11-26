// Progressive enhancements only: every page works without JavaScript.

document.addEventListener('submit', (event) => {
    const form = event.target;
    const message = form.dataset.confirm;
    if (message && !window.confirm(message)) {
        event.preventDefault();
        return;
    }
    form.querySelectorAll('button[type="submit"]').forEach((button) => {
        button.disabled = true;
    });
});

document.querySelectorAll('[data-dismiss]').forEach((button) => {
    button.addEventListener('click', () => button.closest('[role="status"]')?.remove());
});

document.querySelectorAll('[data-menu-toggle]').forEach((button) => {
    const menu = document.getElementById(button.getAttribute('aria-controls'));
    button.addEventListener('click', () => {
        const open = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', String(!open));
        menu?.classList.toggle('hidden', open);
    });
});

document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
    const preview = document.getElementById(input.dataset.preview);
    input.addEventListener('change', () => {
        const [file] = input.files;
        if (preview && file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });
});

// Live average weight while filling a biometric sampling.
const size = document.querySelector('[name="sample_size"]');
const weight = document.querySelector('[name="sample_weight_grams"]');
const average = document.getElementById('sample-average');
if (size && weight && average) {
    const update = () => {
        const n = Number(size.value);
        const grams = Number(String(weight.value).replace(',', '.'));
        average.textContent = n > 0 && grams > 0
            ? `Peso promedio: ${(grams / n).toLocaleString('es-CO', { maximumFractionDigits: 1 })} g`
            : '';
    };
    size.addEventListener('input', update);
    weight.addEventListener('input', update);
}
