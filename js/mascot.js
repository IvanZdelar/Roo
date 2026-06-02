document.addEventListener('DOMContentLoaded', function () {

    // Tab switching
    document.querySelectorAll('.mascot-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.mascot-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.mascot-grid').forEach(g => g.style.display = 'none');

            this.classList.add('active');
            document.getElementById('grid-' + this.dataset.category).style.display = 'grid';
        });
    });

    // Item selection
    document.querySelectorAll('.mascot-item-card').forEach(card => {
        card.addEventListener('click', function () {
            if (this.dataset.locked) return;

            const slot    = this.dataset.slot;
            const value   = this.dataset.value;
            const layerId = this.dataset.layer;
            const file    = this.dataset.file;
            const x       = this.dataset.x;
            const y       = this.dataset.y;
            const w       = this.dataset.w;
            const h       = this.dataset.h;

            // Deselect others in same slot
            document.querySelectorAll(`.mascot-item-card[data-slot="${slot}"]`)
                .forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');

            // Update hidden input
            const inputMap = {
                'hat':       'inputHat',
                'shirt':     'inputShirt',
                'hand_item': 'inputHandItem',
            };
            document.getElementById(inputMap[slot]).value = value;

            // Update SVG layer
            const layer = document.getElementById(layerId);
            if (!layer) return;

            if (file) {
                layer.setAttribute('href', file);
                layer.setAttribute('x', x);
                layer.setAttribute('y', y);
                layer.setAttribute('width', w);
                layer.setAttribute('height', h);
                layer.style.display = 'block';
            } else {
                layer.setAttribute('href', '');
                layer.style.display = 'none';
            }
        });
    });

    // Init layers from already selected items
    document.querySelectorAll('.mascot-item-card.selected').forEach(card => {
        const file    = card.dataset.file;
        const layerId = card.dataset.layer;
        if (!file || !layerId) return;

        const layer = document.getElementById(layerId);
        if (!layer) return;

        layer.setAttribute('href', file);
        layer.setAttribute('x', card.dataset.x);
        layer.setAttribute('y', card.dataset.y);
        layer.setAttribute('width', card.dataset.w);
        layer.setAttribute('height', card.dataset.h);
        layer.style.display = 'block';
    });
});