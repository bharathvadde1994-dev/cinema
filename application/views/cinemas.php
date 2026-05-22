<main class="cinema-directory-page">
    <?php
    $selected_state = !empty($filters['state']) ? $filters['state'] : '';
    $selected_cinema_filter = !empty($filters['cinema']) ? $filters['cinema'] : '';
    $keyword_value = !empty($filters['keyword']) ? $filters['keyword'] : '';
    $map_cinemas = array();
    foreach ($cinemas as $cinema_item) {
        if ($cinema_item['latitude'] === NULL || $cinema_item['longitude'] === NULL) {
            continue;
        }

        $map_cinemas[] = array(
            'slug' => $cinema_item['slug'],
            'name' => $cinema_item['name'],
            'lat' => (float) $cinema_item['latitude'],
            'lng' => (float) $cinema_item['longitude'],
            'location_label' => $cinema_item['location_label'],
            'starting_price_label' => $cinema_item['starting_price_label'],
        );
    }
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
                                <?php if (empty($google_maps_api_key)): ?>
                                    <div class="directory-map-message">
                                        <h3>Google Map is not configured yet</h3>
                                        <p>Add your Google Maps API key in `application/config/google_maps.php` or the `GOOGLE_MAPS_API_KEY` environment variable to enable Map View.</p>
                                    </div>
                                <?php endif; ?>
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
    var focusButtons = document.querySelectorAll('[data-focus-button]');
    var mapCanvas = document.querySelector('[data-cinema-map]');
    var focusSlug = <?php echo json_encode($focus_slug); ?>;
    var initialView = <?php echo json_encode($directory_view); ?>;
    var totalCinemas = <?php echo count($cinemas); ?>;
    var mapApiKey = <?php echo json_encode($google_maps_api_key); ?>;
    var mapId = <?php echo json_encode($google_maps_map_id); ?>;
    var cinemas = <?php echo json_encode($map_cinemas); ?>;
    var selected = {};
    var mapInstance = null;
    var infoWindow = null;
    var googleMarkers = {};

    function updateMapMarkerState() {
        Object.keys(googleMarkers).forEach(function (slug) {
            var marker = googleMarkers[slug];
            var isSelected = !!selected[slug];
            var isFocused = marker.__isFocused === true;
            var pinColor = isFocused || isSelected ? '#b72431' : '#ea6a74';
            var scale = isFocused ? 1.22 : 1;

            if (marker.__iconElement) {
                marker.__iconElement.style.transform = 'translateY(-50%) scale(' + scale + ')';
                marker.__iconElement.style.filter = isFocused ? 'drop-shadow(0 10px 20px rgba(183, 36, 49, 0.26))' : 'drop-shadow(0 8px 18px rgba(17, 24, 39, 0.18))';
                marker.__iconElement.innerHTML = '<span style="display:block;width:18px;height:18px;border-radius:50% 50% 50% 0;background:' + pinColor + ';transform:rotate(-45deg);"></span>';
            } else if (marker.setIcon) {
                marker.setIcon({
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: pinColor,
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2,
                    scale: isFocused ? 10 : 8
                });
                marker.setZIndex(isFocused ? 999 : (isSelected ? 600 : 100));
            }
        });
    }

    function openMarkerInfo(cinema) {
        if (!infoWindow || !mapInstance) {
            return;
        }

        infoWindow.setContent(
            '<div class="directory-map-info-window">' +
                '<strong>' + cinema.name + '</strong>' +
                '<span>' + cinema.location_label + '</span>' +
                '<span>' + cinema.starting_price_label + '</span>' +
            '</div>'
        );

        if (googleMarkers[cinema.slug]) {
            infoWindow.open({
                map: mapInstance,
                anchor: googleMarkers[cinema.slug]
            });
        }
    }

    function loadGoogleMapsScript() {
        return new Promise(function (resolve, reject) {
            if (window.google && window.google.maps) {
                resolve();
                return;
            }

            var existing = document.querySelector('script[data-google-maps-script]');
            if (existing) {
                existing.addEventListener('load', resolve, { once: true });
                existing.addEventListener('error', reject, { once: true });
                return;
            }

            var script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(mapApiKey) + '&libraries=marker';
            script.async = true;
            script.defer = true;
            script.setAttribute('data-google-maps-script', '1');
            script.addEventListener('load', resolve, { once: true });
            script.addEventListener('error', reject, { once: true });
            document.head.appendChild(script);
        });
    }

    function buildAdvancedMarkerElement(color) {
        var wrapper = document.createElement('div');
        wrapper.className = 'directory-google-marker';
        wrapper.innerHTML = '<span style="display:block;width:18px;height:18px;border-radius:50% 50% 50% 0;background:' + color + ';transform:rotate(-45deg);"></span>';
        return wrapper;
    }

    function initGoogleMap() {
        if (!mapCanvas || !mapApiKey || !cinemas.length || !(window.google && window.google.maps)) {
            return;
        }

        mapCanvas.innerHTML = '';

        mapInstance = new google.maps.Map(mapCanvas, {
            zoom: 6,
            center: { lat: cinemas[0].lat, lng: cinemas[0].lng },
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            mapId: mapId || undefined
        });

        infoWindow = new google.maps.InfoWindow();
        var bounds = new google.maps.LatLngBounds();
        var supportsAdvanced = !!(google.maps.marker && google.maps.marker.AdvancedMarkerElement && mapId);

        cinemas.forEach(function (cinema) {
            var marker;

            if (supportsAdvanced) {
                var content = buildAdvancedMarkerElement('#ea6a74');
                marker = new google.maps.marker.AdvancedMarkerElement({
                    map: mapInstance,
                    position: { lat: cinema.lat, lng: cinema.lng },
                    title: cinema.name,
                    content: content
                });
                marker.__iconElement = content;
                marker.addListener('click', function () {
                    focusCinema(cinema.slug);
                });
            } else {
                marker = new google.maps.Marker({
                    map: mapInstance,
                    position: { lat: cinema.lat, lng: cinema.lng },
                    title: cinema.name,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: '#ea6a74',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2,
                        scale: 8
                    }
                });
                marker.addListener('click', function () {
                    focusCinema(cinema.slug);
                });
            }

            marker.__isFocused = false;
            marker.__cinema = cinema;
            googleMarkers[cinema.slug] = marker;
            bounds.extend({ lat: cinema.lat, lng: cinema.lng });
        });

        if (!bounds.isEmpty()) {
            mapInstance.fitBounds(bounds, 60);
        }

        updateMapMarkerState();

        if (focusSlug) {
            focusCinema(focusSlug);
        }
    }

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

        if (selectionCount) {
            selectionCount.textContent = total;
        }

        if (bookingButton) {
            bookingButton.disabled = total === 0;
            bookingButton.innerHTML = 'Book Selection (<span data-selection-count>' + total + '</span>)';
            selectionCount = bookingButton.querySelector('[data-selection-count]');
        }

        if (selectAll) {
            selectAll.checked = total > 0 && total === totalCinemas;
            selectAll.indeterminate = total > 0 && total < totalCinemas;
        }

        updateMapMarkerState();
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

        Object.keys(googleMarkers).forEach(function (markerSlug) {
            googleMarkers[markerSlug].__isFocused = markerSlug === slug;
        });
        updateMapMarkerState();

        var marker = googleMarkers[slug];

        if (marker && marker.__cinema) {
            if (mapInstance) {
                mapInstance.panTo({ lat: marker.__cinema.lat, lng: marker.__cinema.lng });
                mapInstance.setZoom(Math.max(mapInstance.getZoom() || 10, 11));
                openMarkerInfo(marker.__cinema);
            }
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

            if (slugs.length === 0) {
                return;
            }

            window.location.href = <?php echo json_encode(site_url('booking')); ?> + '?cinemas=' + encodeURIComponent(slugs.join(','));
        });
    }

    focusButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            focusCinema(button.getAttribute('data-focus-button'));
        });
    });

    setView(initialView);
    syncSelectedState();

    if (mapApiKey && cinemas.length) {
        loadGoogleMapsScript().then(initGoogleMap).catch(function () {
            if (mapCanvas) {
                mapCanvas.innerHTML = '<div class="directory-map-message"><h3>Google Map failed to load</h3><p>Check your API key, billing, referrer restrictions, and authorized domains.</p></div>';
            }
        });
    } else if (focusSlug) {
        focusCinema(focusSlug);
    }
});
</script>
<?php endif; ?>
