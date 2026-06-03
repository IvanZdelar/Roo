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

                layer.setAttributeNS(
                    'http://www.w3.org/1999/xlink',
                    'href',
                    file
                );
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
        layer.style.display = 'block';
    });
});

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
            const slot    = this.dataset.slot;
            const value   = this.dataset.value;
            const layerId = this.dataset.layer;
            const file    = this.dataset.file;

            // Deselect others in same slot
            document.querySelectorAll(`.mascot-item-card[data-slot="${slot}"]`)
                .forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');

            // Update hidden input
            const inputMap = {
                'hat':       'inputHat',
                'shirt':     'inputShirt',
                'hand_item': 'inputHandItem',
                'emotion':   'inputEmotion',
            };
            if (inputMap[slot]) {
                document.getElementById(inputMap[slot]).value = value;
            }

            // Emotion mijenja base SVG
            if (slot === 'emotion') {
                const base = document.getElementById('baseRooImg');
                if (base) base.setAttribute('href', file);
                return;
            }

            // Ostali slotovi
            const layer = document.getElementById(layerId);
            if (!layer) return;

            if (file) {
                layer.setAttribute('href', file);
                layer.setAttribute('x', this.dataset.x || '0');
                layer.setAttribute('y', this.dataset.y || '0');
                layer.setAttribute('width', this.dataset.w || '127');
                layer.setAttribute('height', this.dataset.h || '161');
                layer.style.display = 'block';
            } else {
                layer.setAttribute('href', '');
                layer.style.display = 'none';
            }
        });
    });

    // Init layers
    document.querySelectorAll('.mascot-item-card.selected').forEach(card => {
        const slot    = card.dataset.slot;
        const file    = card.dataset.file;
        const layerId = card.dataset.layer;
        if (!file) return;

        if (slot === 'emotion') {
            const base = document.getElementById('baseRooImg');
            if (base) base.setAttribute('href', file);
            return;
        }

        const layer = document.getElementById(layerId);
        if (!layer) return;
        layer.setAttribute('href', file);
        layer.setAttribute('x', card.dataset.x || '0');
        layer.setAttribute('y', card.dataset.y || '0');
        layer.setAttribute('width', card.dataset.w || '127');
        layer.setAttribute('height', card.dataset.h || '161');
        layer.style.display = 'block';
    });
});