<section class="admin-auth-section">
    <div class="admin-auth-shell">
        <div class="admin-auth-panel">
            <img class="admin-auth-logo" src="<?php echo base_url('assets/Images/logo.png'); ?>" alt="KinoBlick">
            <h1>Admin Panel Access</h1>
            <p>Manage bookings, payments, and uploaded campaign assets.</p>

            <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

            <?php echo form_open(site_url('admin/login'), array('class' => 'admin-auth-form')); ?>
                <label class="admin-auth-row">
                    <span>Email Address</span>
                    <input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Enter your admin email">
                </label>
                <label class="admin-auth-row">
                    <span>Password</span>
                    <input type="password" name="password" placeholder="Enter your password">
                </label>
                <button type="submit" class="admin-auth-submit">Sign In</button>
            <?php echo form_close(); ?>

            <a class="admin-auth-link" href="<?php echo site_url('admin/forgot-password'); ?>">Forgot Password?</a>
        </div>

        <div class="admin-auth-visual admin-auth-visual-login"></div>
    </div>
</section>
