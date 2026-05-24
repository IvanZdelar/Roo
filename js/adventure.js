document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.wizard-slide');
    const prevBtn = document.getElementById('wizardPrev');
    const nextBtn = document.getElementById('wizardNext');
    const addLocationBtn = document.getElementById('addLocationBtn');
    const locationsContainer = document.getElementById('locationsContainer');
    const budgetRange = document.getElementById('budget_range');
    const budgetValue = document.getElementById('budgetValue');
    const routeLocationsInput = document.getElementById('route_locations_input');
    const selectedStayOptionInput = document.getElementById('selected_stay_option_input');
    const stayTypeHidden = document.getElementById('smjestaj_tip_hidden');
    const stayTypeButtons = document.querySelectorAll('.stay-type-btn');

    const progressFill = document.getElementById('wizardProgressFill');
    const progressText = document.getElementById('wizardProgressText');
    const progressLabel = document.getElementById('wizardProgressLabel');
    const rooWizardBubble = document.getElementById('rooWizardBubble');
    const rooMoodImg = document.getElementById('rooMoodImg');
    const adventureWizardStage = document.getElementById('adventureWizardStage');

    const form = document.getElementById('adventureWizardForm');
    const loader = document.getElementById('adventureCreateLoader');

    const buddySlotsBox = document.getElementById('buddySlotsBox');
    const buddySlotsVisible = document.getElementById('buddy_slots_visible');
    const buddySlotsInput = document.getElementById('buddy_slots_input');

    if (buddySlotsVisible && buddySlotsInput) {
        buddySlotsVisible.addEventListener('input', function () {
            buddySlotsInput.value = buddySlotsVisible.value;
        });
    }

    const calendar = document.getElementById('tripCalendar');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    if (calendar && startDateInput && endDateInput && typeof flatpickr !== 'undefined') {
        flatpickr(calendar, {
            inline: true,
            mode: 'range',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            locale: {
                firstDayOfWeek: 1
            },
            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    startDateInput.value = instance.formatDate(selectedDates[0], 'Y-m-d');
                    endDateInput.value = instance.formatDate(selectedDates[1], 'Y-m-d');
                } else {
                    startDateInput.value = '';
                    endDateInput.value = '';
                }
            }
        });
    }

    let currentStep = 0;
    let alreadySubmitting = false;
    let selectedAccommodationsByCity = {};

    const stayPriceRange = document.getElementById('stayPriceRange');
    const stayPriceValue = document.getElementById('stayPriceValue');

    if (stayPriceRange && stayPriceValue) {
        stayPriceRange.addEventListener('input', function () {
            stayPriceValue.textContent = stayPriceRange.value + '€';

            if (stayTypeHidden && stayTypeHidden.value) {
                renderStayOptions(stayTypeHidden.value);
            }
        });
    }

    const stepLabels = [
        'Lokacije',
        'Naziv i budžet',
        'Kategorija smještaja',
        'Odabir smještaja',
        'Društvo i prijevoz',
        'Tip i aktivnosti',
        'Aktivnosti po lokaciji',
        'Sažetak'
    ];

    const rooBubbleTexts = [
        [
            'Prvo mi reci odakle krećemo, koje gradove obilazimo i gdje završavamo.',
            'Ruta je srce avanture — složimo početak, kraj i sve stanice između.',
            'Upiši gradove i koliko dana želiš provesti na svakoj lokaciji.'
        ],
        [
            'Sad daj ime ovoj avanturi i reci mi koliko trošimo dnevno.',
            'Budžet mi pomaže da predložim realne aktivnosti, smještaj i doživljaje.',
            'Naziv i budžet određuju stil putovanja — od štedljivog do luksuznog.'
        ],
        [
            'Vrijeme je za odabir vrste smještaja. Želiš hotel, hostel ili nešto lokalnije?',
            'Odaberi gdje se vidiš nakon dugog dana istraživanja.',
            'Smještaj mijenja cijeli vibe putovanja, zato biraj ono što ti najviše paše.'
        ],
        [
            'Sad odaberi konkretnu ponudu koja ti najbolje odgovara.',
            'Roo je pripremio opcije — izaberi mjesto gdje ćemo puniti baterije.',
            'Pogledaj ponude i odaberi onu koja najbolje odgovara tvom budžetu i stilu.'
        ],
        [
            'S kim putuješ i kako stižemo do avanture? To mijenja tempo cijelog puta.',
            'Solo, par, prijatelji ili obitelj — svaka ekipa ima drugačiji stil putovanja.',
            'Još malo logistike: odaberi društvo i prijevoz pa nastavljamo na aktivnosti.'
        ],
        [
            'Odaberi jedan ili više tipova putovanja, a ja ću otvoriti aktivnosti koje najbolje pašu.',
            'Možeš kombinirati više stilova, primjerice opuštanje i avanturu.',
            'Ovdje biramo vibe putovanja i aktivnosti koje želiš doživjeti.'
        ],
        [
            'Sad za svaku lokaciju biramo konkretne aktivnosti prema tvom budžetu i interesima.',
            'Svaki grad ima svoje najbolje ideje — odaberi što želiš ubaciti u plan.',
            'Roo ti sada pokazuje prijedloge iz baze za svaki grad koji si odabrao.'
        ],
        [
            'Sve je spremno. Provjeri sažetak i spremi svoju avanturu.',
            'Još samo zadnji pogled na plan i možemo krenuti.',
            'Ako sve izgleda dobro, spremi avanturu i Roo ju pamti za tebe.'
        ]
    ];

    const rooMoods = [
        'media/svg/roo-question.svg',
        'media/svg/roo-question.svg',
        'media/svg/roo-san.svg',
        'media/svg/roo-san.svg',
        'media/svg/roo-happy.svg',
        'media/svg/roo.svg',
        'media/svg/roo-happy.svg',
        'media/svg/roo.svg'
    ];

    const rooSides = [
        'left',
        'right',
        'left',
        'right',
        'left',
        'right',
        'left',
        'right'
    ];

    const activitiesByTripType = {
        'Opuštanje': [
            'Plaža', 'Wellness', 'Spa', 'Yoga', 'Sunset spot', 'Šetnja uz more',
            'Boat tour', 'Lagani shopping', 'Kafići', 'Termalni bazeni',
            'Piknik', 'Fotografija', 'Lokalna hrana'
        ],
        'Avantura': [
            'Planinarenje', 'Zipline', 'Kajaking', 'Rafting', 'Biciklizam',
            'Ronjenje', 'Jahanje', 'Safari', 'Kampiranje', 'Road trip',
            'Penjanje', 'Paragliding', 'Off-road tura', 'Vodopadi'
        ],
        'Istraživanje gradova': [
            'Razgledavanje', 'Muzeji', 'Stari grad', 'Street photo ruta',
            'Lokalne tržnice', 'Arhitektura', 'Galerije', 'Walking tour',
            'Hidden gems', 'Vidikovac', 'Shopping', 'Street food',
            'Povijesne četvrti', 'Parkovi'
        ],
        'Gastro putovanje': [
            'Lokalna hrana', 'Street food', 'Vinarije', 'Fine dining',
            'Food tour', 'Tržnica', 'Kuharski tečaj', 'Craft pivovare',
            'Degustacija sira', 'Degustacija vina', 'Slatkiši', 'Seafood',
            'Tradicionalni restoran'
        ],
        'Kultura i povijest': [
            'Muzeji', 'Dvorci', 'Galerije', 'Kazalište', 'Povijesna tura',
            'Arheološka nalazišta', 'Crkve i katedrale', 'Spomenici',
            'Lokalni običaji', 'Kulturni festival', 'Stari grad',
            'Vođena tura', 'Koncert klasične glazbe'
        ],
        'Noćni život': [
            'Klubovi', 'Cocktail bar', 'Rooftop bar', 'Live music',
            'Pub crawl', 'Festivali', 'Koncerti', 'Karaoke',
            'Noćna šetnja', 'Beach party', 'Stand-up show',
            'Kasna večera', 'DJ event'
        ]
    };

    function showStep(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
            slide.classList.toggle('slide-left', i < index);
            slide.classList.toggle('slide-right', i > index);
        });

        if (prevBtn) prevBtn.style.visibility = index === 0 ? 'hidden' : 'visible';
        if (nextBtn) nextBtn.style.visibility = index === slides.length - 1 ? 'hidden' : 'visible';

        const progressPercent = ((index + 1) / slides.length) * 100;

        if (progressFill) progressFill.style.width = progressPercent + '%';
        if (progressText) progressText.textContent = `Korak ${index + 1} / ${slides.length}`;
        if (progressLabel) progressLabel.textContent = stepLabels[index] || '';

        if (rooWizardBubble) {
            const texts = rooBubbleTexts[index] || [];
            rooWizardBubble.textContent = texts[Math.floor(Math.random() * texts.length)];
            rooWizardBubble.style.animation = 'none';
            rooWizardBubble.offsetHeight;
            rooWizardBubble.style.animation = 'bubblePop 0.35s ease both';
        }

        updateRooMood(index);

        const activeScroll = slides[index]?.querySelector('.wizard-slide-scroll');
        if (activeScroll) activeScroll.scrollTop = 0;

        if (index === 1) updateBudgetVisuals();
        if (stepLabels[index] === 'Aktivnosti po lokaciji') buildLocationActivityStep();
        if (index === slides.length - 1) updateSummary();
    }

    function updateRooMood(index) {
        if (!rooMoodImg) return;

        rooMoodImg.classList.add('mood-changing');

        setTimeout(() => {
            rooMoodImg.src = rooMoods[index] || rooMoods[0];
            rooMoodImg.classList.remove('mood-changing');
        }, 180);

        if (adventureWizardStage) {
            adventureWizardStage.classList.toggle('roo-right', rooSides[index] === 'right');
        }
    }

    function createMiddleLocationRow(number) {
        const row = document.createElement('div');
        row.className = 'wizard-location-row dynamic-location';

        row.innerHTML = `
            <div class="field-group wizard-field-group">
                <input type="text" class="wizard-input-text location-input" placeholder=" ">
                <label>${number}. lokacija</label>
            </div>

            <div class="field-group wizard-field-group wizard-days-group">
                <input type="number" class="wizard-input-text location-days-input" min="1" placeholder=" ">
                <label>Dani</label>
            </div>

            <button type="button" class="remove-location-btn">✕</button>
        `;

        return row;
    }

    function renumberMiddleLocations() {
        if (!locationsContainer) return;

        const middleRows = locationsContainer.querySelectorAll('.dynamic-location');

        middleRows.forEach((row, index) => {
            const label = row.querySelector('.field-group label');
            if (label) label.textContent = (index + 2) + '. lokacija';
        });
    }

    function collectLocations() {
        if (!locationsContainer) return [];

        const rows = locationsContainer.querySelectorAll('.wizard-location-row');
        const values = [];

        rows.forEach(row => {
            const nameInput = row.querySelector('.location-input');
            const daysInput = row.querySelector('.location-days-input');

            const name = nameInput ? nameInput.value.trim() : '';
            const days = daysInput ? parseInt(daysInput.value || '0', 10) : 0;

            if (name !== '') {
                values.push({
                    name: name,
                    days: days > 0 ? days : 1
                });
            }
        });

        if (routeLocationsInput) {
            routeLocationsInput.value = JSON.stringify(values);
        }

        return values;
    }

    if (addLocationBtn && locationsContainer) {
        addLocationBtn.addEventListener('click', function () {
            const endRow = locationsContainer.querySelector('.fixed-end');
            const middleCount = locationsContainer.querySelectorAll('.dynamic-location').length;
            const newRow = createMiddleLocationRow(middleCount + 2);

            locationsContainer.insertBefore(newRow, endRow);
            renumberMiddleLocations();
        });
    }

    if (locationsContainer) {
        locationsContainer.addEventListener('click', function (e) {
            if (!e.target.classList.contains('remove-location-btn')) return;

            const row = e.target.closest('.wizard-location-row');

            if (row && row.classList.contains('dynamic-location')) {
                row.remove();
                renumberMiddleLocations();
            }
        });
    }

    function getBudgetLevel(value) {
        value = parseInt(value, 10);

        if (value < 100) return 'low';
        if (value < 200) return 'mid';
        return 'high';
    }

    const budgetBubbleTexts = {
        low: 'Uživajmo besplatno! Iskopat ću ti najbolje parkove, vidikovce i besplatne dane u muzejima. Tvoj novčanik ostaje pun, a tvoj mobitel pun fotki!',
        mid: 'Pametno i polako. Biramo par top atrakcija koje se ne propuštaju, a ostatak vremena zujimo kao pravi lokalci. Najbolje od oba svijeta!',
        high: 'Idemo do kraja! Nema čekanja u redovima i nema propuštenih prilika. Biram ti najjača iskustva jer uspomene nemaju cijenu!'
    };

    function updateBudgetVisuals() {
        if (!budgetRange || !budgetValue) return;

        const value = parseInt(budgetRange.value, 10);
        const level = getBudgetLevel(value);

        budgetValue.textContent = value + '€';

        ['rooBudgetLow', 'rooBudgetMid', 'rooBudgetHigh'].forEach(id => {
            const item = document.getElementById(id);
            if (item) item.classList.remove('active');
        });

        const activeId =
            level === 'low' ? 'rooBudgetLow' :
            level === 'mid' ? 'rooBudgetMid' :
            'rooBudgetHigh';

        const activeItem = document.getElementById(activeId);
        if (activeItem) activeItem.classList.add('active');

        if (rooWizardBubble && currentStep === 1) {
            rooWizardBubble.textContent = budgetBubbleTexts[level];
            rooWizardBubble.style.animation = 'none';
            rooWizardBubble.offsetHeight;
            rooWizardBubble.style.animation = 'bubblePop 0.35s ease both';
        }
    }

    if (budgetRange) {
        budgetRange.addEventListener('input', updateBudgetVisuals);
        updateBudgetVisuals();
    }

    document.querySelectorAll('.single-choice').forEach(group => {
        const targetId = group.dataset.target;
        const hiddenInput = document.getElementById(targetId);

        group.querySelectorAll('.adventure-chip').forEach(chip => {
            chip.addEventListener('click', function () {
                group.querySelectorAll('.adventure-chip').forEach(c => c.classList.remove('selected'));
                chip.classList.add('selected');

                if (hiddenInput) {
                    hiddenInput.value = chip.dataset.value;
                }

                if (targetId === 'travel_with_input') {
                    if (chip.dataset.value === 'Korisnik') {
                        buddySlotsBox && buddySlotsBox.classList.add('visible');

                        if (buddySlotsVisible && buddySlotsInput && buddySlotsVisible.value.trim() === '') {
                            buddySlotsVisible.value = '1';
                            buddySlotsInput.value = '1';
                        }
                    } else {
                        buddySlotsBox && buddySlotsBox.classList.remove('visible');

                        if (buddySlotsVisible) buddySlotsVisible.value = '';
                        if (buddySlotsInput) buddySlotsInput.value = '';
                    }
                }
            });
        });
    });

    document.querySelectorAll('.multi-choice').forEach(group => {
        const targetId = group.dataset.target;
        const hiddenInput = document.getElementById(targetId);

        group.querySelectorAll('.adventure-chip').forEach(chip => {
            chip.addEventListener('click', function () {
                chip.classList.toggle('selected');

                const selected = [];

                group.querySelectorAll('.adventure-chip.selected').forEach(selectedChip => {
                    selected.push(selectedChip.dataset.value);
                });

                if (hiddenInput) hiddenInput.value = JSON.stringify(selected);

                if (targetId === 'trip_type_input') {
                    renderActivityChoices();
                }
            });
        });
    });

    function renderActivityChoices() {
        const grid = document.getElementById('activityChoiceGrid');
        if (!grid) return;

        const selectedTypes = getSelectedTripTypes();
        let allActivities = new Set();

        selectedTypes.forEach(type => {
            (activitiesByTripType[type] || []).forEach(a => {
                allActivities.add(a);
            });
        });

        grid.innerHTML = [...allActivities].map(activity => `
        <button type="button" class="adventure-chip" data-value="${escapeHtml(activity)}">
            ${escapeHtml(activity)}
        </button>
    `).join('') + `
        <button type="button" class="adventure-chip other-activity-chip" id="otherActivityBtn" data-value="Ostalo">
            ➕ Ostalo
        </button>
    `;

    grid.querySelectorAll('.adventure-chip').forEach(chip => {
        chip.addEventListener('click', function () {
            chip.classList.toggle('selected');
            saveSelectedActivities();
        });
    });

    const otherBtn = document.getElementById('otherActivityBtn');
    const otherWrap = document.getElementById('otherActivityWrap');

    if (otherBtn && otherWrap) {
        otherBtn.addEventListener('click', function () {
            otherWrap.classList.toggle('visible');
        });
    }

    saveSelectedActivities();
    }

    function saveSelectedActivities() {
        const hiddenInput = document.getElementById('activity_tags_input');
        const selected = [];

        document.querySelectorAll('#activityChoiceGrid .adventure-chip.selected').forEach(chip => {
            if (chip.dataset.value !== 'Ostalo') {
                selected.push(chip.dataset.value);
            }
        });

        const custom = document.getElementById('otherActivityInput');
        if (custom && custom.value.trim()) {
            selected.push(custom.value.trim());
        }

        if (hiddenInput) {
            hiddenInput.value = JSON.stringify(selected);
        }
    }

    const otherActivityInput = document.getElementById('otherActivityInput');
    if (otherActivityInput) {
        otherActivityInput.addEventListener('input', saveSelectedActivities);
    }

    stayTypeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            stayTypeButtons.forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');

            if (stayTypeHidden) stayTypeHidden.value = btn.dataset.value;
            if (selectedStayOptionInput) selectedStayOptionInput.value = '';

            renderStayOptions(btn.dataset.value);
        });
    });

    async function renderStayOptions(type) {
        const grid = document.getElementById('stayOptionsGrid');
        const title = document.getElementById('stayChoiceTitle');
        const priceRange = document.getElementById('stayPriceRange');
        const priceValue = document.getElementById('stayPriceValue');

        if (!grid || !title || !priceRange || !priceValue) return;

        const locations = collectLocations();
        const cities = locations.map(loc => loc.name);

        let dbType = '';

        if (type === 'Hotel') {
            dbType = 'hotel_motel';
            title.textContent = 'Odaberi hotel ili motel za svaku lokaciju';
        } else if (type === 'Motel') {
            dbType = 'hostel_apartment';
            title.textContent = 'Odaberi hostel ili apartman za svaku lokaciju';
        } else {
            title.textContent = 'Odaberi korisnika kod kojeg želiš odsjesti';
            grid.innerHTML = `
                <div class="location-activity-card">
                    <h3>Uskoro dostupno</h3>
                    <p>Smještaj kod korisnika ćemo spojiti kasnije.</p>
                </div>
            `;
            return;
        }

        priceValue.textContent = priceRange.value + '€';

        if (!cities.length) {
            grid.innerHTML = `
                <div class="location-activity-card">
                    <h3>Nema lokacija</h3>
                    <p>Prvo dodaj lokacije na prvom koraku.</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = `
            <div class="location-activity-card">
                <h3>Učitavam smještaj...</h3>
                <p>Roo traži opcije prema odabranim gradovima.</p>
            </div>
        `;

        try {
            const response = await fetch('get-accommodations.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    cities: cities,
                    type: dbType,
                    max_price: parseInt(priceRange.value, 10)
                })
            });

            const data = await response.json();

            if (!data.success || !data.accommodations || !data.accommodations.length) {
                grid.innerHTML = `
                    <div class="location-activity-card">
                        <h3>Nema smještaja</h3>
                        <p>Nema opcija za odabrane gradove i cijenu.</p>
                    </div>
                `;
                return;
            }

            renderAccommodationByCity(data.accommodations);
        } catch (e) {
            grid.innerHTML = `
                <div class="location-activity-card">
                    <h3>Greška</h3>
                    <p>Smještaj se nije mogao učitati.</p>
                </div>
            `;
        }
    }

    function renderAccommodationByCity(items) {
        const grid = document.getElementById('stayOptionsGrid');
        if (!grid) return;

        const grouped = {};

        items.forEach(item => {
            if (!grouped[item.city]) grouped[item.city] = [];
            grouped[item.city].push(item);
        });

        grid.innerHTML = Object.keys(grouped).map(city => `
            <div class="accommodation-city-box">
                <h3>${escapeHtml(city)}</h3>

                <button 
                    type="button" 
                    class="stay-option-card no-stay-card" 
                    data-city="${escapeHtml(city)}" 
                    data-value="${escapeHtml(city)}|Nije potreban smještaj"
                >
                    <div class="stay-option-body">
                        <strong>Nije potreban smještaj</strong>
                        <span>Preskoči smještaj za ovu lokaciju</span>
                        <div class="stay-option-bottom">
                            <span>—</span>
                            <b>0€</b>
                        </div>
                    </div>
                </button>

                <div class="stay-options-grid-inner">
                    ${grouped[city].map(item => `
                        <button 
                            type="button" 
                            class="stay-option-card" 
                            data-city="${escapeHtml(city)}" 
                            data-value="${escapeHtml(city)}|${escapeHtml(item.name)}"
                        >
                            <img src="${escapeHtml(item.image || 'media/slike/smjestaj-placeholder.jpg')}" class="stay-option-img" alt="">
                            <div class="stay-option-body">
                                <strong>${escapeHtml(item.name)}</strong>
                                <span>${escapeHtml(item.description || '')}</span>
                                <div class="stay-option-bottom">
                                    <span>${escapeHtml(item.city)}</span>
                                    <b>${escapeHtml(item.max_price_per_night)}€</b>
                                </div>
                            </div>
                        </button>
                    `).join('')}
                </div>
            </div>
        `).join('');

        bindAccommodationCards();
    }

    function bindAccommodationCards() {
        const selectedInput = document.getElementById('selected_stay_option_input');

        document.querySelectorAll('.accommodation-city-box').forEach(cityBox => {
            cityBox.querySelectorAll('.stay-option-card').forEach(card => {
                card.addEventListener('click', function () {
                    const city = card.dataset.city;
                    const value = card.dataset.value;

                    cityBox.querySelectorAll('.stay-option-card').forEach(c => {
                        c.classList.remove('selected');
                    });

                    card.classList.add('selected');
                    selectedAccommodationsByCity[city] = value;

                    if (selectedInput) {
                        selectedInput.value = JSON.stringify(selectedAccommodationsByCity);
                    }
                });
            });
        });
    }
    const locationActivityList = document.getElementById('locationActivityList');
    const locationActivityChoicesInput = document.getElementById('location_activity_choices_input');

    function getSelectedGeneralActivities() {
        const selected = [];

        document.querySelectorAll('#activityChoiceGrid .adventure-chip.selected').forEach(chip => {
            if (chip.dataset.value && chip.dataset.value !== 'Ostalo') {
                selected.push(chip.dataset.value);
            }
        });

        const other = document.getElementById('otherActivityInput');
        if (other && other.value.trim()) {
            selected.push(other.value.trim());
        }

        return selected;
    }

    async function buildLocationActivityStep() {
        if (!locationActivityList) return;

        const locations = collectLocations();
        const selectedActivities = getSelectedGeneralActivities();
        const budgetLevel = getBudgetLevel(budgetRange?.value || 100);

        locationActivityList.innerHTML = '';

        if (!locations.length) {
            locationActivityList.innerHTML = `
                <div class="location-activity-card">
                    <h3>Nema lokacija</h3>
                    <p>Vrati se na prvi korak i dodaj barem početnu i završnu lokaciju.</p>
                </div>
            `;
            return;
        }

        if (!selectedActivities.length) {
            locationActivityList.innerHTML = `
                <div class="location-activity-card">
                    <h3>Nema odabranih aktivnosti</h3>
                    <p>Vrati se na korak aktivnosti i odaberi barem jednu aktivnost.</p>
                </div>
            `;
            return;
        }

        locationActivityList.innerHTML = `
            <div class="location-activity-card">
                <h3>Roo traži najbolje ideje...</h3>
                <p>Uspoređujem tvoje gradove, budžet i aktivnosti.</p>
            </div>
        `;

        locationActivityList.innerHTML = '';

        for (const location of locations) {
            const card = document.createElement('div');
            card.className = 'location-activity-card';

            card.innerHTML = `
                <h3>${escapeHtml(location.name)}</h3>
                <p>${escapeHtml(String(location.days))} dana · učitavam prijedloge...</p>
            `;

            locationActivityList.appendChild(card);

            try {
                const response = await fetch('get-city-activities.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        city: location.name,
                        budget_level: budgetLevel,
                        activity_types: selectedActivities
                    })
                });

                const data = await response.json();

                if (!data.success || !data.activities || data.activities.length === 0) {
                    card.innerHTML = `
                        <h3>${escapeHtml(location.name)}</h3>
                        <p>Za ovaj grad zasad nemamo prijedloge u bazi. Podržani gradovi su Zagreb, Karlovac, Osijek, Split i Dubrovnik.</p>
                    `;
                    continue;
                }

                card.innerHTML = `
                    <h3>${escapeHtml(data.city)}</h3>
                    <p>${escapeHtml(String(location.days))} dana · prijedlozi prema tvom budžetu i interesima</p>

                    <div class="location-activity-grid">
                        ${data.activities.map(activity => `
                            <button 
                                type="button" 
                                class="location-activity-chip"
                                data-location="${escapeHtml(data.city)}"
                                data-value="${escapeHtml(activity.name)}"
                                title="${escapeHtml(activity.description || '')}"
                            >
                                ${escapeHtml(activity.name)}
                            </button>
                        `).join('')}
                    </div>
                `;
            } catch (error) {
                card.innerHTML = `
                    <h3>${escapeHtml(location.name)}</h3>
                    <p>Greška pri učitavanju aktivnosti.</p>
                `;
            }
        }

        bindLocationActivityChips();
    }

    function bindLocationActivityChips() {
        document.querySelectorAll('.location-activity-chip').forEach(chip => {
            chip.addEventListener('click', function () {
                chip.classList.toggle('selected');
                saveLocationActivityChoices();
            });
        });
    }

    function saveLocationActivityChoices() {
        const choices = {};

        document.querySelectorAll('.location-activity-chip.selected').forEach(chip => {
            const location = chip.dataset.location;
            const value = chip.dataset.value;

            if (!choices[location]) choices[location] = [];
            choices[location].push(value);
        });

        if (locationActivityChoicesInput) {
            locationActivityChoicesInput.value = JSON.stringify(choices);
        }
    }

    function updateSummary() {
        const summaryBox = document.getElementById('summaryBox');
        if (!summaryBox) return;

        const locations = collectLocations();
        const startDate = document.getElementById('start_date')?.value || '—';
        const endDate = document.getElementById('end_date')?.value || '—';
        const tripTitle = document.getElementById('trip_title')?.value.trim() || 'Moja avantura';
        const budget = budgetRange?.value || '—';
        let tripTypes = [];

        try {
            tripTypes = JSON.parse(document.getElementById('trip_type_input')?.value || '[]');
        } catch (e) {
            tripTypes = [];
        }

        const travelWith = document.getElementById('travel_with_input')?.value || '—';
        const transport = document.getElementById('transport_mode_input')?.value || '—';
        const stayType = stayTypeHidden?.value || '—';
        let stayOption = '—';

        try {
            const stayJson = JSON.parse(selectedStayOptionInput?.value || '{}');
            const stayValues = Object.values(stayJson);

            if (stayValues.length) {
                stayOption = stayValues.map(item => item.replace('|', ': ')).join(', ');
            }
        } catch (e) {
            stayOption = selectedStayOptionInput?.value || '—';
        }

        let activities = [];
        let locationActivities = {};

        try {
            activities = JSON.parse(document.getElementById('activity_tags_input')?.value || '[]');
        } catch (e) {}

        try {
            locationActivities = JSON.parse(locationActivityChoicesInput?.value || '{}');
        } catch (e) {}

        const totalDays = locations.reduce((sum, loc) => sum + (parseInt(loc.days, 10) || 0), 0);

        summaryBox.innerHTML = `
            <div class="summary-section">
                <div class="summary-title">🧭 ${escapeHtml(tripTitle)}</div>
                <div class="summary-row"><span>Datumi:</span><strong>${escapeHtml(startDate)} → ${escapeHtml(endDate)}</strong></div>
                <div class="summary-row"><span>Ukupno dana:</span><strong>${totalDays}</strong></div>

                ${locations.map(loc => `
                    <div class="summary-location">
                        <strong>${escapeHtml(loc.name)}</strong>
                        <span>${escapeHtml(String(loc.days))} dana</span>
                        ${
                            locationActivities[loc.name]
                                ? `<div class="summary-activities">🎯 ${locationActivities[loc.name].map(escapeHtml).join(', ')}</div>`
                                : ''
                        }
                    </div>
                `).join('')}
            </div>

            <div class="summary-section">
                <div class="summary-title">🎯 Detalji</div>
                <div class="summary-pill-row">
                    <span class="summary-pill">Društvo: ${escapeHtml(travelWith)}</span>
                    <span class="summary-pill">Prijevoz: ${escapeHtml(transport)}</span>
                    <span class="summary-pill">Budžet: ${escapeHtml(budget)}€ / dan</span>
                    ${
                        tripTypes.length
                            ? tripTypes.map(type => `<span class="summary-pill">Tip: ${escapeHtml(type)}</span>`).join('')
                            : '<span class="summary-pill">Tip: —</span>'
                    }
                </div>
            </div>

            <div class="summary-section">
                <div class="summary-title">🏨 Smještaj</div>
                <div class="summary-row"><span>Tip:</span><strong>${escapeHtml(stayType)}</strong></div>
                <div class="summary-row"><span>Odabrano:</span><strong>${escapeHtml(stayOption)}</strong></div>
            </div>

            <div class="summary-section">
                <div class="summary-title">🎉 Aktivnosti</div>
                <div class="summary-pill-row">
                    ${
                        activities.length
                            ? activities.map(a => `<span class="summary-pill">${escapeHtml(a)}</span>`).join('')
                            : '<span class="text-muted">Nema odabranih aktivnosti.</span>'
                    }
                </div>
            </div>
        `;
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            if (currentStep < slides.length - 1) {
                collectLocations();
                saveSelectedActivities();
                currentStep++;
                showStep(currentStep);
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    }

    if (form && loader) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (alreadySubmitting) return;

            alreadySubmitting = true;

            collectLocations();
            saveSelectedActivities();

            loader.classList.add('active');

            try {
                const formData = new FormData(form);

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 2500);
                } else {
                    loader.classList.remove('active');
                    alert(data.error || 'Greška');
                    alreadySubmitting = false;
                }

            } catch (err) {
                loader.classList.remove('active');
                alreadySubmitting = false;
                alert('Greška u komunikaciji sa serverom');
            }
        });
    }

    const toast = document.getElementById('rooToast');
    if (toast) {
        setTimeout(() => toast.remove(), 3800);
    }

    showStep(currentStep);
});

function getSelectedTripTypes() {
    const selected = [];

    document.querySelectorAll('[data-target="trip_type_input"] .adventure-chip.selected')
        .forEach(chip => {
            selected.push(chip.dataset.value);
        });

    return selected;
}

const adventureImageInput = document.getElementById('adventure_image');
const adventureImagePreview = document.getElementById('adventureImagePreview');

if (adventureImageInput && adventureImagePreview) {
    adventureImageInput.addEventListener('change', function () {
        const file = adventureImageInput.files[0];

        if (!file) {
            adventureImagePreview.innerHTML = '<span>Pregled slike</span>';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            adventureImagePreview.innerHTML = `
                <img src="${e.target.result}" alt="Pregled slike">
            `;
        };

        reader.readAsDataURL(file);
    });
}