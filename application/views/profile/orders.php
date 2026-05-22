<main class="account-page">
    <section class="account-section">
        <div class="content-shell account-shell">
            <?php $this->load->view('profile/sidebar'); ?>

            <section class="account-content-card">
                <div class="account-content-head">
                    <h1>Orders</h1>
                    <p>Review your recent cinema bookings.</p>
                </div>

                <?php if (!empty($bookings)): ?>
                    <div class="account-orders-list">
                        <?php foreach ($bookings as $booking): ?>
                            <article class="account-order-card">
                                <div>
                                    <strong><?php echo html_escape($booking['reference']); ?></strong>
                                    <p><?php echo html_escape($booking['campaign']); ?></p>
                                </div>
                                <div>
                                    <span class="account-order-label">Status</span>
                                    <strong><?php echo html_escape($booking['status']); ?></strong>
                                </div>
                                <div>
                                    <span class="account-order-label">Start</span>
                                    <strong><?php echo html_escape($booking['start_date']); ?></strong>
                                </div>
                                <div>
                                    <span class="account-order-label">Term</span>
                                    <strong><?php echo html_escape($booking['term']); ?></strong>
                                </div>
                                <div>
                                    <span class="account-order-label">Amount</span>
                                    <strong><?php echo html_escape($booking['amount']); ?></strong>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="account-empty-state">
                        <h2>No orders yet</h2>
                        <p>Your confirmed bookings will appear here once you place an order.</p>
                        <a class="account-primary-button account-inline-link" href="<?php echo site_url('cinemas'); ?>">Browse Cinemas</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </section>
</main>
