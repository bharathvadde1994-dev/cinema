<section class="admin-page-head">
    <h1>Bookings Management</h1>
    <p>Monitor client bookings, payment status, and uploaded campaign assets.</p>
</section>

<div class="admin-toolbar">
    <form class="admin-toolbar-search" action="<?php echo site_url('admin/bookings'); ?>" method="get">
        <input type="text" name="q" value="<?php echo html_escape($admin_filters['q']); ?>" placeholder="Search bookings...">
        <?php if (!empty($admin_filters['filter'])): ?>
            <input type="hidden" name="filter" value="<?php echo html_escape($admin_filters['filter']); ?>">
        <?php endif; ?>
    </form>

    <form class="admin-filter-form" action="<?php echo site_url('admin/bookings'); ?>" method="get">
        <?php if (!empty($admin_filters['q'])): ?>
            <input type="hidden" name="q" value="<?php echo html_escape($admin_filters['q']); ?>">
        <?php endif; ?>
        <select name="filter" onchange="this.form.submit()">
            <option value="">Filter</option>
            <option value="pending_payment"<?php echo $admin_filters['filter'] === 'pending_payment' ? ' selected' : ''; ?>>Pending Payment</option>
            <option value="paid"<?php echo $admin_filters['filter'] === 'paid' ? ' selected' : ''; ?>>Paid</option>
            <option value="missing_assets"<?php echo $admin_filters['filter'] === 'missing_assets' ? ' selected' : ''; ?>>Missing Assets</option>
            <option value="monthly"<?php echo $admin_filters['filter'] === 'monthly' ? ' selected' : ''; ?>>Monthly</option>
            <option value="one_time"<?php echo $admin_filters['filter'] === 'one_time' ? ' selected' : ''; ?>>One-Time</option>
        </select>
    </form>
</div>

<section class="admin-card admin-bookings-card">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="9" class="admin-table-empty">No bookings found.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>
                            <strong><?php echo html_escape($booking['client_name']); ?></strong>
                            <small><?php echo html_escape($booking['client_code']); ?></small>
                        </td>
                        <td><?php echo html_escape($booking['booking_reference']); ?></td>
                        <td><?php echo html_escape($booking['booking_date_label']); ?></td>
                        <td><?php echo (int) $booking['items_count']; ?></td>
                        <td><span class="admin-chip admin-chip-<?php echo strtolower(str_replace(' ', '-', $booking['payment_status_label'])); ?>"><?php echo html_escape($booking['payment_status_label']); ?></span></td>
                        <td><span class="admin-chip admin-chip-<?php echo strtolower($booking['assets_uploaded_label']) === 'yes' ? 'yes' : 'no'; ?>"><?php echo html_escape($booking['assets_uploaded_label']); ?></span></td>
                        <td><?php echo html_escape($booking['payment_method_label']); ?></td>
                        <td><?php echo html_escape($booking['subscription_type_label']); ?></td>
                        <td>
                            <div class="admin-table-actions">
                                <a class="admin-table-icon" href="<?php echo site_url('admin/bookings/' . (int) $booking['id']); ?>">◉</a>
                                <button
                                    class="admin-table-icon admin-table-icon-danger"
                                    type="button"
                                    data-delete-open
                                    data-booking-id="<?php echo (int) $booking['id']; ?>"
                                    data-booking-reference="<?php echo html_escape($booking['booking_reference']); ?>"
                                    data-client-name="<?php echo html_escape($booking['client_name']); ?>"
                                    data-client-code="<?php echo html_escape($booking['client_code']); ?>"
                                >⌫</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="admin-table-footer">
        <span>Showing 1 to <?php echo count($bookings); ?> of <?php echo count($bookings); ?> entries</span>
        <div class="admin-pagination">
            <span class="admin-page-button is-muted">Previous</span>
            <span class="admin-page-button is-active">1</span>
            <span class="admin-page-button is-muted">Next</span>
        </div>
    </div>
</section>

<div class="admin-modal" id="admin-delete-modal" hidden>
    <div class="admin-modal-backdrop" data-admin-modal-close></div>
    <div class="admin-modal-dialog">
        <div class="admin-modal-head">
            <div>
                <h2>Delete Booking</h2>
                <p>This action cannot be undone. Please provide a reason for deleting this booking.</p>
            </div>
            <button type="button" data-admin-modal-close>&times;</button>
        </div>

        <?php echo form_open('', array('id' => 'admin-delete-form')); ?>
            <div class="admin-modal-summary">
                <div><span>Booking ID:</span><strong data-delete-reference>-</strong></div>
                <div><span>Client:</span><strong data-delete-client>-</strong></div>
                <div><span>Client ID:</span><strong data-delete-client-code>-</strong></div>
            </div>

            <label class="admin-modal-label">
                <span>Reason for Deletion *</span>
                <textarea name="delete_reason" placeholder="Enter the reason for deleting this booking..."></textarea>
            </label>

            <div class="admin-warning-box">Warning: Deleting this booking will permanently remove all associated data, including payment records and uploaded assets.</div>

            <div class="admin-modal-actions">
                <button type="button" class="admin-inline-button" data-admin-modal-close>Cancel</button>
                <button type="submit" class="admin-danger-button">Delete Booking</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('admin-delete-modal');
    var form = document.getElementById('admin-delete-form');
    var openButtons = document.querySelectorAll('[data-delete-open]');
    var closeButtons = document.querySelectorAll('[data-admin-modal-close]');
    var reference = document.querySelector('[data-delete-reference]');
    var client = document.querySelector('[data-delete-client]');
    var clientCode = document.querySelector('[data-delete-client-code]');

    openButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            form.action = '<?php echo site_url('admin/bookings/delete'); ?>/' + button.getAttribute('data-booking-id');
            reference.textContent = button.getAttribute('data-booking-reference');
            client.textContent = button.getAttribute('data-client-name');
            clientCode.textContent = button.getAttribute('data-client-code');
            modal.hidden = false;
            document.body.classList.add('admin-modal-open');
        });
    });

    closeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            modal.hidden = true;
            document.body.classList.remove('admin-modal-open');
        });
    });
});
</script>
