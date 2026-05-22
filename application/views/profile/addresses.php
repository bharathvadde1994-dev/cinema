<main>
    <section class="page-hero profile-hero">
        <div class="content-shell split-shell">
            <div>
                <span class="section-tag">My Account</span>
                <h1>Addresses</h1>
                <p class="page-lead">Maintain billing and delivery address information for invoices and campaign handling.</p>
            </div>
            <div class="hero-side-note">
                <strong>Company</strong>
                <p><?php echo html_escape($auth_user['company_name']); ?></p>
            </div>
        </div>
    </section>

    <section class="profile-section">
        <div class="content-shell profile-shell">
            <?php $this->load->view('profile/sidebar'); ?>

            <div class="profile-main">
                <div class="table-card">
                    <div class="table-card-head">
                        <h2>Address Book</h2>
                    </div>

                    <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                    <?php echo form_open(site_url('profile/addresses'), array('class' => 'address-grid')); ?>
                        <div class="address-card">
                            <h3>Billing Address</h3>
                            <label class="form-row">
                                <span>Address</span>
                                <input type="text" name="billing_address" value="<?php echo set_value('billing_address', $auth_user['billing_address']); ?>">
                            </label>
                            <label class="form-row">
                                <span>City</span>
                                <input type="text" name="billing_city" value="<?php echo set_value('billing_city', $auth_user['billing_city']); ?>">
                            </label>
                            <label class="form-row">
                                <span>Country</span>
                                <input type="text" name="billing_country" value="<?php echo set_value('billing_country', $auth_user['billing_country']); ?>">
                            </label>
                        </div>

                        <div class="address-card">
                            <h3>Delivery Address</h3>
                            <label class="form-row">
                                <span>Address</span>
                                <input type="text" name="delivery_address" value="<?php echo set_value('delivery_address', $auth_user['delivery_address']); ?>">
                            </label>
                            <label class="form-row">
                                <span>City</span>
                                <input type="text" name="delivery_city" value="<?php echo set_value('delivery_city', $auth_user['delivery_city']); ?>">
                            </label>
                            <label class="form-row">
                                <span>Country</span>
                                <input type="text" name="delivery_country" value="<?php echo set_value('delivery_country', $auth_user['delivery_country']); ?>">
                            </label>
                        </div>

                        <button type="submit" class="header-button">Save Addresses</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </section>
</main>
