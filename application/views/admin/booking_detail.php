<main>
    <section class="page-hero page-hero-admin">
        <div class="content-shell split-shell">
            <div>
                <span class="section-tag">Booking Detail</span>
                <h1>#<?php echo $booking['summary']['id']; ?> <?php echo $booking['summary']['client']; ?></h1>
                <p class="page-lead">
                    Deep admin view for one booking: summary, media files, and workflow status.
                </p>
            </div>
            <div class="hero-side-note">
                <strong>Status</strong>
                <p><?php echo $booking['summary']['status']; ?> / <?php echo $booking['summary']['payment']; ?></p>
            </div>
        </div>
    </section>

    <section class="dashboard-section">
        <div class="content-shell split-shell booking-detail-shell">
            <div class="table-card">
                <div class="table-card-head">
                    <h2>Booking summary</h2>
                    <a class="text-button" href="<?php echo site_url('admin/bookings'); ?>">Back to all bookings</a>
                </div>
                <ul class="summary-list summary-list-spacious">
                    <li><span>Client</span><strong><?php echo $booking['summary']['client']; ?></strong></li>
                    <li><span>Campaign</span><strong><?php echo $booking['summary']['campaign']; ?></strong></li>
                    <li><span>Cinemas</span><strong><?php echo $booking['summary']['cinemas']; ?></strong></li>
                    <li><span>Term</span><strong><?php echo $booking['summary']['term']; ?></strong></li>
                    <li><span>Value</span><strong><?php echo $booking['summary']['value']; ?></strong></li>
                </ul>
            </div>

            <div class="detail-stack">
                <article class="table-card">
                    <div class="table-card-head">
                        <h2>Media uploads</h2>
                    </div>
                    <div class="upload-review-list">
                        <?php foreach ($booking['uploads'] as $upload): ?>
                            <div class="upload-review-item">
                                <strong><?php echo $upload['file']; ?></strong>
                                <p><?php echo $upload['scope']; ?></p>
                                <span class="status-chip"><?php echo $upload['status']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="table-card">
                    <div class="table-card-head">
                        <h2>Timeline</h2>
                    </div>
                    <ol class="number-list compact-list">
                        <?php foreach ($booking['timeline'] as $step): ?>
                            <li><?php echo $step; ?></li>
                        <?php endforeach; ?>
                    </ol>
                </article>
            </div>
        </div>
    </section>
</main>
