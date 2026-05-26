<main class="auth-v2-page">
    <section class="auth-v2-section">
        <div class="content-shell auth-v2-shell">
            <div class="auth-v2-panel">
                <div class="auth-v2-brand">
                    <strong>KINO<span>BLICK</span></strong>
                    <small>BOOKING MADE EASY</small>
                </div>

                <h1>Create Your Free Account</h1>
                <p class="auth-v2-switch-copy">Already have an account? <a href="<?php echo site_url('auth/login'); ?>">Sign In</a></p>
                <p class="auth-v2-switch-copy">Admin team? <a href="<?php echo site_url('admin/login'); ?>">Use Admin Login</a></p>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <a class="auth-google-button" href="<?php echo site_url('auth/google?mode=signup'); ?>">
                    <span>G</span>
                    Continue with Google
                </a>

                <div class="auth-v2-divider"><span>OR</span></div>

                <?php echo form_open(site_url('auth/signup'), array('class' => 'auth-v2-form')); ?>
                    <input type="hidden" name="signup_stage" value="account">
                    <label class="auth-v2-row">
                        <span>Work Email</span>
                        <input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Enter your work email">
                    </label>
                    <label class="auth-v2-row">
                        <span>Password</span>
                        <input type="password" name="password" placeholder="Create a password">
                    </label>
                    <button type="submit" class="auth-v2-submit">Sign Up with Email</button>
                <?php echo form_close(); ?>

                <p class="auth-v2-terms">We will send a 6-digit verification code to your email before the account becomes active.</p>
            </div>

            <div class="auth-v2-visual auth-v2-visual-signup"></div>
        </div>
    </section>
</main>
