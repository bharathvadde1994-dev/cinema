<main class="cinema-directory-page">
    <?php
    $selected_state = !empty($filters['state']) ? $filters['state'] : '';
    $selected_cinema_filter = !empty($filters['cinema']) ? $filters['cinema'] : '';
    $keyword_value = !empty($filters['keyword']) ? $filters['keyword'] : '';
    ?>
    <section class="cinema-directory-section">
        <div class="content-shell">
            <form class="directory-toolbar" action="<?php echo site_url('cinemas'); ?>" method="get">
                <div class="directory-search-grid">
                    <label class="directory-field">
                        <span class="screen-reader-text">Location or ZIP code</span>
                        <input type="text" name="keyword" value="<?php echo html_escape($keyword_value); ?>" placeholder="Location or ZIP code">
                    </label>

                    <label class="directory-field">
                        <span class="screen-reader-text">State</span>
                        <select name="state">
                            <option value="">Select state</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?php echo html_escape($state['region']); ?>"<?php echo $selected_state === $state['region'] ? ' selected' : ''; ?>>
                                    <?php echo html_escape($state['region']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label class="directory-field">
                        <span class="screen-reader-text">Cinema</span>
                        <select name="cinema">
                            <option value="">Select cinema</option>
                            <?php foreach ($cinema_options as $cinema_option): ?>
                                <option value="<?php echo html_escape($cinema_option['slug']); ?>"<?php echo $selected_cinema_filter === $cinema_option['slug'] ? ' selected' : ''; ?>>
                                    <?php echo html_escape($cinema_option['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>

                <div class="directory-toolbar-actions">
                    <button class="directory-search-button" type="submit">Search</button>
                    <label class="directory-select-all">
                        <input type="checkbox" data-select-all>
                        <span>Select All</span>
                    </label>
                    <button class="directory-book-selection" type="button" data-book-selection disabled>
                        Book Selection (<span data-selection-count>0</span>)
                    </button>
                </div>
            </form>

            <div class="directory-results-head">
                <p><?php echo count($cinemas); ?> cinemas found</p>

                <div class="directory-view-toggle" role="tablist" aria-label="Directory view">
                    <button
                        class="directory-view-button<?php echo $directory_view === 'map' ? ' is-active' : ''; ?>"
                        type="button"
                        data-view-button="map"
                        aria-selected="<?php echo $directory_view === 'map' ? 'true' : 'false'; ?>"
                    >
                        Map View
                    </button>
                    <button
                        class="directory-view-button<?php echo $directory_view === 'list' ? ' is-active' : ''; ?>"
                        type="button"
                        data-view-button="list"
                        aria-selected="<?php echo $directory_view === 'list' ? 'true' : 'false'; ?>"
                    >
                        List View
                    </button>
                </div>
            </div>

            <?php if (!empty($cinemas)): ?>
                <div class="directory-view-panel<?php echo $directory_view === 'list' ? ' is-active' : ''; ?>" data-view-panel="list">
                    <div class="directory-list-stack">
                        <?php foreach ($cinemas as $cinema): ?>
                            <article class="directory-card" data-cinema-card data-slug="<?php echo html_escape($cinema['slug']); ?>">
                                <label class="directory-card-select">
                                    <input type="checkbox" value="<?php echo html_escape($cinema['slug']); ?>" data-cinema-select>
                                    <span></span>
                                </label>

                                <div class="directory-card-media">
                                    <img src="<?php echo $cinema['image_path']; ?>" alt="<?php echo html_escape($cinema['name']); ?>">
                                </div>

                                <div class="directory-card-body">
                                    <h2><?php echo html_escape($cinema['name']); ?></h2>
                                    <p class="directory-card-location"><?php echo html_escape($cinema['location_label']); ?></p>
                                    <div class="directory-card-meta">
                                        <span><?php echo html_escape($cinema['starting_price_label']); ?></span>
                                        <span><?php echo html_escape($cinema['weekly_reach_label']); ?></span>
                                        <span><?php echo html_escape($cinema['availability_label']); ?></span>
                                    </div>
                                    <div class="directory-card-features">
                                        <span><?php echo html_escape($cinema['seat_count_label']); ?></span>
                                        <span><?php echo html_escape($cinema['hall_count_label']); ?></span>
                                        <span><?php echo html_escape($cinema['formats_label']); ?></span>
                                    </div>
                                </div>

                                <div class="directory-card-actions">
                                    <button class="directory-detail-button" type="button" data-focus-button="<?php echo html_escape($cinema['slug']); ?>">
                                        Cinema Details
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="directory-view-panel<?php echo $directory_view === 'map' ? ' is-active' : ''; ?>" data-view-panel="map">
                    <div class="directory-map-layout">
                        <aside class="directory-side-list">
                            <?php foreach ($cinemas as $cinema): ?>
                                <article class="directory-card directory-card-compact" data-cinema-card data-slug="<?php echo html_escape($cinema['slug']); ?>">
                                    <label class="directory-card-select">
                                        <input type="checkbox" value="<?php echo html_escape($cinema['slug']); ?>" data-cinema-select>
                                        <span></span>
                                    </label>

                                    <div class="directory-card-body">
                                        <h2><?php echo html_escape($cinema['name']); ?></h2>
                                        <p class="directory-card-location"><?php echo html_escape($cinema['location_label']); ?></p>
                                        <div class="directory-card-meta">
                                            <span><?php echo html_escape($cinema['starting_price_label']); ?></span>
                                            <span><?php echo html_escape($cinema['weekly_reach_label']); ?></span>
                                            <span><?php echo html_escape($cinema['availability_label']); ?></span>
                                        </div>
                                        <div class="directory-card-features">
                                            <span><?php echo html_escape($cinema['hall_count_label']); ?></span>
                                            <span><?php echo html_escape($cinema['formats_label']); ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </aside>

                        <div class="directory-map-panel">
                            <div class="directory-map-badge badge-left">Search this area</div>
                            <div class="directory-map-badge badge-right">Search this area</div>
                            <div class="directory-map-canvas" data-cinema-map>
                                <div class="directory-map-grid"></div>
                                <?php foreach ($cinemas as $cinema): ?>
                                    <button
                                        class="directory-map-marker"
                                        type="button"
                                        data-marker="<?php echo html_escape($cinema['slug']); ?>"
                                        data-lat="<?php echo html_escape($cinema['latitude']); ?>"
                                        data-lng="<?php echo html_escape($cinema['longitude']); ?>"
                                        title="<?php echo html_escape($cinema['name']); ?>"
                                    >
                                        <span></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="directory-empty-state">
                    <h2>No cinemas matched your search</h2>
                    <p>Search without filters to see all cinemas, or try a different location, state, or cinema name.</p>
                    <a class="header-button" href="<?php echo site_url('cinemas'); ?>">View All Cinemas</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php if (!empty($cinemas)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var viewButtons = document.querySelectorAll('[data-view-button]');
    var viewPanels = document.querySelectorAll('[data-view-panel]');
    var selectAll = document.querySelector('[data-select-all]');
        var selectionCount = document.querySelector('[data-selection-count]');
        var bookingButton = document.querySelector('[data-book-selection]');
        var checkboxes = document.querySelectorAll('[data-cinema-select]');
    var markers = document.querySelectorAll('[data-marker]');
    var focusButtons = document.querySelectorAll('[data-focus-button]');
    var mapCanvas = document.querySelector('[data-cinema-map]');
    var focusSlug = <?php echo json_encode($focus_slug); ?>;
    var initialView = <?php echo json_encode($directory_view); ?>;
    var totalCinemas = <?php echo count($cinemas); ?>;
    var selected = {};

    function setView(viewName) {
        viewButtons.forEach(function (button) {
            var isActive = button.getAttribute('data-view-button') === viewName;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        viewPanels.forEach(function (panel) {
            panel.classList.toggle('is-active', panel.getAttribute('data-view-panel') === viewName);
        });

        if (window.history && window.history.replaceState) {
            var url = new URL(window.location.href);
            url.searchParams.set('view', viewName);
            window.history.replaceState({}, '', url.toString());
        }
    }

        function syncSelectedState() {
            var keys = Object.keys(selected);
            var total = keys.length;

        checkboxes.forEach(function (checkbox) {
            var isChecked = !!selected[checkbox.value];
            checkbox.checked = isChecked;
            checkbox.closest('[data-cinema-card]').classList.toggle('is-selected', isChecked);
        });

        markers.forEach(function (marker) {
            marker.classList.toggle('is-selected', !!selected[marker.getAttribute('data-marker')]);
        });

        if (selectionCount) {
            selectionCount.textContent = total;
        }

        if (bookingButton) {
            bookingButton.disabled = total !== 1;
            bookingButton.innerHTML = (total > 1 ? 'Single cinema only' : 'Book Selection') + ' (<span data-selection-count>' + total + '</span>)';
            selectionCount = bookingButton.querySelector('[data-selection-count]');
        }

        if (selectAll) {
            selectAll.checked = total > 0 && total === totalCinemas;
            selectAll.indeterminate = total > 0 && total < totalCinemas;
        }
    }

    function toggleSelection(slug, value) {
        if (value) {
            selected[slug] = true;
        } else {
            delete selected[slug];
        }

        syncSelectedState();
    }

    function focusCinema(slug) {
        if (!slug) {
            return;
        }

        setView('map');

        document.querySelectorAll('[data-cinema-card], [data-marker]').forEach(function (node) {
            node.classList.remove('is-focused');
        });

        document.querySelectorAll('[data-slug="' + slug + '"]').forEach(function (card) {
            card.classList.add('is-focused');
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });

        var marker = document.querySelector('[data-marker="' + slug + '"]');

        if (marker) {
            marker.classList.add('is-focused');
            marker.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
        }
    }

    viewButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            setView(button.getAttribute('data-view-button'));
        });
    });

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            toggleSelection(checkbox.value, checkbox.checked);
        });
    });

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(function (checkbox) {
                if (selectAll.checked) {
                    selected[checkbox.value] = true;
                } else {
                    delete selected[checkbox.value];
                }
            });

            syncSelectedState();
        });
    }

        if (bookingButton) {
            bookingButton.addEventListener('click', function () {
                var slugs = Object.keys(selected);

            if (slugs.length !== 1) {
                return;
            }

            window.location.href = <?php echo json_encode(site_url('booking')); ?> + '?cinemas=' + encodeURIComponent(slugs[0]);
        });
    }

    focusButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            focusCinema(button.getAttribute('data-focus-button'));
        });
    });

    markers.forEach(function (marker) {
        marker.addEventListener('click', function () {
            focusCinema(marker.getAttribute('data-marker'));
        });
    });

    if (mapCanvas && markers.length) {
        var minLat = Infinity;
        var maxLat = -Infinity;
        var minLng = Infinity;
        var maxLng = -Infinity;

        markers.forEach(function (marker) {
            var lat = parseFloat(marker.getAttribute('data-lat'));
            var lng = parseFloat(marker.getAttribute('data-lng'));

            minLat = Math.min(minLat, lat);
            maxLat = Math.max(maxLat, lat);
            minLng = Math.min(minLng, lng);
            maxLng = Math.max(maxLng, lng);
        });

        var latRange = Math.max(0.2, maxLat - minLat);
        var lngRange = Math.max(0.2, maxLng - minLng);

        markers.forEach(function (marker) {
            var lat = parseFloat(marker.getAttribute('data-lat'));
            var lng = parseFloat(marker.getAttribute('data-lng'));
            var top = 10 + ((maxLat - lat) / latRange) * 78;
            var left = 8 + ((lng - minLng) / lngRange) * 82;

            marker.style.top = top + '%';
            marker.style.left = left + '%';
        });
    }

    setView(initialView);
    syncSelectedState();

    if (focusSlug) {
        focusCinema(focusSlug);
    }
});
</script>
<?php endif; ?>
