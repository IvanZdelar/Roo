document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.mascot-item');

    // Postavi inicijalni preview iz već odabranih vrijednosti
    document.querySelectorAll('.mascot-item.selected').forEach(item => {
        const file = item.dataset.file;
        const layerId = item.dataset.layer;
        if (file) updateLayer(layerId, file);
    });

    items.forEach(item => {
        item.addEventListener('click', function () {
            const slot    = this.dataset.slot;
            const value   = this.dataset.value;
            const file    = this.dataset.file;
            const layerId = this.dataset.layer;

            // Makni selected sa svih u istom slotu
            document.querySelectorAll(`.mascot-item[data-slot="${slot}"]`)
                .forEach(el => el.classList.remove('selected'));

            this.classList.add('selected');

            // Ažuriraj hidden input
            const inputMap = {
                'hat':       'inputHat',
                'shirt':     'inputShirt',
                'hand_item': 'inputHandItem',
            };
            document.getElementById(inputMap[slot]).value = value;

            // Ažuriraj layer
            updateLayer(layerId, file);
        });
    });

    function updateLayer(layerId, file) {
        const layer = document.getElementById(layerId);
        if (!layer) return;

        if (file) {
            layer.src = file;
            layer.style.display = 'block';
        } else {
            layer.src = '';
            layer.style.display = 'none';
        }
    }
});