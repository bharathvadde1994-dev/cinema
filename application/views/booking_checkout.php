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
                <span class="is-complete">Choose Booking</span>
                <span class="is-active">Enter Info</span>
                <span>Pay</span>
            </div>

            <div class="checkout-shell">
                <div class="checkout-main">
                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Billing Address</h2>
                            <button type="button" class="checkout-inline-link" data-modal-open="billing-modal">
                                <?php echo !empty($billing) ? 'Edit' : '+ Add Address'; ?>
                            </button>
                        </div>

                        <?php if (!empty($billing['first_name']) || !empty($billing['company'])): ?>
                            <div class="checkout-address-block">
                                <?php if (!empty($billing['company'])): ?><strong><?php echo html_escape($billing['company']); ?></strong><?php endif; ?>
                                <span><?php echo html_escape(trim(($billing['salutation'] ?? '') . ' ' . ($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? ''))); ?></span>
                                <span><?php echo html_escape($billing['street'] ?? ''); ?></span>
                                <?php if (!empty($billing['additional'])): ?><span><?php echo html_escape($billing['additional']); ?></span><?php endif; ?>
                                <span><?php echo html_escape(trim(($billing['postcode'] ?? '') . ' ' . ($billing['city'] ?? ''))); ?></span>
                                <span><?php echo html_escape($billing['country'] ?? ''); ?></span>
                                <?php if (!empty($billing['phone'])): ?><span><?php echo html_escape($billing['phone']); ?></span><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p class="checkout-muted">No billing address added.</p>
                        <?php endif; ?>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Add Coupon <span>(optional)</span></h2>
                        </div>
                        <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-inline-form">
                            <input type="hidden" name="form_action" value="coupon">
                            <input type="text" name="coupon_code" value="<?php echo html_escape($checkout_draft['coupon_code'] ?? ''); ?>" placeholder="Enter Coupon Code">
                            <button type="submit">Apply Coupon</button>
                        </form>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Delivery Address</h2>
                        </div>

                        <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-delivery-form">
                            <input type="hidden" name="form_action" value="delivery">
                            <label class="checkout-checkbox-row">
                                <input type="checkbox" name="same_as_billing" value="1" <?php echo $same_as_billing ? 'checked' : ''; ?> data-same-as-billing>
                                <span>Same as billing address</span>
                            </label>

                            <div class="checkout-delivery-fields<?php echo $same_as_billing ? ' is-hidden' : ''; ?>" data-delivery-fields>
                                <input type="text" name="delivery_street" value="<?php echo html_escape($delivery['street'] ?? ''); ?>" placeholder="Street and house number">
                                <input type="text" name="delivery_additional" value="<?php echo html_escape($delivery['additional'] ?? ''); ?>" placeholder="Additional address line">
                                <div class="checkout-field-grid">
                                    <input type="text" name="delivery_postcode" value="<?php echo html_escape($delivery['postcode'] ?? ''); ?>" placeholder="Postcode">
                                    <input type="text" name="delivery_city" value="<?php echo html_escape($delivery['city'] ?? ''); ?>" placeholder="City">
                                </div>
                                <input type="text" name="delivery_country" value="<?php echo html_escape($delivery['country'] ?? 'Germany'); ?>" placeholder="Country">
                            </div>

                            <div class="checkout-delivery-actions">
                                <button type="submit">Save Delivery Address</button>
                            </div>
                        </form>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Payment Method</h2>
                        </div>

                        <button type="button" class="checkout-payment-option is-active" data-modal-open="payment-modal">
                            <span class="checkout-radio is-active"></span>
                            <span class="checkout-payment-copy">
                                <strong>Card Payment</strong>
                                <small>Secure Stripe card checkout.</small>
                            </span>
                            <span class="checkout-payment-badge">Stripe</span>
                        </button>

                        <?php if (!empty($payment['last4'])): ?>
                            <p class="checkout-payment-saved">Saved card ending in <?php echo html_escape($payment['last4']); ?> via <?php echo html_escape($payment['provider']); ?>.</p>
                        <?php endif; ?>
                    </section>
                </div>

                <aside class="checkout-summary-card">
                    <div class="checkout-summary-note">
                        The price includes professional ad video production. If you don’t have a video, we will create one for you at no extra cost.
                    </div>

                    <div class="checkout-summary-section">
                        <h2>Booking Summary</h2>
                        <dl class="checkout-summary-list">
                            <div><dt>Cinema</dt><dd><?php echo html_escape($checkout_draft['cinema_name']); ?></dd></div>
                            <div><dt>Location</dt><dd><?php echo html_escape($checkout_draft['location_label']); ?></dd></div>
                            <div><dt>Selected Halls</dt><dd><?php echo (int) $checkout_draft['hall_count_selected']; ?></dd></div>
                            <div><dt>Duration</dt><dd><?php echo html_escape($checkout_draft['duration_months']); ?> Months</dd></div>
                            <div><dt>Ad Length</dt><dd><?php echo html_escape($checkout_draft['spot_length']); ?> sec</dd></div>
                        </dl>
                    </div>

                    <div class="checkout-summary-section">
                        <h3>Price Breakdown</h3>
                        <dl class="checkout-summary-list checkout-summary-list-pricing">
                            <div><dt>Base Rate</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['base_rate'], 2); ?></dd></div>
                            <div><dt>Processing Fee</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['processing_fee'], 2); ?></dd></div>
                            <?php if ($checkout_summary['custom_start_fee'] > 0): ?>
                                <div><dt>Custom Start Fee</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['custom_start_fee'], 2); ?></dd></div>
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
                            <span>I agree to the Terms, Conditions and Privacy Policy of Kinoblick.</span>
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
        var deliveryFields = document.querySelector('[data-delivery-fields]');

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

        if (sameAsBilling && deliveryFields) {
            sameAsBilling.addEventListener('change', function () {
                deliveryFields.classList.toggle('is-hidden', sameAsBilling.checked);
            });
        }
    });
    </script>
</main>
