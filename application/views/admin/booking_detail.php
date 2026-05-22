<?php
$selected_item = $selected_booking_item;
$payment = !empty($booking['latest_payment']) ? $booking['latest_payment'] : array();
$amount_paid = $booking['payment_plan'] === 'monthly'
    ? (float) $booking['initial_payment_amount']
    : (float) $booking['grand_total'];
$remaining_due = (float) $booking['remaining_balance_amount'];
$invoice_status = $booking['payment_status'] === 'paid' ? 'Paid' : 'Pending';
?>

<div class="admin-detail-back">
    <a href="<?php echo site_url('admin/bookings'); ?>">&larr; Back to Bookings</a>
</div>

<section class="admin-page-head admin-page-head-tight">
    <h1>Booking Details</h1>
    <p>Review booking information, payment details, uploaded assets, and campaign configuration.</p>
</section>

<div class="admin-detail-layout">
    <aside class="admin-detail-sidebar">
        <h2>Cinemas</h2>
        <div class="admin-detail-cinema-list">
            <?php foreach ($booking['items'] as $item): ?>
                <a class="admin-detail-cinema-link<?php echo (int) $item['id'] === (int) $selected_item['id'] ? ' is-active' : ''; ?>" href="<?php echo site_url('admin/bookings/' . (int) $booking['id'] . '?item=' . (int) $item['id']); ?>">
                    <strong><?php echo html_escape($item['cinema_name']); ?></strong>
                    <span><?php echo html_escape($item['city'] . ', ' . $item['region']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </aside>

    <div class="admin-detail-main">
        <section class="admin-card admin-summary-strip">
            <div><span>Booking ID</span><strong><?php echo html_escape($booking['booking_reference']); ?></strong></div>
            <div><span>Client Name</span><strong><?php echo html_escape($booking['client_name']); ?></strong></div>
            <div><span>Booking Date</span><strong><?php echo html_escape($booking['booking_date_label']); ?></strong></div>
            <div><span>Payment Status</span><strong><span class="admin-chip admin-chip-<?php echo strtolower(str_replace(' ', '-', $booking['payment_status_label'])); ?>"><?php echo html_escape($booking['payment_status_label']); ?></span></strong></div>
            <div><span>Assets Uploaded</span><strong><span class="admin-chip admin-chip-<?php echo strtolower($booking['assets_uploaded_label']) === 'yes' ? 'yes' : 'no'; ?>"><?php echo html_escape($booking['assets_uploaded_label']); ?></span></strong></div>
            <div><span>Booking Type</span><strong><?php echo html_escape($booking['booking_type_label']); ?></strong></div>
        </section>

        <section class="admin-card">
            <div class="admin-card-head">
                <h2>Cinema Details</h2>
            </div>
            <div class="admin-detail-grid">
                <div><span>Cinema Name</span><strong><?php echo html_escape($selected_item['cinema_name']); ?></strong></div>
                <div><span>Location</span><strong><?php echo html_escape($selected_item['location_label']); ?></strong></div>
                <div><span>Weekly Reach</span><strong><?php echo html_escape($selected_item['weekly_reach_label']); ?></strong></div>
                <div><span>Screens Available</span><strong><?php echo (int) $selected_item['hall_count']; ?></strong></div>
                <div><span>Selected Halls</span><strong><?php echo (int) $selected_item['selected_halls']; ?></strong></div>
                <div><span>Spot Length</span><strong><?php echo (int) $selected_item['ad_length_seconds']; ?> seconds</strong></div>
                <div><span>Campaign Duration</span><strong><?php echo (int) $selected_item['term_months']; ?> Months</strong></div>
                <div><span>Selected Months</span><strong><?php echo html_escape($selected_item['selected_months_label']); ?></strong></div>
            </div>
        </section>

        <section class="admin-card">
            <div class="admin-card-head">
                <h2>Booking Details</h2>
                <span class="admin-inline-button is-static">Live Data</span>
            </div>
            <div class="admin-detail-grid">
                <div><span>Booking Reference ID</span><strong><?php echo html_escape($booking['booking_reference']); ?></strong></div>
                <div><span>Booking Type</span><strong><?php echo html_escape($booking['booking_type_label']); ?></strong></div>
                <div><span>Created Date</span><strong><?php echo html_escape($booking['booking_date_label']); ?></strong></div>
                <div><span>Campaign Start Month</span><strong><?php echo date('F Y', strtotime($booking['start_date'])); ?></strong></div>
                <div><span>Campaign Duration</span><strong><?php echo (int) $booking['term_months']; ?> Months</strong></div>
                <div><span>Spot Length</span><strong><?php echo (int) $booking['ad_length_seconds']; ?> seconds</strong></div>
                <div><span>Total Cinemas Booked</span><strong><?php echo count($booking['items']); ?></strong></div>
                <div><span>Booking Status</span><strong><span class="admin-chip admin-chip-<?php echo strtolower(str_replace(' ', '-', $booking['status_badge'])); ?>"><?php echo html_escape($booking['status_badge']); ?></span></strong></div>
            </div>
        </section>

        <section class="admin-card">
            <div class="admin-card-head">
                <h2>Payment Details</h2>
                <div class="admin-card-actions">
                    <a class="admin-inline-button" href="<?php echo site_url('admin/bookings/document/' . (int) $booking['id'] . '/invoice'); ?>">Download Invoice</a>
                    <a class="admin-inline-button" href="<?php echo site_url('admin/bookings/document/' . (int) $booking['id'] . '/receipt'); ?>">Download Receipt</a>
                </div>
            </div>
            <div class="admin-detail-grid">
                <div><span>Payment Method</span><strong><?php echo html_escape($booking['payment_method_label']); ?></strong></div>
                <div><span>Payment Plan</span><strong><?php echo html_escape($booking['subscription_type_label']); ?></strong></div>
                <div><span>Total Amount</span><strong><?php echo html_escape($booking['currency']); ?><?php echo number_format((float) $booking['grand_total'], 2); ?></strong></div>
                <div><span>VAT Amount</span><strong><?php echo html_escape($booking['currency']); ?><?php echo number_format((float) $booking['tax_amount'], 2); ?></strong></div>
                <div><span>Processing Fee</span><strong><?php echo html_escape($booking['currency']); ?><?php echo number_format(max(0, (float) $booking['setup_fee_total'] - 250), 2); ?></strong></div>
                <div><span>Amount Paid</span><strong><?php echo html_escape($booking['currency']); ?><?php echo number_format($amount_paid, 2); ?></strong></div>
                <div><span>Remaining Due</span><strong class="admin-amount-due"><?php echo html_escape($booking['currency']); ?><?php echo number_format($remaining_due, 2); ?></strong></div>
                <div><span>Next Billing Date</span><strong><?php echo $booking['payment_plan'] === 'monthly' ? date('M d, Y', strtotime('+1 month', strtotime($booking['start_date']))) : '-'; ?></strong></div>
                <div><span>Transaction Reference</span><strong><?php echo !empty($payment['transaction_reference']) ? html_escape($payment['transaction_reference']) : '-'; ?></strong></div>
                <div><span>Invoice Status</span><strong><span class="admin-chip admin-chip-<?php echo strtolower($invoice_status); ?>"><?php echo $invoice_status; ?></span></strong></div>
            </div>
            <?php if ($remaining_due > 0): ?>
                <div class="admin-warning-box"><?php echo html_escape($booking['currency']); ?><?php echo number_format($remaining_due, 2); ?> remaining across monthly payments</div>
            <?php endif; ?>
        </section>

        <section class="admin-card">
            <div class="admin-card-head">
                <h2>Uploaded Assets</h2>
                <div class="admin-card-actions">
                    <form action="<?php echo site_url('admin/bookings/request-reupload/' . (int) $booking['id']); ?>" method="post">
                        <button type="submit" class="admin-inline-button">Re-request Assets</button>
                    </form>
                    <form action="<?php echo site_url('admin/bookings/assets-reviewed/' . (int) $booking['id']); ?>" method="post">
                        <button type="submit" class="admin-inline-button">Mark Assets Reviewed</button>
                    </form>
                </div>
            </div>

            <?php if (empty($booking['assets'])): ?>
                <div class="admin-empty-assets">
                    <div class="admin-empty-assets-icon">⌲</div>
                    <strong>No assets uploaded yet</strong>
                    <p>The client has not uploaded campaign assets for this booking.</p>
                </div>
            <?php else: ?>
                <div class="admin-asset-list">
                    <?php foreach ($booking['assets'] as $asset): ?>
                        <div class="admin-asset-item">
                            <div>
                                <strong><?php echo html_escape($asset['original_name']); ?></strong>
                                <small><?php echo date('M d, Y h:i A', strtotime($asset['uploaded_at'])); ?></small>
                            </div>
                            <div class="admin-asset-actions">
                                <span class="admin-chip admin-chip-reviewed"><?php echo $asset['review_status'] === 'approved' ? 'Reviewed' : ucfirst(str_replace('_', ' ', $asset['review_status'])); ?></span>
                                <a class="admin-table-icon" href="<?php echo base_url($asset['storage_path']); ?>" target="_blank" rel="noopener">◉</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
