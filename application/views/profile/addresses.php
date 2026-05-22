<?php
$same = !empty($same_as_delivery);
$billing_name = trim($auth_user['first_name'] . ' ' . $auth_user['last_name']);
$billing_lines = array_filter(array(
    $billing_name,
    $auth_user['company_name'],
    trim($auth_user['billing_address'] . ($auth_user['billing_additional'] ? ', ' . $auth_user['billing_additional'] : '')),
    trim($auth_user['billing_city'] . ($auth_user['billing_state'] ? ', ' . $auth_user['billing_state'] : '') . ($auth_user['billing_postcode'] ? ' ' . $auth_user['billing_postcode'] : '')),
    $auth_user['billing_country'],
));
$delivery_lines = array_filter(array(
    $billing_name,
    $auth_user['company_name'],
    trim($auth_user['delivery_address'] . ($auth_user['delivery_additional'] ? ', ' . $auth_user['delivery_additional'] : '')),
    trim($auth_user['delivery_city'] . ($auth_user['delivery_state'] ? ', ' . $auth_user['delivery_state'] : '') . ($auth_user['delivery_postcode'] ? ' ' . $auth_user['delivery_postcode'] : '')),
    $auth_user['delivery_country'],
));
?>
<main class="account-page">
    <section class="account-section">
        <div class="content-shell account-shell">
            <?php $this->load->view('profile/sidebar'); ?>

            <section class="account-content-card">
                <div class="account-content-head">
                    <h1>Addresses</h1>
                    <p>Manage your billing and delivery addresses.</p>
                </div>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <label class="account-checkbox-row">
                    <input type="checkbox" <?php echo $same ? 'checked' : ''; ?> data-address-same>
                    <span>Billing address same as delivery address</span>
                </label>

                <div class="account-address-grid">
                    <article class="account-address-card">
                        <h2>Default Billing Address</h2>
                        <?php if (!empty($billing_lines)): ?>
                            <?php foreach ($billing_lines as $line): ?>
                                <span><?php echo html_escape($line); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span>No billing address saved yet.</span>
                        <?php endif; ?>
                    </article>

                    <article class="account-address-card">
                        <h2>Default Delivery Address</h2>
                        <?php if (!empty($delivery_lines)): ?>
                            <?php foreach ($delivery_lines as $line): ?>
                                <span><?php echo html_escape($line); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span>No delivery address saved yet.</span>
                        <?php endif; ?>
                    </article>
                </div>

                <button type="button" class="account-secondary-button" data-modal-open="profile-address-modal">+ Add New Address</button>
            </section>
        </div>
    </section>

    <div class="checkout-modal" id="profile-address-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog account-address-modal">
            <div class="checkout-modal-head">
                <div>
                    <h2>Add New Address</h2>
                    <p>Enter your address details below. This will replace your current default address.</p>
                </div>
                <button type="button" data-modal-close>&times;</button>
            </div>

            <?php echo form_open(site_url('profile/addresses'), array('class' => 'account-address-form', 'id' => 'profile-address-form')); ?>
                <label class="account-checkbox-row">
                    <input type="checkbox" name="same_as_delivery" value="1" <?php echo $same ? 'checked' : ''; ?> data-address-modal-same>
                    <span>Billing address same as delivery address</span>
                </label>

                <div class="account-form-grid">
                    <label class="account-field">
                        <span>Full Name</span>
                        <input type="text" name="billing_full_name" value="<?php echo set_value('billing_full_name', $billing_name); ?>">
                    </label>
                    <label class="account-field">
                        <span>Company</span>
                        <input type="text" name="billing_company" value="<?php echo set_value('billing_company', $auth_user['company_name']); ?>">
                    </label>
                    <label class="account-field account-field-full">
                        <span>Street Address</span>
                        <input type="text" name="billing_address" value="<?php echo set_value('billing_address', $auth_user['billing_address']); ?>">
                    </label>
                    <label class="account-field">
                        <span>Office Number</span>
                        <input type="text" name="billing_additional" value="<?php echo set_value('billing_additional', $auth_user['billing_additional']); ?>">
                    </label>
                    <label class="account-field">
                        <span>City</span>
                        <input type="text" name="billing_city" value="<?php echo set_value('billing_city', $auth_user['billing_city']); ?>">
                    </label>
                    <label class="account-field">
                        <span>State</span>
                        <input type="text" name="billing_state" value="<?php echo set_value('billing_state', $auth_user['billing_state']); ?>">
                    </label>
                    <label class="account-field">
                        <span>Postal Code</span>
                        <input type="text" name="billing_postcode" value="<?php echo set_value('billing_postcode', $auth_user['billing_postcode']); ?>">
                    </label>
                    <label class="account-field account-field-full">
                        <span>Country</span>
                        <input type="text" name="billing_country" value="<?php echo set_value('billing_country', $auth_user['billing_country'] ? $auth_user['billing_country'] : 'Germany'); ?>">
                    </label>
                </div>

                <div class="account-delivery-fields<?php echo $same ? ' is-hidden' : ''; ?>" data-address-delivery-fields>
                    <div class="account-form-grid">
                        <label class="account-field account-field-full">
                            <span>Delivery Street Address</span>
                            <input type="text" name="delivery_address" value="<?php echo set_value('delivery_address', $auth_user['delivery_address']); ?>">
                        </label>
                        <label class="account-field">
                            <span>Delivery Office Number</span>
                            <input type="text" name="delivery_additional" value="<?php echo set_value('delivery_additional', $auth_user['delivery_additional']); ?>">
                        </label>
                        <label class="account-field">
                            <span>Delivery City</span>
                            <input type="text" name="delivery_city" value="<?php echo set_value('delivery_city', $auth_user['delivery_city']); ?>">
                        </label>
                        <label class="account-field">
                            <span>Delivery State</span>
                            <input type="text" name="delivery_state" value="<?php echo set_value('delivery_state', $auth_user['delivery_state']); ?>">
                        </label>
                        <label class="account-field">
                            <span>Delivery Postal Code</span>
                            <input type="text" name="delivery_postcode" value="<?php echo set_value('delivery_postcode', $auth_user['delivery_postcode']); ?>">
                        </label>
                        <label class="account-field account-field-full">
                            <span>Delivery Country</span>
                            <input type="text" name="delivery_country" value="<?php echo set_value('delivery_country', $auth_user['delivery_country'] ? $auth_user['delivery_country'] : 'Germany'); ?>">
                        </label>
                    </div>
                </div>

                <div class="checkout-modal-actions">
                    <button type="button" class="light-button" data-modal-close>Cancel</button>
                    <button type="submit" class="account-primary-button">Save Address</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var openButton = document.querySelector('[data-modal-open="profile-address-modal"]');
        var closeButtons = document.querySelectorAll('#profile-address-modal [data-modal-close]');
        var modal = document.getElementById('profile-address-modal');
        var sameToggle = document.querySelector('[data-address-modal-same]');
        var displayToggle = document.querySelector('[data-address-same]');
        var deliveryFields = document.querySelector('[data-address-delivery-fields]');

        function syncDeliveryVisibility(checked) {
            if (deliveryFields) {
                deliveryFields.classList.toggle('is-hidden', checked);
            }
            if (displayToggle) {
                displayToggle.checked = checked;
            }
        }

        if (openButton && modal) {
            openButton.addEventListener('click', function () {
                modal.hidden = false;
                document.body.classList.add('checkout-modal-open');
            });
        }

        closeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                modal.hidden = true;
                document.body.classList.remove('checkout-modal-open');
            });
        });

        if (sameToggle) {
            sameToggle.addEventListener('change', function () {
                syncDeliveryVisibility(sameToggle.checked);
            });
            syncDeliveryVisibility(sameToggle.checked);
        }

        if (displayToggle) {
            displayToggle.addEventListener('change', function () {
                if (sameToggle) {
                    sameToggle.checked = displayToggle.checked;
                    syncDeliveryVisibility(sameToggle.checked);
                }
            });
        }
    });
    </script>
</main>
