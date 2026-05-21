<main>
    <section class="page-hero">
        <div class="content-shell split-shell">
            <div>
                <span class="section-tag">Customer Dashboard</span>
                <h1>My bookings and uploads</h1>
                <p class="page-lead">
                    This is where the advertiser checks payment, campaign status, and booking summaries.
                </p>
            </div>
            <div class="hero-side-note">
                <strong>Main account actions</strong>
                <p>Review summaries, upload media, and follow approval status for every booking.</p>
            </div>
        </div>
    </section>

    <section class="dashboard-section">
        <div class="content-shell">
            <div class="feature-grid feature-grid-compact">
                <article class="feature-card">
                    <h3>Open tasks</h3>
                    <p>1 booking waiting for uploads and 1 campaign currently live.</p>
                </article>
                <article class="feature-card">
                    <h3>Saved billing details</h3>
                    <p>Reuse company data and invoice contacts for future campaigns.</p>
                </article>
                <article class="feature-card">
                    <h3>Media library</h3>
                    <p>Store trailers and poster files that can be reused later.</p>
                </article>
            </div>

            <div class="table-card">
                <div class="table-card-head">
                    <h2>Booking summary</h2>
                    <a class="text-button" href="<?php echo site_url('booking'); ?>">Create another booking</a>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Campaign</th>
                            <th>Cinemas</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['reference']; ?></td>
                                <td><?php echo $booking['campaign']; ?></td>
                                <td><?php echo $booking['cinemas']; ?></td>
                                <td><span class="status-chip"><?php echo $booking['status']; ?></span></td>
                                <td><?php echo $booking['start_date']; ?></td>
                                <td><?php echo $booking['amount']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
