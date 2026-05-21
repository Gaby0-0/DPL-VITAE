import './bootstrap';
import './form-filters';

// Toggle show/hide password on .form-password-toggle
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.form-password-toggle .input-group-text').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.input-group').querySelector('input[type="password"], input[type="text"]');
            const icon  = btn.querySelector('i');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon?.classList.replace('bx-hide', 'bx-show');
            } else {
                input.type = 'password';
                icon?.classList.replace('bx-show', 'bx-hide');
            }
        });
    });
});

// Re-run after Livewire navigations
document.addEventListener('livewire:navigated', () => {
    document.querySelectorAll('.form-password-toggle .input-group-text').forEach(btn => {
        btn.replaceWith(btn.cloneNode(true)); // remove old listeners
    });
    document.querySelectorAll('.form-password-toggle .input-group-text').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.input-group').querySelector('input[type="password"], input[type="text"]');
            const icon  = btn.querySelector('i');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon?.classList.replace('bx-hide', 'bx-show');
            } else {
                input.type = 'password';
                icon?.classList.replace('bx-show', 'bx-hide');
            }
        });
    });
});