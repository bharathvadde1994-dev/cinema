<main class="booking-page">
    <?php if (empty($booking_cinema)): ?>
        <section class="booking-empty-section">
            <div class="content-shell">
                <div class="booking-empty-card">
                    <a class="booking-back-link" href="<?php echo site_url('cinemas'); ?>">&larr; Back to search</a>
                    <h1>Select one cinema to continue</h1>
                    <p>The next booking step is enabled only for a single selected cinema right now. Start from the cinema directory and choose one cinema to continue.</p>
                    <a class="header-button" href="<?php echo site_url('cinemas'); ?>">Open Cinemas Directory</a>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="booking-config-section">
            <div class="content-shell booking-config-shell">
                <div class="booking-config-main">
                    <a class="booking-back-link" href="<?php echo site_url('cinemas'); ?>">&larr; Back to search</a>

                    <header class="booking-cinema-head">
                        <div>
                            <h1><?php echo html_escape($booking_cinema['name']); ?></h1>
                            <p><?php echo html_escape($booking_cinema['location_label']); ?></p>
                            <div class="booking-cinema-stats">
                                <span><?php echo html_escape($booking_cinema['weekly_reach_label']); ?></span>
                                <span><?php echo html_escape($booking_cinema['hall_count_label']); ?></span>
                            </div>
                        </div>
                    </header>

                    <form class="booking-config-form" action="<?php echo site_url('booking/add_to_cart'); ?>" method="post" id="single-cinema-booking">
                        <input type="hidden" name="cinema_slug" value="<?php echo html_escape($booking_cinema['slug']); ?>">
                        <input type="hidden" name="duration" value="6" data-duration-input>
                        <input type="hidden" name="spot_length" value="10" data-spot-input>
                        <input type="hidden" name="start_month" value="Oct" data-month-input>

                        <section class="booking-step-card">
                            <h2>1. Select Number of Halls</h2>
                            <p>Choose how many screens you want your ad to play on concurrently.</p>
                            <div class="booking-step-field">
                                <label for="hall_count">Halls</label>
                                <select id="hall_count" name="hall_count" data-hall-select>
                                    <option value="">Select No. of halls</option>
                                    <?php for ($i = 1; $i <= (int) $booking_cinema['hall_count']; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> Halls</option>
                                    <?php endfor; ?>
                                </select>
                                <small data-hall-error>Please select at least one hall.</small>
                            </div>
                        </section>

                        <section class="booking-step-card">
                            <h2>2. Campaign Duration</h2>
                            <p>How long should your advertisement run?</p>
                            <div class="booking-pill-row" data-duration-group>
                                <?php foreach ($booking_duration_options as $option): ?>
                                    <button
                                        class="booking-pill<?php echo $option['value'] === '6' ? ' is-active' : ''; ?>"
                                        type="button"
                                        data-duration-option="<?php echo html_escape($option['value']); ?>"
                                    >
                                        <?php echo html_escape($option['label']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <div class="booking-note booking-note-duration" data-duration-note hidden>
                                For custom campaign durations (less than 6 months or more than 12 months), our sales team will create a tailored package for you.
                            </div>
                        </section>

                        <section class="booking-step-card">
                            <h2>3. Ad Spot Length</h2>
                            <p>Select the duration of your video asset.</p>
                            <div class="booking-pill-row" data-spot-group>
                                <?php foreach ($booking_spot_options as $option): ?>
                                    <button
                                        class="booking-pill<?php echo $option['value'] === '10' ? ' is-active' : ''; ?>"
                                        type="button"
                                        data-spot-option="<?php echo html_escape($option['value']); ?>"
                                    >
                                        <?php echo html_escape($option['label']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <div class="booking-note booking-note-spot" data-spot-note hidden>
                                For custom ad spot length (more than 30 sec), our sales team will create a tailored package for you.
                            </div>
                        </section>

                        <section class="booking-step-card">
                            <h2>4. Select Month</h2>
                            <p>Select the starting month of advertisement</p>
                            <div class="booking-pill-row" data-month-group>
                                <?php foreach ($booking_month_options as $index => $month): ?>
                                    <button
                                        class="booking-pill<?php echo $month === 'Oct' ? ' is-active' : ''; ?>"
                                        type="button"
                                        data-month-option="<?php echo html_escape($month); ?>"
                                    >
                                        <?php echo html_escape($month); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <div class="booking-month-custom-wrap" data-custom-month-wrap hidden>
                                <div class="booking-note-inline">If a custom month is selected, an additional charge of 50 Euros will be applied.</div>
                                <div class="booking-pill-row booking-pill-row-wrap" data-custom-month-group>
                                    <?php foreach ($booking_month_custom_options as $month): ?>
                                        <button type="button" class="booking-pill booking-pill-small" data-custom-month-option="<?php echo html_escape($month); ?>">
                                            <?php echo html_escape($month); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>

                <aside class="booking-summary-card" data-booking-summary data-base-price="<?php echo html_escape((string) $booking_cinema['starting_price']); ?>">
                    <h2>Booking Summary</h2>
                    <dl class="booking-summary-list">
                        <div>
                            <dt>Cinema</dt>
                            <dd><?php echo html_escape($booking_cinema['name']); ?></dd>
                        </div>
                        <div>
                            <dt>Selected Halls</dt>
                            <dd data-summary-halls>0</dd>
                        </div>
                        <div>
                            <dt>Duration</dt>
                            <dd data-summary-duration>6 Months</dd>
                        </div>
                        <div>
                            <dt>Spot Length</dt>
                            <dd data-summary-spot>10 sec</dd>
                        </div>
                    </dl>

                    <div class="booking-summary-total">
                        <span>Estimated Total</span>
                        <strong data-summary-total>&euro;0</strong>
                        <small>Excl. VAT</small>
                    </div>

                    <button class="header-button full-button" type="submit" form="single-cinema-booking" data-summary-submit disabled>
                        Add to Cart
                    </button>

                    <div class="booking-contact-card" data-contact-card hidden>
                        <p>Contact us to generate custom pricing based on your unique requirements.</p>
                        <a class="dark-button full-button" href="tel:+493012345678">+49 30 1234 5678</a>
                    </div>
                </aside>
            </div>
        </section>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('single-cinema-booking');

            if (!form) {
                return;
            }

            var hallSelect = form.querySelector('[data-hall-select]');
            var hallError = form.querySelector('[data-hall-error]');
            var durationInput = form.querySelector('[data-duration-input]');
            var spotInput = form.querySelector('[data-spot-input]');
            var monthInput = form.querySelector('[data-month-input]');
            var durationButtons = form.querySelectorAll('[data-duration-option]');
            var spotButtons = form.querySelectorAll('[data-spot-option]');
            var monthButtons = form.querySelectorAll('[data-month-option]');
            var customMonthButtons = form.querySelectorAll('[data-custom-month-option]');
            var durationNote = form.querySelector('[data-duration-note]');
            var spotNote = form.querySelector('[data-spot-note]');
            var customMonthWrap = form.querySelector('[data-custom-month-wrap]');
            var summary = document.querySelector('[data-booking-summary]');
            var summaryHalls = summary.querySelector('[data-summary-halls]');
            var summaryDuration = summary.querySelector('[data-summary-duration]');
            var summarySpot = summary.querySelector('[data-summary-spot]');
            var summaryTotal = summary.querySelector('[data-summary-total]');
            var summarySubmit = summary.querySelector('[data-summary-submit]');
            var contactCard = summary.querySelector('[data-contact-card]');
            var basePrice = parseFloat(summary.getAttribute('data-base-price') || '1800');
            var customSelectedMonth = 'Oct';

            function setActive(buttons, activeValue, attributeName) {
                buttons.forEach(function (button) {
                    button.classList.toggle('is-active', button.getAttribute(attributeName) === activeValue);
                });
            }

            function formatCurrency(value) {
                return new Intl.NumberFormat('en-DE', {
                    style: 'currency',
                    currency: 'EUR',
                    maximumFractionDigits: 0
                }).format(value);
            }

            function currentDurationLabel() {
                return durationInput.value === 'custom' ? 'Custom' : durationInput.value + ' Months';
            }

            function currentSpotLabel() {
                return spotInput.value === 'custom' ? 'Custom' : spotInput.value + ' sec';
            }

            function calculateTotal(halls, duration, spot, month) {
                var spotMultiplier = 1;
                var durationDiscount = 1;
                var monthSurcharge = month === 'Custom' ? 50 : 0;

                if (spot >= 30) {
                    spotMultiplier = 1.42;
                } else if (spot >= 20) {
                    spotMultiplier = 1.18;
                }

                if (duration >= 12) {
                    durationDiscount = 0.92;
                } else if (duration >= 6) {
                    durationDiscount = 0.97;
                }

                return Math.round(basePrice * halls * duration * spotMultiplier * durationDiscount + monthSurcharge);
            }

            function refreshSummary() {
                var halls = parseInt(hallSelect.value || '0', 10);
                var durationIsCustom = durationInput.value === 'custom';
                var spotIsCustom = spotInput.value === 'custom';
                var monthIsCustom = monthInput.value === 'Custom';
                var canSubmit = halls > 0 && !durationIsCustom && !spotIsCustom;
                var needsContact = halls > 0 && (durationIsCustom || spotIsCustom);

                hallError.hidden = halls > 0;
                summaryHalls.textContent = halls > 0 ? halls : '0';
                summaryDuration.textContent = currentDurationLabel();
                summarySpot.textContent = currentSpotLabel();
                durationNote.hidden = !durationIsCustom;
                spotNote.hidden = !spotIsCustom;
                customMonthWrap.hidden = !monthIsCustom;

                if (monthIsCustom) {
                    monthInput.value = 'Custom';
                }

                if (canSubmit) {
                    summaryTotal.textContent = formatCurrency(
                        calculateTotal(halls, parseInt(durationInput.value, 10), parseInt(spotInput.value, 10), monthInput.value)
                    );
                } else if (needsContact) {
                    summaryTotal.textContent = 'Custom';
                } else {
                    summaryTotal.textContent = formatCurrency(0);
                }

                summarySubmit.disabled = !canSubmit;
                contactCard.hidden = !needsContact;
            }

            durationButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    durationInput.value = button.getAttribute('data-duration-option');
                    setActive(durationButtons, durationInput.value, 'data-duration-option');
                    refreshSummary();
                });
            });

            spotButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    spotInput.value = button.getAttribute('data-spot-option');
                    setActive(spotButtons, spotInput.value, 'data-spot-option');
                    refreshSummary();
                });
            });

            monthButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    monthInput.value = button.getAttribute('data-month-option');
                    setActive(monthButtons, monthInput.value, 'data-month-option');

                    if (monthInput.value !== 'Custom') {
                        customSelectedMonth = monthInput.value;
                        customMonthButtons.forEach(function (monthButton) {
                            monthButton.classList.remove('is-active');
                        });
                    }

                    refreshSummary();
                });
            });

            customMonthButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    customSelectedMonth = button.getAttribute('data-custom-month-option');
                    customMonthButtons.forEach(function (monthButton) {
                        monthButton.classList.toggle('is-active', monthButton === button);
                    });
                });
            });

            hallSelect.addEventListener('change', refreshSummary);

            form.addEventListener('submit', function (event) {
                if (!hallSelect.value) {
                    event.preventDefault();
                    refreshSummary();
                    hallSelect.focus();
                    return;
                }

                if (durationInput.value === 'custom' || spotInput.value === 'custom') {
                    event.preventDefault();
                    refreshSummary();
                }

                if (monthInput.value === 'Custom' && customSelectedMonth) {
                    monthInput.value = 'Custom';
                }
            });

            refreshSummary();
        });
        </script>
    <?php endif; ?>
</main>
