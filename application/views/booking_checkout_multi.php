<main class="checkout-page checkout-page-multi">
    <?php
    $billing = !empty($checkout_draft['billing_address']) ? $checkout_draft['billing_address'] : array();
    $same_as_billing = isset($checkout_draft['same_as_billing']) ? (bool) $checkout_draft['same_as_billing'] : TRUE;
    $selected_plan = !empty($checkout_draft['payment_plan']) && $checkout_draft['payment_plan'] === 'monthly' ? 'monthly' : 'one_time';
    $one_time = $checkout_plan_options['one_time'];
    $monthly = $checkout_plan_options['monthly'];
    ?>
    <section class="checkout-section">
        <div class="content-shell">
            <div class="checkout-progress">
                <div class="checkout-progress-step is-complete"><span class="checkout-progress-dot">1</span><span class="checkout-progress-label">Choose Booking</span></div>
                <span class="checkout-progress-line is-complete"></span>
                <div class="checkout-progress-step is-active"><span class="checkout-progress-dot">2</span><span class="checkout-progress-label">Enter Info</span></div>
                <span class="checkout-progress-line"></span>
                <div class="checkout-progress-step"><span class="checkout-progress-dot">3</span><span class="checkout-progress-label">Pay</span></div>
            </div>

            <div class="checkout-shell">
                <div class="checkout-main">
                    <section class="checkout-card">
                        <h2>Cinemas Booked</h2>
                        <div class="checkout-multi-cinema-list">
                            <?php foreach ($cart_items as $index => $item): ?>
                                <div class="checkout-multi-cinema-row">
                                    <span class="checkout-multi-index"><?php echo $index + 1; ?></span>
                                    <strong><?php echo html_escape($item['name']); ?></strong>
                                    <span><?php echo html_escape($item['location_label']); ?></span>
                                    <span><?php echo (int) $item['hall_count_selected']; ?> halls</span>
                                    <span><?php echo (int) $item['duration_months']; ?> Months</span>
                                    <span><?php echo (int) $item['spot_length']; ?> sec</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Billing Address</h2>
                            <button type="button" class="checkout-inline-link" data-modal-open="billing-modal"><?php echo !empty($billing['first_name']) ? 'Edit' : '+ Add Address'; ?></button>
                        </div>
                        <?php if (!empty($billing['first_name'])): ?>
                            <div class="checkout-address-block">
                                <strong><?php echo html_escape(trim($billing['first_name'] . ' ' . $billing['last_name'])); ?></strong>
                                <span><?php echo html_escape($billing['street']); ?></span>
                                <span><?php echo html_escape(trim($billing['postcode'] . ' ' . $billing['city'])); ?></span>
                                <span><?php echo html_escape($billing['country']); ?></span>
                            </div>
                        <?php else: ?>
                            <p class="checkout-muted">No billing address added.</p>
                        <?php endif; ?>
                        <label class="checkout-checkbox-row checkout-checkbox-row-with-top">
                            <input type="checkbox" checked disabled>
                            <span>Delivery Address Same as billing address</span>
                        </label>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Add Coupon <small>(Optional)</small></h2>
                        </div>
                        <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-inline-form">
                            <input type="hidden" name="form_action" value="coupon">
                            <input type="text" name="coupon_code" value="<?php echo html_escape($checkout_draft['coupon_code'] ?? ''); ?>" placeholder="Enter Coupon Code">
                            <button type="submit" class="light-button">Apply Coupon</button>
                        </form>
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <h2>Payment Method</h2>
                        </div>
                        <div class="checkout-payment-list">
                            <div class="checkout-payment-option"><span class="checkout-radio"></span><span class="checkout-payment-copy"><strong>Invoice</strong></span></div>
                            <div class="checkout-payment-option is-active"><span class="checkout-radio is-active"></span><span class="checkout-payment-copy"><strong>Card Payment</strong><small><?php echo !empty($checkout_draft['payment_details']['last4']) ? '.... .... .... ' . html_escape($checkout_draft['payment_details']['last4']) : 'Saved for later gateway integration'; ?></small></span><button type="button" class="checkout-inline-link" data-modal-open="payment-modal">Edit</button></div>
                            <div class="checkout-payment-option"><span class="checkout-radio"></span><span class="checkout-payment-copy"><strong>PayPal</strong></span></div>
                            <div class="checkout-payment-option"><span class="checkout-radio"></span><span class="checkout-payment-copy"><strong>Klarna</strong></span></div>
                            <div class="checkout-payment-option"><span class="checkout-radio"></span><span class="checkout-payment-copy"><strong>Apple Pay</strong></span></div>
                        </div>
                    </section>
                </div>

                <aside class="checkout-summary-card">
                    <div class="checkout-summary-note">The price includes professional ad video production. If you don't have a video, we will create one using your media assets.</div>
                    <div class="checkout-summary-section">
                        <h2>Booking Summary</h2>
                        <dl class="checkout-summary-list">
                            <div><dt>Cinemas</dt><dd><?php echo (int) $checkout_summary['cinema_count']; ?></dd></div>
                            <div><dt>Total Halls</dt><dd><?php echo (int) $checkout_summary['total_halls']; ?></dd></div>
                            <div><dt>Duration</dt><dd><?php echo (int) $checkout_summary['max_duration_months']; ?> Months</dd></div>
                            <div><dt>Ad Length</dt><dd><?php echo html_escape($checkout_summary['spot_length_label']); ?></dd></div>
                            <div><dt>Plan</dt><dd><?php echo $selected_plan === 'monthly' ? 'Monthly' : 'One Time'; ?></dd></div>
                        </dl>
                    </div>
                    <div class="checkout-summary-section">
                        <h3>Price Breakdown</h3>
                        <dl class="checkout-summary-list checkout-summary-list-pricing">
                            <div><dt>Base Cost</dt><dd>&euro;<?php echo number_format((float) $checkout_summary['base_rate'], 2); ?></dd></div>
                            <div><dt>Processing Fee (5%)</dt><dd>&euro;<?php echo number_format((float) $checkout_summary['processing_fee'], 2); ?></dd></div>
                            <div><dt>FSK Exam Fee</dt><dd>&euro;<?php echo number_format((float) $checkout_summary['custom_start_fee'], 2); ?></dd></div>
                            <div><dt>Subtotal</dt><dd>&euro;<?php echo number_format((float) $checkout_summary['subtotal'], 2); ?></dd></div>
                            <div><dt>VAT (19%)</dt><dd>&euro;<?php echo number_format((float) $checkout_summary['vat'], 2); ?></dd></div>
                        </dl>
                    </div>
                    <div class="checkout-summary-total">
                        <span>Total</span>
                        <strong>&euro;<?php echo number_format((float) $checkout_summary['grand_total'], 2); ?></strong>
                    </div>
                    <form action="<?php echo site_url('booking/place_order'); ?>" method="post" class="checkout-order-form" id="multi-order-form">
                        <input type="hidden" name="payment_plan" value="<?php echo html_escape($selected_plan); ?>" id="checkout-payment-plan-input">
                        <button type="button" class="header-button full-button" id="checkout-open-plan-modal">Order Now</button>
                        <label class="checkout-terms-row"><input type="checkbox" name="accept_terms" value="1"><span>I agree to the Terms &amp; Conditions and Privacy Policy of Kinoblick.</span></label>
                    </form>
                </aside>
            </div>
        </div>
    </section>

    <div class="checkout-modal" id="billing-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog">
            <div class="checkout-modal-head"><h2>Billing Address</h2><button type="button" data-modal-close>&times;</button></div>
            <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-modal-form">
                <input type="hidden" name="form_action" value="billing">
                <div class="checkout-field-grid">
                    <label><span>Company</span><input type="text" name="company" value="<?php echo html_escape($billing['company'] ?? ''); ?>"></label>
                    <label><span>Salutation</span><input type="text" name="salutation" value="<?php echo html_escape($billing['salutation'] ?? 'Mr'); ?>"></label>
                </div>
                <div class="checkout-field-grid">
                    <label><span>First Name</span><input type="text" name="first_name" value="<?php echo html_escape($billing['first_name'] ?? ''); ?>"></label>
                    <label><span>Last Name</span><input type="text" name="last_name" value="<?php echo html_escape($billing['last_name'] ?? ''); ?>"></label>
                </div>
                <label><span>Street Address</span><input type="text" name="street" value="<?php echo html_escape($billing['street'] ?? ''); ?>"></label>
                <label><span>Additional Address Line</span><input type="text" name="additional" value="<?php echo html_escape($billing['additional'] ?? ''); ?>"></label>
                <div class="checkout-field-grid">
                    <label><span>Postcode</span><input type="text" name="postcode" value="<?php echo html_escape($billing['postcode'] ?? ''); ?>"></label>
                    <label><span>City</span><input type="text" name="city" value="<?php echo html_escape($billing['city'] ?? ''); ?>"></label>
                </div>
                <div class="checkout-field-grid">
                    <label><span>Country</span><input type="text" name="country" value="<?php echo html_escape($billing['country'] ?? 'Germany'); ?>"></label>
                    <label><span>Phone</span><input type="text" name="phone" value="<?php echo html_escape($billing['phone'] ?? ''); ?>"></label>
                </div>
                <div class="checkout-modal-actions"><button type="button" class="light-button" data-modal-close>Cancel</button><button type="submit" class="dark-button">Save Address</button></div>
            </form>
        </div>
    </div>

    <div class="checkout-modal" id="payment-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog checkout-modal-dialog-small">
            <div class="checkout-modal-head"><h2>Card Details</h2><button type="button" data-modal-close>&times;</button></div>
            <form action="<?php echo site_url('booking/checkout'); ?>" method="post" class="checkout-modal-form">
                <input type="hidden" name="form_action" value="payment">
                <label><span>Cardholder Name</span><input type="text" name="cardholder_name" value="<?php echo html_escape($checkout_draft['payment_details']['cardholder_name'] ?? ''); ?>"></label>
                <label><span>Card Number</span><input type="text" name="card_number" value="" placeholder="0000 0000 0000 0000"></label>
                <div class="checkout-field-grid">
                    <label><span>Expiry Month</span><input type="text" name="expiry_month" value="<?php echo html_escape($checkout_draft['payment_details']['expiry_month'] ?? ''); ?>" placeholder="09"></label>
                    <label><span>Expiry Year</span><input type="text" name="expiry_year" value="<?php echo html_escape($checkout_draft['payment_details']['expiry_year'] ?? ''); ?>" placeholder="27"></label>
                </div>
                <div class="checkout-modal-actions"><button type="button" class="light-button" data-modal-close>Cancel</button><button type="submit" class="dark-button">Save Card</button></div>
            </form>
        </div>
    </div>

    <div class="checkout-modal" id="plan-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog checkout-plan-dialog">
            <div class="checkout-modal-head"><div><h2>Choose Your Payment Plan</h2><p>Select how you would like to pay for your cinema advertising campaign.</p></div><button type="button" data-modal-close>&times;</button></div>
            <div class="checkout-plan-switcher">
                <button type="button" class="checkout-plan-tab <?php echo $selected_plan === 'one_time' ? 'is-active' : ''; ?>" data-plan-tab="one_time">One-Time Payment</button>
                <button type="button" class="checkout-plan-tab <?php echo $selected_plan === 'monthly' ? 'is-active' : ''; ?>" data-plan-tab="monthly">Monthly Payment</button>
            </div>
            <div class="checkout-plan-grid">
                <article class="checkout-plan-card <?php echo $selected_plan === 'one_time' ? 'is-active' : ''; ?>" data-plan-card="one_time">
                    <div class="checkout-plan-card-top"><strong>Pay in Full</strong><span class="checkout-plan-indicator"></span></div>
                    <ul class="checkout-plan-features"><li>Lower overall cost</li><li>One invoice</li><li>Faster processing</li></ul>
                    <small>Total amount</small>
                    <h3>&euro;<?php echo number_format((float) $one_time['amount_paid_now'], 0); ?></h3>
                    <button type="button" class="header-button full-button" data-submit-plan="one_time">Make One-Time Payment</button>
                </article>
                <article class="checkout-plan-card <?php echo $selected_plan === 'monthly' ? 'is-active' : ''; ?>" data-plan-card="monthly">
                    <div class="checkout-plan-card-top"><strong>Pay Monthly</strong><span class="checkout-plan-indicator"></span></div>
                    <ul class="checkout-plan-features"><li>Spread cost over campaign duration</li><li>Easier budgeting</li><li>Flexible planning</li></ul>
                    <small>Monthly amount</small>
                    <h3>&euro;<?php echo number_format((float) $monthly['monthly_amount'], 0); ?></h3>
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
        var planModal = document.getElementById('plan-modal');
        var openPlanModal = document.getElementById('checkout-open-plan-modal');
        var orderForm = document.getElementById('multi-order-form');
        var planInput = document.getElementById('checkout-payment-plan-input');
        var planTabs = document.querySelectorAll('[data-plan-tab]');
        var planCards = document.querySelectorAll('[data-plan-card]');
        var planSubmitButtons = document.querySelectorAll('[data-submit-plan]');

        function openModal(modal) { if (modal) { modal.hidden = false; document.body.classList.add('checkout-modal-open'); } }
        function closeModal(modal) { if (modal) { modal.hidden = true; } document.body.classList.remove('checkout-modal-open'); }
        function syncPlanState(plan) {
            planInput.value = plan;
            planTabs.forEach(function (tab) { tab.classList.toggle('is-active', tab.getAttribute('data-plan-tab') === plan); });
            planCards.forEach(function (card) { card.classList.toggle('is-active', card.getAttribute('data-plan-card') === plan); });
        }

        modalButtons.forEach(function (button) { button.addEventListener('click', function () { openModal(document.getElementById(button.getAttribute('data-modal-open'))); }); });
        closeButtons.forEach(function (button) { button.addEventListener('click', function () { closeModal(button.closest('.checkout-modal')); }); });
        if (openPlanModal) { openPlanModal.addEventListener('click', function () { openModal(planModal); }); }
        planTabs.forEach(function (tab) { tab.addEventListener('click', function () { syncPlanState(tab.getAttribute('data-plan-tab')); }); });
        planSubmitButtons.forEach(function (button) { button.addEventListener('click', function () { syncPlanState(button.getAttribute('data-submit-plan')); if (orderForm) { orderForm.submit(); } }); });
        syncPlanState('<?php echo $selected_plan; ?>');
    });
    </script>
</main>
