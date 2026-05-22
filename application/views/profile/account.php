<main>
    <section class="page-hero profile-hero">
        <div class="content-shell split-shell">
            <div>
                <span class="section-tag">My Account</span>
                <h1>Personal Profile</h1>
                <p class="page-lead">Manage your account details, company info, and recent booking activity.</p>
            </div>
            <div class="hero-side-note">
                <strong>Signed in as</strong>
                <p><?php echo html_escape($auth_user['email']); ?></p>
            </div>
        </div>
    </section>

    <section class="profile-section">
        <div class="content-shell profile-shell">
            <?php $this->load->view('profile/sidebar'); ?>

            <div class="profile-main">
                <div class="table-card">
                    <div class="table-card-head">
                        <h2>Personal Profile</h2>
                    </div>

                    <?php echo validation_errors('<div class="form-error">', '</div>'); ?>

                    <?php echo form_open(site_url('profile/update'), array('class' => 'auth-form auth-form-two-col profile-form')); ?>
                        <label class="form-row">
                            <span>First Name</span>
                            <input type="text" name="first_name" value="<?php echo set_value('first_name', $auth_user['first_name']); ?>">
                        </label>
                        <label class="form-row">
                            <span>Last Name</span>
                            <input type="text" name="last_name" value="<?php echo set_value('last_name', $auth_user['last_name']); ?>">
                        </label>
                        <label class="form-row">
                            <span>Email</span>
                            <input type="email" name="email" value="<?php echo set_value('email', $auth_user['email']); ?>">
                        </label>
                        <label class="form-row">
                            <span>Phone</span>
                            <input type="text" name="phone" value="<?php echo set_value('phone', $auth_user['phone']); ?>">
                        </label>
                        <label class="form-row">
                            <span>Company Name</span>
                            <input type="text" name="company_name" value="<?php echo set_value('company_name', $auth_user['company_name']); ?>">
                        </label>
                        <label class="form-row">
                            <span>Website</span>
                            <input type="text" name="website" value="<?php echo set_value('website', $auth_user['website']); ?>">
                        </label>
                        <label class="form-row form-row-full">
                            <span>VAT Number</span>
                            <input type="text" name="vat_number" value="<?php echo set_value('vat_number', $auth_user['vat_number']); ?>">
                        </label>
                        <button type="submit" class="header-button">Save Changes</button>
                    <?php echo form_close(); ?>
                </div>

                <div class="table-card">
                    <div class="table-card-head">
                        <h2>Recent Bookings</h2>
                        <a class="text-button" href="<?php echo site_url('booking'); ?>">Create another booking</a>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Campaign</th>
                                <th>Status</th>
                                <th>Start</th>
                                <th>Term</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?php echo html_escape($booking['reference']); ?></td>
                                    <td><?php echo html_escape($booking['campaign']); ?></td>
                                    <td><span class="status-chip"><?php echo html_escape($booking['status']); ?></span></td>
                                    <td><?php echo html_escape($booking['start_date']); ?></td>
                                    <td><?php echo html_escape($booking['term']); ?></td>
                                    <td><?php echo html_escape($booking['amount']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
