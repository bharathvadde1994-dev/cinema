<main class="auth-page">
    <section class="auth-section">
        <div class="content-shell auth-shell">
            <div class="auth-card auth-form-card">
                <span class="section-tag">KinoBlick</span>
                <h1>Log Into Your Account</h1>
                <p class="auth-copy">Access your bookings, uploads, invoices, and profile settings.</p>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <?php echo form_open(site_url('auth/login'), array('class' => 'auth-form')); ?>
                    <label class="form-row">
                        <span>Email</span>
                        <input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Enter your email">
                    </label>
                    <label class="form-row">
                        <span>Password</span>
                        <input type="password" name="password" placeholder="Enter your password">
                    </label>
                    <button type="submit" class="header-button full-button">Sign In</button>
                <?php echo form_close(); ?>

                <div class="auth-footer-row">
                    <span>New here?</span>
                    <a href="<?php echo site_url('auth/signup'); ?>">Create account</a>
                </div>
            </div>

            <div class="auth-card auth-visual auth-visual-login">
                <div class="auth-visual-copy">
                    <h2>Control cinema bookings from one clean dashboard.</h2>
                    <p>Track selected cinemas, payment status, and media uploads without leaving the platform.</p>
                </div>
            </div>
        </div>
    </section>
</main>
