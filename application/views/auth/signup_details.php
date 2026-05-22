<main class="auth-v2-page">
    <section class="auth-v2-section">
        <div class="content-shell auth-v2-shell">
            <div class="auth-v2-panel">
                <div class="auth-v2-brand">
                    <strong>KINO<span>BLICK</span></strong>
                    <small>BOOKING MADE EASY</small>
                </div>

                <div class="auth-v2-progress">
                    <small>Step 2 of 2</small>
                    <div class="auth-v2-progress-bars">
                        <span class="is-complete"></span>
                        <span class="is-active"></span>
                    </div>
                </div>

                <h1>Tell Us About Yourself</h1>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <?php echo form_open(site_url('auth/signup/details'), array('class' => 'auth-v2-form')); ?>
                    <?php
                    $google_full_name = !empty($signup_google_profile['full_name']) ? $signup_google_profile['full_name'] : '';
                    $google_company_name = !empty($signup_google_profile['company_name']) ? $signup_google_profile['company_name'] : '';
                    ?>
                    <label class="auth-v2-row">
                        <span>Full Name</span>
                        <input type="text" name="full_name" value="<?php echo set_value('full_name', $google_full_name); ?>" placeholder="Enter your full name">
                    </label>
                    <label class="auth-v2-row">
                        <span>Salutation</span>
                        <select name="salutation">
                            <?php $salutation = set_value('salutation', 'Mr'); ?>
                            <option value="Mr" <?php echo $salutation === 'Mr' ? 'selected' : ''; ?>>Mr.</option>
                            <option value="Ms" <?php echo $salutation === 'Ms' ? 'selected' : ''; ?>>Ms.</option>
                            <option value="Mrs" <?php echo $salutation === 'Mrs' ? 'selected' : ''; ?>>Mrs.</option>
                            <option value="Dr" <?php echo $salutation === 'Dr' ? 'selected' : ''; ?>>Dr.</option>
                        </select>
                    </label>
                    <label class="auth-v2-row">
                        <span>Company Name</span>
                        <input type="text" name="company_name" value="<?php echo set_value('company_name', $google_company_name); ?>" placeholder="Enter your company name">
                    </label>
                    <label class="auth-v2-row">
                        <span>VAT ID</span>
                        <input type="text" name="vat_number" value="<?php echo set_value('vat_number'); ?>" placeholder="Enter your VAT ID (optional)">
                    </label>
                    <label class="auth-v2-row">
                        <span>I Am</span>
                        <?php $business_type = set_value('business_type'); ?>
                        <select name="business_type">
                            <option value="">Select business type</option>
                            <option value="Cafe" <?php echo $business_type === 'Cafe' ? 'selected' : ''; ?>>Cafe</option>
                            <option value="Hairdresser" <?php echo $business_type === 'Hairdresser' ? 'selected' : ''; ?>>Hairdresser</option>
                            <option value="Real Estate" <?php echo $business_type === 'Real Estate' ? 'selected' : ''; ?>>Real Estate</option>
                            <option value="Restaurant" <?php echo $business_type === 'Restaurant' ? 'selected' : ''; ?>>Restaurant</option>
                            <option value="Insurance" <?php echo $business_type === 'Insurance' ? 'selected' : ''; ?>>Insurance</option>
                            <option value="Local Business" <?php echo $business_type === 'Local Business' ? 'selected' : ''; ?>>Local Business</option>
                        </select>
                    </label>
                    <label class="auth-v2-row auth-v2-row-readonly">
                        <span>Account Email</span>
                        <input type="text" value="<?php echo html_escape($signup_email); ?>" readonly>
                    </label>
                    <button type="submit" class="auth-v2-submit">Continue</button>
                <?php echo form_close(); ?>
            </div>

            <div class="auth-v2-visual auth-v2-visual-details"></div>
        </div>
    </section>
</main>
