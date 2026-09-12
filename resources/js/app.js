import Alpine from 'alpinejs';

window.Alpine = Alpine;

/* Selector de técnicas (multi-selección). */
Alpine.data('techniquePicker', (selectedInit, options) => ({
    selected: (selectedInit || []),
    options: (options || []),
    search: '',
    open: false,
    get filtered() {
        const s = (this.search || '').trim().toLowerCase();
        if (!s) return this.options.filter(o => !this.selected.includes(o.value));
        return this.options.filter(o => !this.selected.includes(o.value) && o.name.toLowerCase().includes(s));
    },
    toggle(option) {
        const i = this.selected.indexOf(option.value);
        if (i > -1) { this.selected.splice(i, 1); } else { this.selected.push(option.value); }
        this.search = '';
        this.open = true;
        // Mantener el foco en el input para poder seguir buscando.
        this.$nextTick(() => {
            const input = this.$el && this.$el.querySelector('input[type="text"]');
            if (input) input.focus();
        });
    },
    remove(v) { this.selected = this.selected.filter(x => x !== v); },
    onKeydown(e) {
        if (e.key === 'Backspace' && this.search === '' && this.selected.length) {
            this.selected.pop();
            this.open = true;
        }
        if (e.key === 'ArrowDown' && this.filtered.length) {
            this.open = true;
        }
    },
}));

/* Selector de paquetes de tokens (lista + detalle interactivo). */
Alpine.data('packageSelector', (packages) => ({
    packages,
    selected: packages.length ? packages[0].id : null,
    formatUsd(value) {
        return Number(value).toFixed(2);
    },
}));

Alpine.start();