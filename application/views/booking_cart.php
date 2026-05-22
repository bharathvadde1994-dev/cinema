<main class="booking-cart-page">
    <section class="booking-cart-section">
        <div class="content-shell booking-cart-shell">
            <div class="booking-cart-main">
                <header class="booking-cart-head">
                    <h1>Your Cart</h1>
                    <p>Review and manage your cinema advertising bookings before checkout.</p>
                </header>

                <a class="booking-cart-add-more" href="<?php echo site_url('cinemas'); ?>">+ Add another cinema</a>

                <div class="booking-cart-list">
                    <?php foreach ($cart_items as $item): ?>
                        <article class="booking-cart-item">
                            <div>
                                <h2><?php echo html_escape($item['name']); ?></h2>
                                <p><?php echo html_escape($item['location_label']); ?> · <?php echo (int) $item['hall_count_selected']; ?> hall · <?php echo (int) $item['spot_length']; ?> sec · <?php echo (int) $item['duration_months']; ?> Months · <?php echo html_escape($item['start_month']); ?> 2026</p>
                                <div class="booking-cart-pricing">
                                    <span>Base cost</span>
                                    <strong>&euro;<?php echo number_format((float) $item['estimated_total'], 0); ?></strong>
                                </div>
                            </div>
                            <div class="booking-cart-item-actions">
                                <a class="booking-cart-edit" href="<?php echo site_url('booking/edit/' . rawurlencode($item['slug'])); ?>">Edit</a>
                                <a class="booking-cart-remove" href="<?php echo site_url('booking/remove/' . rawurlencode($item['slug'])); ?>">Remove</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <aside class="booking-cart-summary">
                <h2>Booking Summary</h2>
                <dl class="booking-summary-list">
                    <div><dt>Total Cinemas</dt><dd><?php echo (int) $cart_summary['cinema_count']; ?></dd></div>
                    <div><dt>Total Halls</dt><dd><?php echo (int) $cart_summary['total_halls']; ?></dd></div>
                    <div><dt>Duration</dt><dd><?php echo (int) $cart_summary['max_duration_months']; ?> Months</dd></div>
                    <div><dt>Spot Length</dt><dd><?php echo html_escape($cart_summary['spot_length_label']); ?></dd></div>
                </dl>
                <div class="booking-cart-pricing-list">
                    <div><span>Base Cost</span><strong>&euro;<?php echo number_format((float) $cart_summary['base_rate'], 0); ?></strong></div>
                    <div><span>Processing Fee (5%)</span><strong>&euro;<?php echo number_format((float) $cart_summary['processing_fee'], 0); ?></strong></div>
                    <div><span>FSK Exam Fee</span><strong>&euro;<?php echo number_format((float) $cart_summary['custom_start_fee'], 0); ?></strong></div>
                    <div><span>VAT (19%)</span><strong>&euro;<?php echo number_format((float) $cart_summary['vat'], 0); ?></strong></div>
                </div>
                <div class="booking-summary-total">
                    <span>Total</span>
                    <strong>&euro;<?php echo number_format((float) $cart_summary['grand_total'], 0); ?></strong>
                </div>
                <a class="header-button full-button" href="<?php echo site_url('booking/checkout'); ?>">Proceed to Checkout</a>
            </aside>
        </div>
    </section>
</main>
