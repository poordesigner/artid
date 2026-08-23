

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/* Buscar el artistaas */
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
        this.open = false;
    },
    remove(v) { this.selected = this.selected.filter(x => x !== v); },
    onKeydown(e) {
        if (e.key === 'Backspace' && this.search === '' && this.selected.length) {
            this.selected.pop();
        }
        if (e.key === 'ArrowDown' && this.filtered.length) {
            this.open = true;
        }
    },
}));

Alpine.start();
