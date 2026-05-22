<section class="admin-auth-section">
    <div class="admin-auth-shell">
        <div class="admin-auth-panel">
            <img class="admin-auth-logo" src="<?php echo base_url('assets/Images/logo.png'); ?>" alt="KinoBlick">
            <h1>Forgot Password</h1>
            <p>Enter your registered email address to receive a verification link or code.</p>

            <?php echo form_open(site_url('admin/forgot-password'), array('class' => 'admin-auth-form')); ?>
                <label class="admin-auth-row">
                    <span>Email Address</span>
                    <input type="email" name="email" placeholder="Enter your email">
                </label>
                <button type="submit" class="admin-auth-submit">Send Verification Link</button>
            <?php echo form_close(); ?>

            <a class="admin-auth-back" href="<?php echo site_url('admin/login'); ?>">&larr; Back to Login</a>
        </div>

        <div class="admin-auth-visual admin-auth-visual-forgot"></div>
    </div>
</section>
