<main>
    <section class="page-hero">
        <div class="content-shell split-shell">
            <div>
                <span class="section-tag">Cinema Catalogue</span>
                <h1>Available cinema locations</h1>
                <p class="page-lead">
                    This page is the inventory layer: map discovery, venue filtering, and the jump into booking.
                </p>
            </div>
            <div class="hero-side-note">
                <strong>Next integration</strong>
                <p>Replace this static map block with Google Maps, Mapbox, or Leaflet + real cinema coordinates.</p>
            </div>
        </div>
    </section>

    <section class="map-preview-section">
        <div class="content-shell split-shell split-shell-wide">
            <div class="map-preview">
                <div class="map-surface map-surface-large">
                    <?php foreach ($cinemas as $cinema): ?>
                        <span class="map-pin"><?php echo $cinema['city']; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-panel">
                <h2>Suggested filters</h2>
                <ul class="bullet-list neutral-list">
                    <li>City or region</li>
                    <li>Number of screens</li>
                    <li>Available start month</li>
                    <li>Supported ad lengths</li>
                    <li>Estimated audience reach</li>
                </ul>
                <a class="header-button" href="<?php echo site_url('booking'); ?>">Start a booking</a>
            </div>
        </div>
    </section>

    <section class="catalogue-teaser">
        <div class="content-shell">
            <div class="cinema-grid">
                <?php foreach ($cinemas as $cinema): ?>
                    <article class="cinema-card cinema-card-detailed">
                        <div class="card-topline">
                            <span class="city-pill"><?php echo $cinema['city']; ?></span>
                            <span class="status-pill"><?php echo $cinema['availability']; ?></span>
                        </div>
                        <h3><?php echo $cinema['name']; ?></h3>
                        <p><?php echo $cinema['address']; ?></p>
                        <dl class="cinema-meta">
                            <div>
                                <dt>Screens</dt>
                                <dd><?php echo $cinema['screens']; ?></dd>
                            </div>
                            <div>
                                <dt>Monthly reach</dt>
                                <dd><?php echo $cinema['monthly_reach']; ?></dd>
                            </div>
                            <div>
                                <dt>Formats</dt>
                                <dd><?php echo $cinema['formats']; ?></dd>
                            </div>
                        </dl>
                        <div class="card-actions">
                            <a class="dark-button dark-button-small" href="<?php echo site_url('booking'); ?>">Book this cinema</a>
                            <a class="text-button" href="<?php echo site_url('booking'); ?>">Use in quote</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
