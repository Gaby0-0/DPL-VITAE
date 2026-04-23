/**
 * form-filters.js
 * Restricciones de entrada en tiempo real para campos de formulario.
 * Se aplica automáticamente a cualquier input con los atributos data-filter="*"
 *
 * Uso en HTML:
 *   data-filter="solo-letras"     → solo letras, acentos, ñ y espacios
 *   data-filter="solo-numeros"    → solo dígitos
 *   data-filter="telefono"        → dígitos, espacios, +, -, (, )
 *   data-filter="placa"           → letras mayúsculas, dígitos y guión
 *   data-filter="decimal"         → dígitos y un solo punto decimal
 *   data-filter="email"           → sin espacios (validación nativa del browser)
 */

const FILTROS = {
    'solo-letras': /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]$/,
    'solo-numeros': /^[0-9]$/,
    'telefono':    /^[0-9\s\+\-\(\)]$/,
    'placa':       /^[A-Z0-9\-]$/,
    'decimal':     null, // lógica especial
    'email':       null, // bloquea solo espacios
};

function aplicarFiltro(input) {
    const filtro = input.dataset.filter;
    if (!filtro) return;

    input.addEventListener('keypress', (e) => {
        const char = e.key;

        // Permitir teclas de control siempre
        if (char.length > 1) return;

        if (filtro === 'decimal') {
            // Solo dígitos y un punto decimal
            if (!/^[0-9.]$/.test(char)) {
                e.preventDefault();
                return;
            }
            // Solo un punto
            if (char === '.' && input.value.includes('.')) {
                e.preventDefault();
            }
            return;
        }

        if (filtro === 'email') {
            // Bloquear espacios en email
            if (char === ' ') e.preventDefault();
            return;
        }

        if (filtro === 'placa') {
            // Forzar mayúsculas en placa
            if (!/^[A-Za-z0-9\-]$/.test(char)) {
                e.preventDefault();
            }
            return;
        }

        const regex = FILTROS[filtro];
        if (regex && !regex.test(char)) {
            e.preventDefault();
        }
    });

    // Para placa: convertir a mayúsculas al pegar
    if (filtro === 'placa') {
        input.addEventListener('input', () => {
            const pos = input.selectionStart;
            input.value = input.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
            input.setSelectionRange(pos, pos);
        });
    }

    // Para solo-letras: limpiar si pegan texto con números
    if (filtro === 'solo-letras') {
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const texto = (e.clipboardData || window.clipboardData).getData('text');
            const limpio = texto.replace(/[^a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]/g, '');
            document.execCommand('insertText', false, limpio);
        });
    }

    // Para teléfono: limpiar si pegan texto inválido
    if (filtro === 'telefono') {
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const texto = (e.clipboardData || window.clipboardData).getData('text');
            const limpio = texto.replace(/[^0-9\s\+\-\(\)]/g, '');
            document.execCommand('insertText', false, limpio);
        });
    }

    // Para solo-numeros y decimal: limpiar si pegan texto inválido
    if (filtro === 'solo-numeros' || filtro === 'decimal') {
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const texto = (e.clipboardData || window.clipboardData).getData('text');
            const limpio = filtro === 'decimal'
                ? texto.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')
                : texto.replace(/[^0-9]/g, '');
            document.execCommand('insertText', false, limpio);
        });
    }
}

// Inicializar al cargar el DOM
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-filter]').forEach(aplicarFiltro);
});

// Soporte para Livewire (navegación SPA con wire:navigate)
document.addEventListener('livewire:navigated', () => {
    document.querySelectorAll('[data-filter]').forEach(aplicarFiltro);
});