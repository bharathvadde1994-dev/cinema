<main class="auth-page">
    <section class="auth-section">
        <div class="content-shell auth-shell auth-shell-signup">
            <div class="auth-card auth-form-card">
                <span class="section-tag">KinoBlick</span>
                <h1>Create Your Free Account</h1>
                <p class="auth-copy">Set up your advertiser profile and start booking cinema ad slots.</p>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <?php echo form_open(site_url('auth/signup'), array('class' => 'auth-form auth-form-two-col')); ?>
                    <label class="form-row">
                        <span>Company Name</span>
                        <input type="text" name="company_name" value="<?php echo set_value('company_name'); ?>" placeholder="Company name">
                    </label>
                    <label class="form-row">
                        <span>First Name</span>
                        <input type="text" name="first_name" value="<?php echo set_value('first_name'); ?>" placeholder="First name">
                    </label>
                    <label class="form-row">
                        <span>Last Name</span>
                        <input type="text" name="last_name" value="<?php echo set_value('last_name'); ?>" placeholder="Last name">
                    </label>
                    <label class="form-row">
                        <span>Email</span>
                        <input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Business email">
                    </label>
                    <label class="form-row">
                        <span>Phone</span>
                        <input type="text" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="Phone number">
                    </label>
                    <label class="form-row">
                        <span>Address</span>
                        <input type="text" name="billing_address" value="<?php echo set_value('billing_address'); ?>" placeholder="Billing address">
                    </label>
                    <label class="form-row">
                        <span>City</span>
                        <input type="text" name="billing_city" value="<?php echo set_value('billing_city'); ?>" placeholder="City">
                    </label>
                    <label class="form-row">
                        <span>Country</span>
                        <input type="text" name="billing_country" value="<?php echo set_value('billing_country', 'Germany'); ?>" placeholder="Country">
                    </label>
                    <label class="form-row">
                        <span>Password</span>
                        <input type="password" name="password" placeholder="Minimum 8 characters">
                    </label>
                    <label class="form-row">
                        <span>Confirm Password</span>
                        <input type="password" name="password_confirm" placeholder="Repeat password">
                    </label>
                    <button type="submit" class="header-button full-button auth-submit">Sign Up</button>
                <?php echo form_close(); ?>

                <div class="auth-footer-row">
                    <span>Already have an account?</span>
                    <a href="<?php echo site_url('auth/login'); ?>">Log in</a>
                </div>
            </div>

            <div class="auth-card auth-visual auth-visual-signup">
                <div class="auth-visual-copy">
                    <h2>Launch campaigns across multiple cinemas with one account.</h2>
                    <p>After payment, upload once and apply the same media set across the selected venues.</p>
                </div>
            </div>
        </div>
    </section>
</main>
