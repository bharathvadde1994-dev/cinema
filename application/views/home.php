<main>
    <section class="hero-banner hero-banner-home">
        <div class="content-shell hero-shell">
            <div class="hero-copy">
                <span class="eyebrow">Cinema ad inventory marketplace</span>
                <h1>Sell cinema ad slots online across your client’s full network.</h1>
                <p>
                    Advertisers can browse available cinemas, choose a 3 or 6 month run,
                    set ad duration, see live pricing, pay online, upload media, and manage
                    every booking from one account.
                </p>
                <div class="hero-actions">
                    <a class="header-button" href="<?php echo site_url('booking'); ?>">Build a booking</a>
                    <a class="ghost-button" href="<?php echo site_url('cinemas'); ?>">Explore cinemas</a>
                </div>
            </div>
            <div class="hero-panel">
                <h2>Platform Scope</h2>
                <ul class="bullet-list">
                    <li>Map-based cinema discovery</li>
                    <li>Duration and ad-length pricing logic</li>
                    <li>Checkout, booking summary, and uploads</li>
                    <li>Customer and admin dashboards</li>
                </ul>
            </div>
        </div>
        <div class="seat-overlay"></div>
    </section>

    <section class="overview-section">
        <div class="content-shell">
            <div class="section-heading-row">
                <div>
                    <span class="section-tag">Business Model</span>
                    <h2>Main structure for the website</h2>
                </div>
                <p class="section-lead">
                    This scaffold is built around the actual commercial flow: inventory discovery,
                    booking, payment, upload handling, and admin operations.
                </p>
            </div>

            <div class="feature-grid">
                <article class="feature-card dark-card">
                    <h3>Advertiser journey</h3>
                    <p>Browse cinemas, choose slots, calculate pricing, pay, upload files, and review bookings.</p>
                </article>
                <article class="feature-card">
                    <h3>Operations journey</h3>
                    <p>Review every booking, payment status, upload status, and media assignment per cinema.</p>
                </article>
                <article class="feature-card">
                    <h3>Media workflow</h3>
                    <p>Upload once and apply the same trailer or artwork across multiple selected cinemas.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="content-shell stats-shell">
            <?php foreach ($stats as $stat): ?>
                <article class="stat-card">
                    <strong><?php echo $stat['value']; ?></strong>
                    <span><?php echo $stat['label']; ?></span>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="journey-section">
        <div class="content-shell split-shell">
            <div class="journey-copy">
                <span class="section-tag">Core Flow</span>
                <h2>What the platform needs to do</h2>
                <ol class="number-list">
                    <?php foreach ($steps as $step): ?>
                        <li><?php echo $step; ?></li>
                    <?php endforeach; ?>
                </ol>
                <a class="dark-button" href="<?php echo site_url('booking'); ?>">Open booking prototype</a>
            </div>

            <div class="journey-grid">
                <article class="mini-card">
                    <h3>Dynamic price summary</h3>
                    <p>Pricing changes by cinemas selected, campaign length, and ad duration.</p>
                </article>
                <article class="mini-card">
                    <h3>Upload after payment</h3>
                    <p>Files are attached after booking completion, with bulk-apply options.</p>
                </article>
                <article class="mini-card">
                    <h3>User profile area</h3>
                    <p>Advertisers can track summary, payment state, and upload progress.</p>
                </article>
                <article class="mini-card">
                    <h3>Admin control panel</h3>
                    <p>The client sees all bookings, statuses, and upload review requirements.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="map-preview-section">
        <div class="content-shell split-shell split-shell-wide">
            <div class="map-preview">
                <div class="map-surface">
                    <?php foreach ($cities as $city): ?>
                        <span class="map-pin"><?php echo $city; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="map-copy">
                <span class="section-tag">Map Integration</span>
                <h2>Show all cinema locations on one map</h2>
                <p>
                    This needs a real map integration next, but the structure is ready: users can
                    discover inventory by city, then open the cinema catalogue or booking flow.
                </p>
                <div class="button-row">
                    <a class="header-button" href="<?php echo site_url('cinemas'); ?>">Open cinema list</a>
                    <a class="text-button" href="<?php echo site_url('admin/bookings'); ?>">View admin structure</a>
                </div>
            </div>
        </div>
    </section>

    <section class="catalogue-teaser">
        <div class="content-shell">
            <div class="section-heading-row">
                <div>
                    <span class="section-tag">Inventory Preview</span>
                    <h2>Sample cinema inventory</h2>
                </div>
                <a class="text-button" href="<?php echo site_url('cinemas'); ?>">See all locations</a>
            </div>

            <div class="cinema-grid">
                <?php foreach ($featured_cinemas as $cinema): ?>
                    <article class="cinema-card">
                        <span class="city-pill"><?php echo $cinema['city']; ?></span>
                        <h3><?php echo $cinema['name']; ?></h3>
                        <p><?php echo $cinema['address']; ?></p>
                        <dl class="cinema-meta">
                            <div>
                                <dt>Screens</dt>
                                <dd><?php echo $cinema['screens']; ?></dd>
                            </div>
                            <div>
                                <dt>Reach</dt>
                                <dd><?php echo $cinema['monthly_reach']; ?></dd>
                            </div>
                        </dl>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
