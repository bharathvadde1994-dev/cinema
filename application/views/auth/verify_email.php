<main class="auth-v2-page">
    <section class="auth-v2-section">
        <div class="content-shell auth-v2-shell">
            <div class="auth-v2-panel">
                <div class="auth-v2-brand">
                    <strong>KINO<span>BLICK</span></strong>
                    <small>BOOKING MADE EASY</small>
                </div>

                <h1>Verify Your Email</h1>
                <p class="auth-v2-switch-copy">Enter the 6-digit code sent to <strong><?php echo html_escape($verification_email); ?></strong>.</p>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <?php echo form_open(site_url('auth/verify_email'), array('class' => 'auth-v2-form')); ?>
                    <label class="auth-v2-row">
                        <span>Verification Code</span>
                        <input type="text" name="verification_code" value="<?php echo set_value('verification_code'); ?>" maxlength="6" inputmode="numeric" placeholder="Enter 6-digit code">
                    </label>
                    <button type="submit" class="auth-v2-submit">Verify Email</button>
                <?php echo form_close(); ?>

                <?php echo form_open(site_url('auth/verify_email'), array('class' => 'auth-v2-form')); ?>
                    <input type="hidden" name="verification_action" value="resend">
                    <button type="submit" class="light-button">Resend Code</button>
                <?php echo form_close(); ?>

                <p class="auth-v2-terms"><a href="<?php echo site_url('auth/login'); ?>">Back to login</a></p>
            </div>

            <div class="auth-v2-visual auth-v2-visual-details"></div>
        </div>
    </section>
</main>
