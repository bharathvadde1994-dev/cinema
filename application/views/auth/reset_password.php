<main class="auth-v2-page">
    <section class="auth-v2-section">
        <div class="content-shell auth-v2-shell">
            <div class="auth-v2-panel">
                <div class="auth-v2-brand">
                    <strong>KINO<span>BLICK</span></strong>
                    <small>BOOKING MADE EASY</small>
                </div>

                <h1>Reset Password</h1>
                <p class="auth-v2-switch-copy">Enter the reset code sent to <strong><?php echo html_escape($reset_email); ?></strong> and choose a new password.</p>

                <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                <?php echo form_open(site_url('auth/reset_password'), array('class' => 'auth-v2-form')); ?>
                    <label class="auth-v2-row">
                        <span>Reset Code</span>
                        <input type="text" name="reset_code" value="<?php echo set_value('reset_code'); ?>" maxlength="6" inputmode="numeric" placeholder="Enter 6-digit code">
                    </label>
                    <label class="auth-v2-row">
                        <span>New Password</span>
                        <input type="password" name="password" placeholder="Create a new password">
                    </label>
                    <label class="auth-v2-row">
                        <span>Confirm Password</span>
                        <input type="password" name="password_confirm" placeholder="Repeat the new password">
                    </label>
                    <button type="submit" class="auth-v2-submit">Update Password</button>
                <?php echo form_close(); ?>

                <?php echo form_open(site_url('auth/reset_password'), array('class' => 'auth-v2-form')); ?>
                    <input type="hidden" name="reset_action" value="resend">
                    <button type="submit" class="light-button">Resend Code</button>
                <?php echo form_close(); ?>

                <p class="auth-v2-terms"><a href="<?php echo site_url('auth/login'); ?>">Back to login</a></p>
            </div>

            <div class="auth-v2-visual auth-v2-visual-login"></div>
        </div>
    </section>
</main>
