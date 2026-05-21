<main>
    <section class="page-hero page-hero-admin">
        <div class="content-shell split-shell">
            <div>
                <span class="section-tag">Admin Dashboard</span>
                <h1>Bookings operations overview</h1>
                <p class="page-lead">
                    This area is for the client: every booking, payment status, upload requirement, and review step.
                </p>
            </div>
            <div class="hero-side-note">
                <strong>Core admin need</strong>
                <p>Operations should be able to find any booking quickly and know what action is blocking launch.</p>
            </div>
        </div>
    </section>

    <section class="dashboard-section">
        <div class="content-shell">
            <div class="stats-shell admin-metrics">
                <?php foreach ($metrics as $metric): ?>
                    <article class="stat-card">
                        <strong><?php echo $metric['value']; ?></strong>
                        <span><?php echo $metric['label']; ?></span>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="table-card">
                <div class="table-card-head">
                    <h2>All bookings</h2>
                    <span class="table-note">Current scaffold with demo data</span>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Campaign</th>
                            <th>Cinemas</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Term</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><a class="table-link" href="<?php echo site_url('admin/bookings/' . $booking['id']); ?>">#<?php echo $booking['id']; ?></a></td>
                                <td><?php echo $booking['client']; ?></td>
                                <td><?php echo $booking['campaign']; ?></td>
                                <td><?php echo $booking['cinemas']; ?></td>
                                <td><span class="status-chip"><?php echo $booking['status']; ?></span></td>
                                <td><?php echo $booking['payment']; ?></td>
                                <td><?php echo $booking['term']; ?></td>
                                <td><?php echo $booking['value']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
