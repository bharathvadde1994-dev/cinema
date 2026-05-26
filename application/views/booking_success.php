<main class="booking-success-page">
    <?php
    $payment = !empty($booking_record['payment_payload']) ? $booking_record['payment_payload'] : array();
    $summary = !empty($payment['summary']) ? $payment['summary'] : array();
    $currency = !empty($summary['currency']) ? $summary['currency'] : $booking_record['currency'];
    $is_monthly = $booking_record['payment_plan'] === 'monthly';
    $is_multi = count($booking_record['items']) > 1;
    $base_rate = isset($summary['base_rate']) ? (float) $summary['base_rate'] : (float) $booking_record['subtotal_amount'];
    $processing_fee = isset($summary['processing_fee']) ? (float) $summary['processing_fee'] : 0.00;
    $custom_start_fee = isset($summary['custom_start_fee']) ? (float) $summary['custom_start_fee'] : 0.00;
    $coupon_discount = isset($summary['coupon_discount']) ? (float) $summary['coupon_discount'] : (float) $booking_record['discount_amount'];
    $coupon_code = isset($summary['coupon_code']) ? (string) $summary['coupon_code'] : '';
    $vat = isset($summary['vat']) ? (float) $summary['vat'] : (float) $booking_record['tax_amount'];
    ?>

    <section class="booking-success-section">
        <div class="content-shell booking-success-shell">
            <div class="booking-success-grid<?php echo $is_multi || $is_monthly ? ' is-wide' : ''; ?>">
                <?php if ($is_multi && $is_monthly): ?>
                    <section class="booking-success-card booking-success-bank-card">
                        <h2>Bank Account Details</h2>
                        <p>Please transfer the total amount to the following account:</p>
                        <dl class="booking-success-bank-list">
                            <div><dt>Account Holder</dt><dd><?php echo html_escape($bank_account_details['account_holder']); ?></dd></div>
                            <div><dt>IBAN</dt><dd><?php echo html_escape($bank_account_details['iban']); ?></dd></div>
                            <div><dt>BIC</dt><dd><?php echo html_escape($bank_account_details['bic']); ?></dd></div>
                            <div><dt>Bank</dt><dd><?php echo html_escape($bank_account_details['bank']); ?></dd></div>
                            <div><dt>Reference</dt><dd><?php echo html_escape($bank_account_details['reference']); ?></dd></div>
                        </dl>
                        <div class="booking-success-bank-note">Important: Please include the reference number in your transfer to ensure proper allocation of your payment.</div>
                    </section>
                <?php endif; ?>

                <section class="booking-success-card">
                    <p class="booking-success-note">A confirmation email has been sent to your registered email address.</p>
                    <h2>Booking Details</h2>
                    <?php if ($is_multi): ?>
                        <div class="booking-success-table">
                            <div class="booking-success-table-head">
                                <span>Cinema</span><span>Location</span><span>Halls</span><span>Ad Length</span><span>Month</span>
                            </div>
                            <?php foreach ($booking_record['items'] as $item): ?>
                                <div class="booking-success-table-row">
                                    <strong><?php echo html_escape($item['cinema_name']); ?></strong>
                                    <span><?php echo html_escape($item['location_label']); ?></span>
                                    <span><?php echo html_escape($item['play_frequency']); ?></span>
                                    <span><?php echo (int) $item['ad_length_seconds']; ?> sec</span>
                                    <span><?php echo html_escape($item['month_label']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="booking-success-info-grid">
                            <div><span>Cinema</span><strong><?php echo html_escape($booking_record['cinema_name']); ?></strong></div>
                            <div><span>Location</span><strong><?php echo html_escape($booking_record['location_label']); ?></strong></div>
                            <div><span>Halls</span><strong><?php echo html_escape($booking_record['play_frequency']); ?></strong></div>
                            <div><span>Duration</span><strong><?php echo (int) $booking_record['term_months']; ?> Months</strong></div>
                            <div><span>Ad Length</span><strong><?php echo (int) $booking_record['ad_length_seconds']; ?> sec</strong></div>
                            <div><span>Cities</span><strong><?php echo html_escape($booking_record['city_label']); ?></strong></div>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="booking-success-card">
                    <h2>Payment Summary</h2>
                    <dl class="booking-success-pricing">
                        <div><dt>Base Cost</dt><dd><?php echo $currency; ?><?php echo number_format($base_rate, 2); ?></dd></div>
                        <?php if ($is_multi && $is_monthly): ?><div><dt>Shipping Cost</dt><dd><?php echo $currency; ?>150.00</dd></div><?php endif; ?>
                        <?php if ($is_monthly): ?><div><dt>Payment</dt><dd>Monthly</dd></div><?php endif; ?>
                        <div><dt>Processing Fee (5%)</dt><dd><?php echo $currency; ?><?php echo number_format($processing_fee, 2); ?></dd></div>
                        <?php if ($custom_start_fee > 0): ?><div><dt>FSK Exam Fee</dt><dd><?php echo $currency; ?><?php echo number_format($custom_start_fee, 2); ?></dd></div><?php endif; ?>
                        <?php if ($coupon_discount > 0): ?><div><dt>Coupon Discount<?php if ($coupon_code !== ''): ?> (<?php echo html_escape($coupon_code); ?>)<?php endif; ?></dt><dd>-<?php echo $currency; ?><?php echo number_format($coupon_discount, 2); ?></dd></div><?php endif; ?>
                        <div><dt>VAT (19%)</dt><dd><?php echo $currency; ?><?php echo number_format($vat, 2); ?></dd></div>
                    </dl>
                    <div class="booking-success-total"><span>Total</span><strong><?php echo $currency; ?><?php echo number_format((float) $booking_record['grand_total'], 2); ?></strong></div>
                </section>

                <?php if (!$is_multi && $is_monthly): ?>
                    <section class="booking-success-card booking-success-bank-card">
                        <h2>Bank Account Details</h2>
                        <p>Please transfer the total amount to the following account:</p>
                        <dl class="booking-success-bank-list">
                            <div><dt>Account Holder</dt><dd><?php echo html_escape($bank_account_details['account_holder']); ?></dd></div>
                            <div><dt>IBAN</dt><dd><?php echo html_escape($bank_account_details['iban']); ?></dd></div>
                            <div><dt>BIC</dt><dd><?php echo html_escape($bank_account_details['bic']); ?></dd></div>
                            <div><dt>Bank</dt><dd><?php echo html_escape($bank_account_details['bank']); ?></dd></div>
                            <div><dt>Reference</dt><dd><?php echo html_escape($bank_account_details['reference']); ?></dd></div>
                        </dl>
                        <div class="booking-success-bank-note">Important: Please include the reference number in your transfer to ensure proper allocation of your payment.</div>
                    </section>
                <?php endif; ?>
            </div>

            <div class="booking-success-actions">
                <a class="header-button" href="<?php echo site_url('booking/upload/' . rawurlencode($booking_record['booking_reference'])); ?>">Upload Media Assets</a>
                <a class="light-button" href="<?php echo site_url(''); ?>">Back To Home</a>
            </div>
        </div>
    </section>

    <?php if (!empty($show_success_modal)): ?>
        <div class="checkout-modal booking-success-modal-overlay">
            <div class="checkout-modal-backdrop"></div>
            <div class="checkout-modal-dialog booking-success-modal">
                <a class="booking-success-modal-close" href="<?php echo site_url('booking/details/' . rawurlencode($booking_record['booking_reference'])); ?>">&times;</a>
                <div class="booking-success-check">&#10003;</div>
                <h2><?php echo $is_monthly && $is_multi ? 'Awaiting Payment Confirmation' : 'Your Slot Has Been Added!'; ?></h2>
                <p>
                    <?php if ($is_monthly && $is_multi): ?>
                        We've received your booking request and generated the invoice successfully. Your cinema slot will be secured once the payment is confirmed.
                    <?php else: ?>
                        Your cinema advertising slot has been successfully booked. We've sent a confirmation email with all the details.
                    <?php endif; ?>
                </p>
                <div class="booking-success-modal-summary">
                    <div><span>Cinema</span><strong><?php echo $is_multi ? count($booking_record['items']) . ' Cinemas' : html_escape($booking_record['cinema_name']); ?></strong></div>
                    <div><span>Halls</span><strong><?php echo html_escape($booking_record['play_frequency']); ?></strong></div>
                    <div><span>Duration</span><strong><?php echo (int) $booking_record['term_months']; ?> Months</strong></div>
                    <?php if ($is_monthly && $is_multi): ?>
                        <div><span>Total Amount Due</span><strong><?php echo $currency; ?><?php echo number_format((float) $booking_record['grand_total'], 2); ?></strong></div>
                    <?php else: ?>
                        <div><span>Payment Type</span><strong><?php echo $is_monthly ? 'Monthly' : 'One Time'; ?></strong></div>
                        <div><span>Total Amount</span><strong><?php echo $currency; ?><?php echo number_format((float) $booking_record['grand_total'], 2); ?></strong></div>
                    <?php endif; ?>
                </div>
                <a class="header-button full-button" href="<?php echo site_url('booking/details/' . rawurlencode($booking_record['booking_reference'])); ?>">View Booking Details</a>
            </div>
        </div>
    <?php endif; ?>
</main>
