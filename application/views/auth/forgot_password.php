<main class="auth-v2-page">
    <section class="auth-v2-section">
        <div class="content-shell auth-v2-shell">
            <div class="auth-v2-panel">
                <div class="auth-v2-brand">
                    <strong>KINO<span>BLICK</span></strong>
                    <small>BOOKING MADE EASY</small>
                </div>

                <h1>Forgot Password</h1>
                <p class="auth-v2-switch-copy">Enter your email and we will send you a 6-digit reset code.</p>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <?php echo form_open(site_url('auth/forgot_password'), array('class' => 'auth-v2-form')); ?>
                    <label class="auth-v2-row">
                        <span>Work Email</span>
                        <input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Enter your work email">
                    </label>
                    <button type="submit" class="auth-v2-submit">Send Reset Code</button>
                <?php echo form_close(); ?>

                <p class="auth-v2-terms"><a href="<?php echo site_url('auth/login'); ?>">Back to login</a></p>
            </div>

            <div class="auth-v2-visual auth-v2-visual-login"></div>
        </div>
    </section>
</main>
