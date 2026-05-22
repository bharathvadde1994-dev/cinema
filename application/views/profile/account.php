<main class="account-page">
    <section class="account-section">
        <div class="content-shell account-shell">
            <?php $this->load->view('profile/sidebar'); ?>

            <section class="account-content-card">
                <div class="account-content-head">
                    <h1>Personal Profile</h1>
                    <p>Manage your personal and business information.</p>
                </div>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <?php echo form_open(site_url('profile/update'), array('class' => 'account-form-grid')); ?>
                    <label class="account-field account-field-full">
                        <span>Full Name</span>
                        <input type="text" name="full_name" value="<?php echo set_value('full_name', trim($auth_user['first_name'] . ' ' . $auth_user['last_name'])); ?>">
                    </label>
                    <label class="account-field">
                        <span>Salutation</span>
                        <?php $salutation = set_value('salutation', $auth_user['salutation'] ? $auth_user['salutation'] : 'Mr'); ?>
                        <select name="salutation">
                            <option value="Mr" <?php echo $salutation === 'Mr' ? 'selected' : ''; ?>>Mr.</option>
                            <option value="Ms" <?php echo $salutation === 'Ms' ? 'selected' : ''; ?>>Ms.</option>
                            <option value="Mrs" <?php echo $salutation === 'Mrs' ? 'selected' : ''; ?>>Mrs.</option>
                            <option value="Dr" <?php echo $salutation === 'Dr' ? 'selected' : ''; ?>>Dr.</option>
                        </select>
                    </label>
                    <label class="account-field">
                        <span>Company Name</span>
                        <input type="text" name="company_name" value="<?php echo set_value('company_name', $auth_user['company_name']); ?>">
                    </label>
                    <label class="account-field">
                        <span>VAT ID</span>
                        <input type="text" name="vat_number" value="<?php echo set_value('vat_number', $auth_user['vat_number']); ?>">
                    </label>
                    <label class="account-field">
                        <span>Email Address</span>
                        <input type="email" name="email" value="<?php echo set_value('email', $auth_user['email']); ?>">
                    </label>
                    <label class="account-field">
                        <span>Phone Number</span>
                        <input type="text" name="phone" value="<?php echo set_value('phone', $auth_user['phone']); ?>">
                    </label>
                    <input type="hidden" name="website" value="<?php echo html_escape($auth_user['website']); ?>">
                    <div class="account-actions">
                        <button type="submit" class="account-primary-button">Save Changes</button>
                    </div>
                <?php echo form_close(); ?>
            </section>
        </div>
    </section>
</main>
