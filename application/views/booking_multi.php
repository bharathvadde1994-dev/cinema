<main class="booking-page booking-page-multi">
    <?php if (empty($booking_cinemas)): ?>
        <section class="booking-empty-section">
            <div class="content-shell">
                <div class="booking-empty-card">
                    <a class="booking-back-link" href="<?php echo site_url('cinemas'); ?>">&larr; Back to search</a>
                    <h1>Select cinemas to continue</h1>
                    <p>Choose at least two cinemas to use the multi-cinema workflow.</p>
                    <a class="header-button" href="<?php echo site_url('cinemas'); ?>">Open Cinemas Directory</a>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="booking-config-section">
            <div class="content-shell booking-config-shell">
                <div class="booking-config-main">
                    <a class="booking-back-link" href="<?php echo $booking_edit_item ? site_url('booking/cart') : site_url('cinemas'); ?>">&larr; <?php echo $booking_edit_item ? 'Back to cart' : 'Back to search'; ?></a>

                    <header class="booking-cinema-head">
                        <div>
                            <h1><?php echo $booking_edit_item ? 'Edit Booking' : 'Plan ' . count($booking_cinemas) . ' Cinemas'; ?></h1>
                            <p><?php echo $booking_edit_item ? ($booking_edit_item['name'] . ' · ' . $booking_edit_item['location_label']) : 'Configure your advertising campaign across selected cinemas.'; ?></p>
                        </div>
                    </header>

                    <div class="booking-multi-selected-list">
                        <?php foreach ($booking_cinemas as $index => $cinema): ?>
                            <div class="booking-multi-selected-item">
                                <span class="booking-multi-index"><?php echo $index + 1; ?></span>
                                <strong><?php echo html_escape($cinema['name']); ?></strong>
                                <span><?php echo html_escape($cinema['location_label']); ?></span>
                                <span><?php echo html_escape($cinema['weekly_reach_label']); ?></span>
                                <span><?php echo html_escape($cinema['hall_count_label']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <form class="booking-config-form" action="<?php echo site_url('booking/add_to_cart'); ?>" method="post" id="multi-cinema-booking">
                        <input type="hidden" name="cinema_slugs" value="<?php echo html_escape(implode(',', $booking_target_slugs)); ?>">
                        <?php if (!empty($booking_edit_item['slug'])): ?>
                            <input type="hidden" name="edit_slug" value="<?php echo html_escape($booking_edit_item['slug']); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="duration" value="<?php echo html_escape((string) $booking_multi_summary['duration_months']); ?>" data-duration-input>
                        <input type="hidden" name="spot_length" value="<?php echo html_escape((string) $booking_multi_summary['spot_length']); ?>" data-spot-input>
                        <input type="hidden" name="start_month" value="<?php echo html_escape($booking_multi_summary['start_month']); ?>" data-month-input>

                        <section class="booking-step-card">
                            <h2>1. Select Number of Halls</h2>
                            <p>Choose how many screens you want your ad to play on concurrently.</p>
                            <div class="booking-step-field">
                                <label for="multi_hall_count">Halls</label>
                                <select id="multi_hall_count" name="hall_count" data-hall-select>
                                    <option value="">Select No. of halls</option>
                                    <?php
                                    $max_halls = 0;
                                    foreach ($booking_cinemas as $cinema) {
                                        $max_halls = max($max_halls, (int) $cinema['hall_count']);
                                    }
                                    for ($i = 1; $i <= $max_halls; $i++):
                                    ?>
                                        <option value="<?php echo $i; ?>"<?php echo (int) $booking_multi_summary['halls'] === $i ? ' selected' : ''; ?>><?php echo $i; ?> Halls</option>
                                    <?php endfor; ?>
                                </select>
                                <small data-hall-error hidden>Please select at least one hall.</small>
                            </div>
                        </section>

                        <section class="booking-step-card">
                            <h2>2. Campaign Duration</h2>
                            <p>How long should your advertisement run?</p>
                            <div class="booking-pill-row" data-duration-group>
                                <?php foreach ($booking_duration_options as $option): ?>
                                    <button class="booking-pill<?php echo (string) $booking_multi_summary['duration_months'] === $option['value'] ? ' is-active' : ''; ?>" type="button" data-duration-option="<?php echo html_escape($option['value']); ?>">
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
                                    <button class="booking-pill<?php echo (string) $booking_multi_summary['spot_length'] === $option['value'] ? ' is-active' : ''; ?>" type="button" data-spot-option="<?php echo html_escape($option['value']); ?>">
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
                                <?php foreach ($booking_month_options as $month): ?>
                                    <button class="booking-pill<?php echo $booking_multi_summary['start_month'] === $month ? ' is-active' : ''; ?>" type="button" data-month-option="<?php echo html_escape($month); ?>">
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

                <aside class="booking-summary-card" data-booking-summary data-cinema-count="<?php echo count($booking_summary_cinemas); ?>" data-cinemas='<?php echo json_encode(array_map(function ($cinema) {
                    return array(
                        'starting_price' => (float) $cinema['starting_price'],
                        'hall_count' => (int) $cinema['hall_count'],
                    );
                }, $booking_summary_cinemas)); ?>'>
                    <h2>Booking Summary</h2>
                    <dl class="booking-summary-list">
                        <div><dt><?php echo count($booking_summary_cinemas) === 1 ? 'Cinema' : 'Cinemas'; ?></dt><dd><?php echo count($booking_summary_cinemas); ?></dd></div>
                        <div><dt>Selected Halls</dt><dd data-summary-halls><?php echo (int) $booking_multi_summary['halls']; ?></dd></div>
                        <div><dt>Duration</dt><dd data-summary-duration><?php echo (int) $booking_multi_summary['duration_months']; ?> Months</dd></div>
                        <div><dt>Spot Length</dt><dd data-summary-spot><?php echo (int) $booking_multi_summary['spot_length']; ?> sec</dd></div>
                    </dl>
                    <div class="booking-summary-total">
                        <span>Estimated Total</span>
                        <strong data-summary-total><?php echo '&euro;' . number_format((float) $booking_multi_summary['total'], 0); ?></strong>
                        <small>Excl. VAT</small>
                    </div>
                    <button class="header-button full-button" type="submit" form="multi-cinema-booking" data-summary-submit>
                        <?php echo $booking_edit_item ? 'Update Changes' : 'Go To Cart'; ?>
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
            var form = document.getElementById('multi-cinema-booking');
            if (!form) { return; }

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
            var contactCard = summary.querySelector('[data-contact-card]');
            var submitButton = summary.querySelector('[data-summary-submit]');
            var cinemas = JSON.parse(summary.getAttribute('data-cinemas') || '[]');
            var customSelectedMonth = monthInput.value;

            function setActive(buttons, activeValue, attributeName) {
                buttons.forEach(function (button) {
                    button.classList.toggle('is-active', button.getAttribute(attributeName) === activeValue);
                });
            }

            function formatCurrency(value) {
                return new Intl.NumberFormat('en-DE', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(value);
            }

            function calculateCinemaTotal(cinema, halls, duration, spot, month) {
                var basePrice = parseFloat(cinema.starting_price || '1800');
                var spotMultiplier = spot >= 30 ? 1.42 : (spot >= 20 ? 1.18 : 1);
                var durationDiscount = duration >= 12 ? 0.92 : (duration >= 6 ? 0.97 : 1);
                var monthSurcharge = month === 'Custom' ? 50 : 0;
                return basePrice * halls * duration * spotMultiplier * durationDiscount + monthSurcharge;
            }

            function refreshSummary() {
                var halls = parseInt(hallSelect.value || '0', 10);
                var durationIsCustom = durationInput.value === 'custom';
                var spotIsCustom = spotInput.value === 'custom';
                var monthIsCustom = monthInput.value === 'Custom';
                var total = 0;

                cinemas.forEach(function (cinema) {
                    total += calculateCinemaTotal(cinema, halls, parseInt(durationInput.value || '0', 10), parseInt(spotInput.value || '0', 10), monthInput.value);
                });

                hallError.hidden = halls > 0;
                summaryHalls.textContent = halls > 0 ? halls : '0';
                summaryDuration.textContent = durationIsCustom ? 'Custom' : durationInput.value + ' Months';
                summarySpot.textContent = spotIsCustom ? 'Custom' : spotInput.value + ' sec';
                durationNote.hidden = !durationIsCustom;
                spotNote.hidden = !spotIsCustom;
                customMonthWrap.hidden = !monthIsCustom;
                contactCard.hidden = !(durationIsCustom || spotIsCustom);
                submitButton.disabled = halls <= 0 || durationIsCustom || spotIsCustom;
                summaryTotal.textContent = (durationIsCustom || spotIsCustom) ? 'Custom' : formatCurrency(total);
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
                        customMonthButtons.forEach(function (monthButton) { monthButton.classList.remove('is-active'); });
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
            refreshSummary();
        });
        </script>
    <?php endif; ?>
</main>
