<section class="admin-page-head">
    <h1>Dashboard</h1>
    <p>Monitor admin metrics and recent booking activity.</p>
</section>

<section class="admin-metric-grid">
    <?php foreach ($metrics as $metric): ?>
        <article class="admin-metric-card">
            <strong><?php echo html_escape($metric['value']); ?></strong>
            <span><?php echo html_escape($metric['label']); ?></span>
        </article>
    <?php endforeach; ?>
</section>

<section class="admin-card">
    <div class="admin-card-head">
        <div>
            <h2>Recent Bookings</h2>
            <p>Latest bookings that need operational review.</p>
        </div>
        <a class="admin-inline-button" href="<?php echo site_url('admin/bookings'); ?>">View All</a>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Booking ID</th>
                    <th>Creation Date</th>
                    <th>Items</th>
                    <th>Payment Made</th>
                    <th>Assets Uploaded</th>
                    <th>Payment Method</th>
                    <th>Subscription Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><strong><?php echo html_escape($booking['client_name']); ?></strong><small><?php echo html_escape($booking['client_code']); ?></small></td>
                        <td><?php echo html_escape($booking['booking_reference']); ?></td>
                        <td><?php echo html_escape($booking['booking_date_label']); ?></td>
                        <td><?php echo (int) $booking['items_count']; ?></td>
                        <td><span class="admin-chip admin-chip-<?php echo strtolower(str_replace(' ', '-', $booking['payment_status_label'])); ?>"><?php echo html_escape($booking['payment_status_label']); ?></span></td>
                        <td><span class="admin-chip admin-chip-<?php echo strtolower($booking['assets_uploaded_label']) === 'yes' ? 'yes' : 'no'; ?>"><?php echo html_escape($booking['assets_uploaded_label']); ?></span></td>
                        <td><?php echo html_escape($booking['payment_method_label']); ?></td>
                        <td><?php echo html_escape($booking['subscription_type_label']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
