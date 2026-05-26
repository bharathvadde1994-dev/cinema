<main class="auth-v2-page">
    <section class="auth-v2-section">
        <div class="content-shell auth-v2-shell">
            <div class="auth-v2-panel">
                <div class="auth-v2-brand">
                    <strong>KINO<span>BLICK</span></strong>
                    <small>BOOKING MADE EASY</small>
                </div>

                <h1>Welcome Back</h1>
                <p class="auth-v2-switch-copy">Don't have an account? <a href="<?php echo site_url('auth/signup'); ?>">Sign Up</a></p>
                <p class="auth-v2-switch-copy">Admin team? <a href="<?php echo site_url('admin/login'); ?>">Use Admin Login</a></p>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <a class="auth-google-button" href="<?php echo site_url('auth/google?mode=login'); ?>">
                    <span>G</span>
                    Continue with Google
                </a>

                <div class="auth-v2-divider"><span>OR</span></div>

                <?php echo form_open(site_url('auth/login'), array('class' => 'auth-v2-form')); ?>
                    <label class="auth-v2-row">
                        <span>Work Email</span>
                        <input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Enter your work email">
                    </label>
                    <label class="auth-v2-row">
                        <span>Password</span>
                        <input type="password" name="password" placeholder="Enter your password">
                    </label>
                    <button type="submit" class="auth-v2-submit">Sign In with Email</button>
                <?php echo form_close(); ?>
                <p class="auth-v2-switch-copy"><a href="<?php echo site_url('auth/forgot_password'); ?>">Forgot your password?</a></p>

                <p class="auth-v2-terms">By signing in, you agree to our Terms &amp; Privacy Policy.</p>
            </div>

            <div class="auth-v2-visual auth-v2-visual-login"></div>
        </div>
    </section>
</main>
