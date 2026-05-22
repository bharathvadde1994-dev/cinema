<main class="checkout-page">
    <?php
    $billing = !empty($checkout_draft['billing_address']) ? $checkout_draft['billing_address'] : array();
    $delivery = !empty($checkout_draft['delivery_address']) ? $checkout_draft['delivery_address'] : array();
    $payment = !empty($checkout_draft['payment_details']) ? $checkout_draft['payment_details'] : array();
    $same_as_billing = isset($checkout_draft['same_as_billing']) ? (bool) $checkout_draft['same_as_billing'] : TRUE;
    ?>

    <section class="checkout-section">
        <div class="content-shell">
            <div class="checkout-progress">
                <div class="checkout-progress-step is-complete">
                    <span class="checkout-progress-dot">1</span>
                    <span class="checkout-progress-label">Choose Booking</span>
                </div>
                <span class="checkout-progress-line is-complete"></span>
                <div class="checkout-progress-step is-active">
                    <span class="checkout-progress-dot">2</span>
                    <span class="checkout-progress-label">Enter Info</span>
                </div>
                <span class="checkout-progress-line"></span>
                <div class="checkout-progress-step">
                    <span class="checkout-progress-dot">3</span>
                    <span class="checkout-progress-label">Pay</span>
                </div>
            </div>

            <div class="checkout-shell">
                <div class="checkout-main">
                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Billing Address</h2>
                            <button type="button" class="checkout-inline-link" data-modal-open="billing-modal">
                                <?php echo !empty($billing['first_name']) || !empty($billing['company']) ? 'Edit' : '+ Add Address'; ?>
                            </button>
                        </div>

                        <?php if (!empty($billing['first_name']) || !empty($billing['company'])): ?>
                            <div class="checkout-address-block">
                                <strong><?php echo html_escape(trim(($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? ''))); ?></strong>
                                <?php if (!empty($billing['company'])): ?><span><?php echo html_escape($billing['company']); ?></span><?php endif; ?>
                                <span><?php echo html_escape($billing['street'] ?? ''); ?></span>
                                <?php if (!empty($billing['additional'])): ?><span><?php echo html_escape($billing['additional']); ?></span><?php endif; ?>
                                <span><?php echo html_escape(trim(($billing['postcode'] ?? '') . ' ' . ($billing['city'] ?? ''))); ?></span>
                                <span><?php echo html_escape($billing['country'] ?? ''); ?></span>
                            </div>
                        <?php else: ?>
                            <p class="checkout-muted">No billing address added.</p>
                        <?php endif; ?>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Delivery Address</h2>
                        </div>

                        <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-delivery-form" id="delivery-toggle-form">
                            <input type="hidden" name="form_action" value="delivery">
                            <label class="checkout-checkbox-row">
                                <input type="checkbox" name="same_as_billing" value="1" <?php echo $same_as_billing ? 'checked' : ''; ?> data-same-as-billing>
                                <span>Same as billing address</span>
                            </label>
                        </form>

                        <div class="checkout-delivery-panel<?php echo $same_as_billing ? ' is-hidden' : ''; ?>" data-delivery-panel>
                            <?php if (!empty($delivery['street']) || !empty($delivery['city'])): ?>
                                <div class="checkout-address-block checkout-address-block-delivery">
                                    <strong>Delivery Address</strong>
                                    <span><?php echo html_escape($delivery['street'] ?? ''); ?></span>
                                    <?php if (!empty($delivery['additional'])): ?><span><?php echo html_escape($delivery['additional']); ?></span><?php endif; ?>
                                    <span><?php echo html_escape(trim(($delivery['postcode'] ?? '') . ' ' . ($delivery['city'] ?? ''))); ?></span>
                                    <span><?php echo html_escape($delivery['country'] ?? ''); ?></span>
                                </div>
                            <?php else: ?>
                                <p class="checkout-muted">No delivery address added.</p>
                            <?php endif; ?>

                            <button type="button" class="checkout-address-button" data-modal-open="delivery-modal">
                                <?php echo (!empty($delivery['street']) || !empty($delivery['city'])) ? '+ Edit Address' : '+ Add Address'; ?>
                            </button>
                        </div>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Payment Method</h2>
                        </div>

                        <div class="checkout-payment-list">
                            <div class="checkout-payment-option">
                                <span class="checkout-radio"></span>
                                <span class="checkout-payment-copy">
                                    <strong>Invoice</strong>
                                </span>
                            </div>

                            <button type="button" class="checkout-payment-option is-active" data-modal-open="payment-modal">
                                <span class="checkout-radio is-active"></span>
                                <span class="checkout-payment-copy">
                                    <strong>Card Payment</strong>
                                    <small><?php echo !empty($payment['last4']) ? '•••• •••• •••• ' . html_escape($payment['last4']) : 'Add Stripe card details'; ?></small>
                                </span>
                                <span class="checkout-inline-link">Edit</span>
                            </button>

                            <div class="checkout-payment-option">
                                <span class="checkout-radio"></span>
                                <span class="checkout-payment-copy">
                                    <strong>Klarna</strong>
                                </span>
                            </div>

                            <div class="checkout-payment-option">
                                <span class="checkout-radio"></span>
                                <span class="checkout-payment-copy">
                                    <strong>Apple Pay</strong>
                                </span>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="checkout-summary-card">
                    <div class="checkout-summary-note">
                        The price includes professional ad video production. If you don't have a video, we will create one using your media assets.
                    </div>

                    <div class="checkout-summary-section">
                        <h2>Booking Summary</h2>
                        <dl class="checkout-summary-list">
                            <div><dt>Cinema</dt><dd><?php echo html_escape($checkout_draft['cinema_name']); ?></dd></div>
                            <div><dt>Location</dt><dd><?php echo html_escape($checkout_draft['location_label']); ?></dd></div>
                            <div><dt>Halls</dt><dd><?php echo (int) $checkout_draft['hall_count_selected']; ?></dd></div>
                            <div><dt>Duration</dt><dd><?php echo html_escape($checkout_draft['duration_months']); ?> Months</dd></div>
                            <div><dt>Ad Length</dt><dd><?php echo html_escape($checkout_draft['spot_length']); ?> sec</dd></div>
                        </dl>
                    </div>

                    <div class="checkout-summary-section">
                        <h3>Price Breakdown</h3>
                        <dl class="checkout-summary-list checkout-summary-list-pricing">
                            <div><dt>Base Cost</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['base_rate'], 2); ?></dd></div>
                            <div><dt>Processing Fee (5%)</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['processing_fee'], 2); ?></dd></div>
                            <?php if ($checkout_summary['custom_start_fee'] > 0): ?>
                                <div><dt>FSK Exam Fee</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['custom_start_fee'], 2); ?></dd></div>
                            <?php endif; ?>
                            <?php if ($checkout_summary['coupon_discount'] > 0): ?>
                                <div><dt>Coupon Discount</dt><dd>-<?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['coupon_discount'], 2); ?></dd></div>
                            <?php endif; ?>
                            <div><dt>Subtotal</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['subtotal'], 2); ?></dd></div>
                            <div><dt>VAT (19%)</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['vat'], 2); ?></dd></div>
                        </dl>
                    </div>

                    <div class="checkout-summary-total">
                        <span>Total</span>
                        <strong><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['grand_total'], 2); ?></strong>
                    </div>

                    <form action="<?php echo site_url('booking/place_order'); ?>" method="post" class="checkout-order-form">
                        <button type="submit" class="header-button full-button">Order Now</button>
                        <label class="checkout-terms-row">
                            <input type="checkbox" name="accept_terms" value="1">
                            <span>I agree to the Terms &amp; Conditions and Privacy Policy of Kinoblick.</span>
                        </label>
                    </form>
                </aside>
            </div>
        </div>
    </section>

    <div class="checkout-modal" id="billing-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog">
            <div class="checkout-modal-head">
                <h2>Billing Address</h2>
                <button type="button" data-modal-close>&times;</button>
            </div>

            <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-modal-form">
                <input type="hidden" name="form_action" value="billing">
                <div class="checkout-field-grid">
                    <label>
                        <span>Company</span>
                        <input type="text" name="company" value="<?php echo html_escape($billing['company'] ?? ''); ?>">
                    </label>
                    <label>
                        <span>Salutation</span>
                        <input type="text" name="salutation" value="<?php echo html_escape($billing['salutation'] ?? 'Mr'); ?>">
                    </label>
                </div>
                <div class="checkout-field-grid">
                    <label>
                        <span>First Name</span>
                        <input type="text" name="first_name" value="<?php echo html_escape($billing['first_name'] ?? ''); ?>">
                    </label>
                    <label>
                        <span>Last Name</span>
                        <input type="text" name="last_name" value="<?php echo html_escape($billing['last_name'] ?? ''); ?>">
                    </label>
                </div>
                <label>
                    <span>Street Address</span>
                    <input type="text" name="street" value="<?php echo html_escape($billing['street'] ?? ''); ?>">
                </label>
                <label>
                    <span>Additional Address Line</span>
                    <input type="text" name="additional" value="<?php echo html_escape($billing['additional'] ?? ''); ?>">
                </label>
                <div class="checkout-field-grid">
                    <label>
                        <span>Postcode</span>
                        <input type="text" name="postcode" value="<?php echo html_escape($billing['postcode'] ?? ''); ?>">
                    </label>
                    <label>
                        <span>City</span>
                        <input type="text" name="city" value="<?php echo html_escape($billing['city'] ?? ''); ?>">
                    </label>
                </div>
                <div class="checkout-field-grid">
                    <label>
                        <span>Country</span>
                        <input type="text" name="country" value="<?php echo html_escape($billing['country'] ?? 'Germany'); ?>">
                    </label>
                    <label>
                        <span>Phone</span>
                        <input type="text" name="phone" value="<?php echo html_escape($billing['phone'] ?? ''); ?>">
                    </label>
                </div>
                <div class="checkout-modal-actions">
                    <button type="button" class="light-button" data-modal-close>Cancel</button>
                    <button type="submit" class="dark-button">Save Address</button>
                </div>
            </form>
        </div>
    </div>

    <div class="checkout-modal" id="delivery-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog">
            <div class="checkout-modal-head">
                <h2>Delivery Address</h2>
                <button type="button" data-modal-close>&times;</button>
            </div>

            <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-modal-form">
                <input type="hidden" name="form_action" value="delivery">
                <input type="hidden" name="same_as_billing" value="0">
                <label>
                    <span>Street Address</span>
                    <input type="text" name="delivery_street" value="<?php echo html_escape($delivery['street'] ?? ''); ?>">
                </label>
                <label>
                    <span>Additional Address Line</span>
                    <input type="text" name="delivery_additional" value="<?php echo html_escape($delivery['additional'] ?? ''); ?>">
                </label>
                <div class="checkout-field-grid">
                    <label>
                        <span>Postcode</span>
                        <input type="text" name="delivery_postcode" value="<?php echo html_escape($delivery['postcode'] ?? ''); ?>">
                    </label>
                    <label>
                        <span>City</span>
                        <input type="text" name="delivery_city" value="<?php echo html_escape($delivery['city'] ?? ''); ?>">
                    </label>
                </div>
                <label>
                    <span>Country</span>
                    <input type="text" name="delivery_country" value="<?php echo html_escape($delivery['country'] ?? 'Germany'); ?>">
                </label>
                <div class="checkout-modal-actions">
                    <button type="button" class="light-button" data-modal-close>Cancel</button>
                    <button type="submit" class="dark-button">Save Address</button>
                </div>
            </form>
        </div>
    </div>

    <div class="checkout-modal" id="payment-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog checkout-modal-dialog-small">
            <div class="checkout-modal-head">
                <h2>Card Details</h2>
                <button type="button" data-modal-close>&times;</button>
            </div>

            <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-modal-form">
                <input type="hidden" name="form_action" value="payment">
                <label>
                    <span>Cardholder Name</span>
                    <input type="text" name="cardholder_name" value="<?php echo html_escape($payment['cardholder_name'] ?? ''); ?>" placeholder="Max Mustermann">
                </label>
                <label>
                    <span>Card Number</span>
                    <input type="text" name="card_number" value="" placeholder="0000 0000 0000 0000">
                </label>
                <div class="checkout-field-grid">
                    <label>
                        <span>Expiry Month</span>
                        <input type="text" name="expiry_month" value="<?php echo html_escape($payment['expiry_month'] ?? ''); ?>" placeholder="09">
                    </label>
                    <label>
                        <span>Expiry Year</span>
                        <input type="text" name="expiry_year" value="<?php echo html_escape($payment['expiry_year'] ?? ''); ?>" placeholder="27">
                    </label>
                </div>
                <label class="checkout-checkbox-row checkout-checkbox-row-tight">
                    <input type="checkbox" checked disabled>
                    <span>Save card for future bookings</span>
                </label>
                <div class="checkout-modal-actions">
                    <button type="button" class="light-button" data-modal-close>Cancel</button>
                    <button type="submit" class="dark-button">Save Card</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalButtons = document.querySelectorAll('[data-modal-open]');
        var closeButtons = document.querySelectorAll('[data-modal-close]');
        var sameAsBilling = document.querySelector('[data-same-as-billing]');
        var deliveryPanel = document.querySelector('[data-delivery-panel]');
        var deliveryToggleForm = document.getElementById('delivery-toggle-form');

        modalButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = document.getElementById(button.getAttribute('data-modal-open'));
                if (modal) {
                    modal.hidden = false;
                    document.body.classList.add('checkout-modal-open');
                }
            });
        });

        closeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = button.closest('.checkout-modal');
                if (modal) {
                    modal.hidden = true;
                }
                document.body.classList.remove('checkout-modal-open');
            });
        });

        if (sameAsBilling && deliveryPanel && deliveryToggleForm) {
            sameAsBilling.addEventListener('change', function () {
                deliveryPanel.classList.toggle('is-hidden', sameAsBilling.checked);
                deliveryToggleForm.submit();
            });
        }
    });
    </script>
</main>
