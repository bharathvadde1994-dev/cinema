<main>
    <section class="page-hero">
        <div class="content-shell split-shell">
            <div>
                <span class="section-tag">Booking Flow</span>
                <h1>Build a cinema ad booking</h1>
                <p class="page-lead">
                    This is the commercial engine: choose inventory, pricing options, payment, then uploads.
                </p>
            </div>
            <div class="hero-side-note">
                <strong>User outcome</strong>
                <p>The advertiser should always see the current booking summary and price before paying.</p>
            </div>
        </div>
    </section>

    <section class="booking-builder-section">
        <div class="content-shell split-shell booking-shell">
            <div class="builder-panel">
                <h2>Campaign setup</h2>
                <div class="form-card">
                    <label class="form-row">
                        <span>Select cinemas</span>
                        <select>
                            <option>Choose one or more cinemas</option>
                            <?php foreach ($cinemas as $cinema): ?>
                                <option><?php echo $cinema['name']; ?> - <?php echo $cinema['city']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="form-row">
                        <span>Booking duration</span>
                        <select>
                            <?php foreach ($booking_options['durations'] as $duration): ?>
                                <option><?php echo $duration; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="form-row">
                        <span>Ad length</span>
                        <select>
                            <?php foreach ($booking_options['ad_lengths'] as $length): ?>
                                <option><?php echo $length; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="form-row">
                        <span>Campaign start</span>
                        <input type="month" value="2026-07">
                    </label>
                </div>

                <div class="package-grid">
                    <?php foreach ($booking_options['packages'] as $package): ?>
                        <article class="mini-card">
                            <h3><?php echo $package['name']; ?></h3>
                            <p><?php echo $package['description']; ?></p>
                            <strong class="price-line"><?php echo $package['price']; ?></strong>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <aside class="summary-panel">
                <h2>Live booking summary</h2>
                <div class="summary-card">
                    <ul class="summary-list">
                        <li><span>Cinemas</span><strong><?php echo implode(', ', $booking_options['draft']['cinemas']); ?></strong></li>
                        <li><span>Term</span><strong><?php echo $booking_options['draft']['term']; ?></strong></li>
                        <li><span>Ad length</span><strong><?php echo $booking_options['draft']['ad_length']; ?></strong></li>
                        <li><span>Placement</span><strong><?php echo $booking_options['draft']['loop']; ?></strong></li>
                        <li><span>Setup fee</span><strong><?php echo $booking_options['draft']['setup_fee']; ?></strong></li>
                        <li><span>Media review</span><strong><?php echo $booking_options['draft']['media_review']; ?></strong></li>
                    </ul>
                    <div class="summary-total">
                        <span>Monthly</span>
                        <strong><?php echo $booking_options['draft']['monthly_price']; ?></strong>
                    </div>
                    <div class="summary-total summary-total-grand">
                        <span>Total booking</span>
                        <strong><?php echo $booking_options['draft']['total']; ?></strong>
                    </div>
                    <a class="header-button full-button" href="<?php echo site_url('profile'); ?>">Continue to payment</a>
                </div>
            </aside>
        </div>
    </section>

    <section class="journey-section">
        <div class="content-shell split-shell">
            <div class="journey-copy">
                <span class="section-tag">Upload Logic</span>
                <h2>Post-booking media submission</h2>
                <p class="page-lead compact-lead">
                    After payment, the advertiser uploads trailers and artwork. They can assign one file
                    to one cinema or apply it across all selected cinemas in the booking.
                </p>
            </div>
            <div class="journey-grid">
                <article class="mini-card">
                    <h3>Upload once</h3>
                    <p>Choose a master trailer or poster for the whole booking.</p>
                </article>
                <article class="mini-card">
                    <h3>Apply to many</h3>
                    <p>Reuse the same file for multiple selected cinemas with one action.</p>
                </article>
                <article class="mini-card">
                    <h3>Override specific cinemas</h3>
                    <p>Swap files for only one or two locations when needed.</p>
                </article>
                <article class="mini-card">
                    <h3>Review status</h3>
                    <p>Track whether uploads are approved, pending, or require replacement.</p>
                </article>
            </div>
        </div>
    </section>
</main>
