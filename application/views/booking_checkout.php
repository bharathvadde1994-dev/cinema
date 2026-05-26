<main class="checkout-page">
    <?php
    $billing = !empty($checkout_draft['billing_address']) ? $checkout_draft['billing_address'] : array();
    $delivery = !empty($checkout_draft['delivery_address']) ? $checkout_draft['delivery_address'] : array();
    $same_as_billing = isset($checkout_draft['same_as_billing']) ? (bool) $checkout_draft['same_as_billing'] : TRUE;
    $selected_plan = !empty($checkout_draft['payment_plan']) && $checkout_draft['payment_plan'] === 'monthly' ? 'monthly' : 'one_time';
    $one_time = $checkout_plan_options['one_time'];
    $monthly = $checkout_plan_options['monthly'];
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
                            <h2>Add Coupon <small>(Optional)</small></h2>
                        </div>

                        <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-inline-form">
                            <input type="hidden" name="form_action" value="coupon">
                            <input type="text" name="coupon_code" value="<?php echo html_escape($checkout_draft['coupon_code'] ?? ''); ?>" placeholder="Use SAVE10 or SAVE100">
                            <button type="submit" class="light-button">Apply Coupon</button>
                        </form>

                        <?php if (!empty($checkout_summary['coupon_code'])): ?>
                            <p class="checkout-muted">Applied coupon: <strong><?php echo html_escape($checkout_summary['coupon_code']); ?></strong> (<?php echo html_escape($checkout_summary['coupon_label']); ?>)</p>
                        <?php endif; ?>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Payment Plan</h2>
                        </div>

                        <div class="checkout-plan-inline">
                            <article class="checkout-plan-inline-card <?php echo $selected_plan === 'one_time' ? 'is-active' : ''; ?>">
                                <strong>One-Time Payment</strong>
                                <span>Pay the full campaign amount at once.</span>
                                <b><?php echo $checkout_summary['currency']; ?> <?php echo number_format($one_time['amount_paid_now'], 2); ?></b>
                            </article>
                            <article class="checkout-plan-inline-card <?php echo $selected_plan === 'monthly' ? 'is-active' : ''; ?>">
                                <strong>Monthly Payment</strong>
                                <span><?php echo $checkout_summary['currency']; ?> <?php echo number_format($monthly['monthly_amount'], 2); ?> / month for <?php echo (int) $monthly['months']; ?> months.</span>
                                <b><?php echo $checkout_summary['currency']; ?> <?php echo number_format($monthly['amount_paid_now'], 2); ?> today</b>
                            </article>
                        </div>

                        <p class="checkout-muted checkout-payment-skip-note">Payment collection is simulated for now. We still save the selected plan and payment snapshot in the database for future Stripe integration.</p>
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
                                <div><dt>Coupon Discount<?php if (!empty($checkout_summary['coupon_code'])): ?> (<?php echo html_escape($checkout_summary['coupon_code']); ?>)<?php endif; ?></dt><dd>-<?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['coupon_discount'], 2); ?></dd></div>
                            <?php endif; ?>
                            <div><dt>Subtotal</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['subtotal'], 2); ?></dd></div>
                            <div><dt>VAT (19%)</dt><dd><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['vat'], 2); ?></dd></div>
                        </dl>
                    </div>

                    <div class="checkout-summary-total">
                        <span>Total</span>
                        <strong><?php echo $checkout_summary['currency']; ?> <?php echo number_format($checkout_summary['grand_total'], 2); ?></strong>
                    </div>

                    <form action="<?php echo site_url('booking/place_order'); ?>" method="post" class="checkout-order-form" id="checkout-order-form">
                        <input type="hidden" name="payment_plan" value="<?php echo html_escape($selected_plan); ?>" id="checkout-payment-plan-input">
                        <button type="button" class="header-button full-button" id="checkout-open-plan-modal">Order Now</button>
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

    <div class="checkout-modal" id="plan-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog checkout-plan-dialog">
            <div class="checkout-modal-head">
                <div>
                    <h2>Choose Your Payment Plan</h2>
                    <p>Select how you would like to pay for your cinema advertising campaign.</p>
                </div>
                <button type="button" data-modal-close>&times;</button>
            </div>

            <div class="checkout-plan-switcher">
                <button type="button" class="checkout-plan-tab <?php echo $selected_plan === 'one_time' ? 'is-active' : ''; ?>" data-plan-tab="one_time">One-Time Payment</button>
                <button type="button" class="checkout-plan-tab <?php echo $selected_plan === 'monthly' ? 'is-active' : ''; ?>" data-plan-tab="monthly">Monthly Payment</button>
            </div>

            <div class="checkout-plan-grid">
                <article class="checkout-plan-card <?php echo $selected_plan === 'one_time' ? 'is-active' : ''; ?>" data-plan-card="one_time">
                    <div class="checkout-plan-card-top">
                        <strong>Pay in Full</strong>
                        <span class="checkout-plan-indicator"></span>
                    </div>
                    <ul class="checkout-plan-features">
                        <li>Lower overall cost</li>
                        <li>One invoice</li>
                        <li>Faster processing</li>
                    </ul>
                    <small>Total amount</small>
                    <h3><?php echo $checkout_summary['currency']; ?> <?php echo number_format($one_time['amount_paid_now'], 0); ?></h3>
                    <button type="button" class="header-button full-button" data-submit-plan="one_time">Make One-Time Payment</button>
                </article>

                <article class="checkout-plan-card <?php echo $selected_plan === 'monthly' ? 'is-active' : ''; ?>" data-plan-card="monthly">
                    <div class="checkout-plan-card-top">
                        <strong>Pay Monthly</strong>
                        <span class="checkout-plan-indicator"></span>
                    </div>
                    <ul class="checkout-plan-features">
                        <li>Spread cost over campaign duration</li>
                        <li>Easier budgeting</li>
                        <li>Flexible planning</li>
                    </ul>
                    <small>Monthly amount</small>
                    <h3><?php echo $checkout_summary['currency']; ?> <?php echo number_format($monthly['monthly_amount'], 0); ?></h3>
                    <p><?php echo (int) $monthly['months']; ?> months</p>
                    <button type="button" class="header-button full-button" data-submit-plan="monthly">Continue with Monthly Plan</button>
                </article>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalButtons = document.querySelectorAll('[data-modal-open]');
        var closeButtons = document.querySelectorAll('[data-modal-close]');
        var sameAsBilling = document.querySelector('[data-same-as-billing]');
        var deliveryPanel = document.querySelector('[data-delivery-panel]');
        var deliveryToggleForm = document.getElementById('delivery-toggle-form');
        var openPlanModal = document.getElementById('checkout-open-plan-modal');
        var planModal = document.getElementById('plan-modal');
        var orderForm = document.getElementById('checkout-order-form');
        var planInput = document.getElementById('checkout-payment-plan-input');
        var planTabs = document.querySelectorAll('[data-plan-tab]');
        var planCards = document.querySelectorAll('[data-plan-card]');
        var planSubmitButtons = document.querySelectorAll('[data-submit-plan]');

        function openModal(modal) {
            if (modal) {
                modal.hidden = false;
                document.body.classList.add('checkout-modal-open');
            }
        }

        function closeModal(modal) {
            if (modal) {
                modal.hidden = true;
            }
            document.body.classList.remove('checkout-modal-open');
        }

        function syncPlanState(plan) {
            planInput.value = plan;
            planTabs.forEach(function (tab) {
                tab.classList.toggle('is-active', tab.getAttribute('data-plan-tab') === plan);
            });
            planCards.forEach(function (card) {
                card.classList.toggle('is-active', card.getAttribute('data-plan-card') === plan);
            });
        }

        modalButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                openModal(document.getElementById(button.getAttribute('data-modal-open')));
            });
        });

        closeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                closeModal(button.closest('.checkout-modal'));
            });
        });

        if (sameAsBilling && deliveryPanel && deliveryToggleForm) {
            sameAsBilling.addEventListener('change', function () {
                deliveryPanel.classList.toggle('is-hidden', sameAsBilling.checked);
                deliveryToggleForm.submit();
            });
        }

        if (openPlanModal) {
            openPlanModal.addEventListener('click', function () {
                openModal(planModal);
            });
        }

        planTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                syncPlanState(tab.getAttribute('data-plan-tab'));
            });
        });

        planSubmitButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                syncPlanState(button.getAttribute('data-submit-plan'));
                if (orderForm) {
                    orderForm.submit();
                }
            });
        });

        syncPlanState('<?php echo $selected_plan; ?>');
    });
    </script>
</main>
