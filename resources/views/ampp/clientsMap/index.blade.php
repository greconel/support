<x-layouts.ampp :title="__('Clients map')">
    <x-push name="styles">
        <style>
            /*Legend specific*/
            .legend {
                background: rgba(255, 255, 255, 0.9);
                transition: all 0.5s;
                -webkit-transition: all 0.5s; /* Safari 3.1 to 6.0 */
            }

            .legend:hover {
                cursor: pointer;
                transform: scale(1.05);
            }

            .pixi-popup {
                padding-bottom: 34px;
            }

            .leaflet-popup-content{
                width: auto !important;
            }
        </style>
    </x-push>

    <x-ui.page-title>{{ __('Clients map') }}</x-ui.page-title>

    <div id="map" class="rounded-3 shadow-lg" style="height: 80vh"></div>

    <x-push name="scripts">
        <script src="{{ asset('vendor/bezier/bezier-easing.js') }}"></script>

        <script>
            let map;
            let mapPosition = [50.847812, 4.350885];

            // this is used to simulate leaflet zoom animation timing:
            const easing = BezierEasing(0, 0, 0.25, 1);

            // pixi js
            const loader = new PIXI.Loader();
            loader.add('client', '{{ asset('images/icons/user-tie/marker-user-tie-blue.png') }}');

            // load map with pixi js
            document.addEventListener("DOMContentLoaded", function () {
                loader.load(function (loader, resources) {
                    // create a map
                    map = L.map('map').setView(mapPosition, 13);

                    L.tileLayer(
                        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                        {
                            maxZoom: 18,
                            minZoom: 1,
                            attribution: 'Data by <a href="https://openstreetmap.org">OpenStreetMap</a>, under <a href="http://www.openstreetmap.org/copyright">ODbL</a>.',
                            detectRetina: true
                        }
                    ).addTo(map);

                    if(navigator.geolocation){
                        navigator.geolocation.getCurrentPosition((position) => {
                            map.setView([position.coords.latitude, position.coords.longitude])
                        });
                    }

                    // add legend to map
                    const legend = L.control({position: "bottomleft"});

                    legend.onAdd = function (map) {
                        let legend = '{!! str_replace(["\r","\n"], "", view('ampp.clientsMap.legend')) !!}';

                        let div = L.DomUtil.create("div", "legend rounded-3");

                        div.innerHTML = legend;

                        return div;
                    };

                    legend.addTo(map);

                    // render customers
                    const pixiOverlay = (function () {
                        let frame = null;
                        let firstDraw = true;
                        let prevZoom;
                        let zoomChangeTs = null;
                        const iconScale = .4;
                        const clients = [];
                        let markerLatLng;
                        let marker;

                        @foreach($clients as $client)
                            @if(($client->lat != 0 && $client->lat) || ($client->lng != 0 || $client->lng))
                                markerLatLng = ["{{ str_replace(',', '.', $client->lat) }}", "{{ str_replace(',', '.', $client->lng) }}"];
                                marker = new PIXI.Sprite(resources["client"].texture);

                                marker.popup = L.popup({className: 'pixi-popup'})
                                    .setLatLng(markerLatLng)
                                    .setContent('{!! str_replace(array("\r","\n"),"",view('ampp.clientsMap.popup', ['client' => $client])) !!}');
                                //.openOn(map); // open on page load

                                clients.push(marker);
                            @endif
                        @endforeach

                        // new pixi container
                        const pixiContainer = new PIXI.Container();
                        pixiContainer.interactive = true;
                        pixiContainer.buttonMode = true;

                        // set prospects interactive and add to container
                        clients.forEach(function (client) {
                            client.interactive = true;
                            pixiContainer.addChild(client);
                        });

                        const doubleBuffering = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

                        return L.pixiOverlay(function (utils, event) {
                            if (frame) {
                                cancelAnimationFrame(frame);
                                frame = null;
                            }

                            const zoom = utils.getMap().getZoom();
                            const container = utils.getContainer();
                            const renderer = utils.getRenderer();
                            const project = utils.latLngToLayerPoint;
                            const getScale = utils.getScale;
                            const scale = utils.getScale();
                            const invScale = iconScale / scale;

                            // draw all customers for the first time
                            if (firstDraw) {
                                clients.forEach(function (client) {
                                    const markerCoords = project([client.popup.getLatLng().lat, client.popup.getLatLng().lng]);
                                    client.x = markerCoords.x;
                                    client.y = markerCoords.y;
                                    client.anchor.set(0.5, 0.5);
                                    client.scale.set(iconScale / scale);
                                    client.currentScale = iconScale / scale;

                                    // open popup on customer click
                                    utils.getMap().on('click', function (e) {
                                        // not really nice but much better than before
                                        // good starting point for improvements
                                        const interaction = utils.getRenderer().plugins.interaction;
                                        const pointerEvent = e.originalEvent;
                                        const pixiPoint = new PIXI.Point();
                                        // get global click position in pixiPoint:
                                        interaction.mapPositionToPoint(pixiPoint, pointerEvent.clientX, pointerEvent.clientY);
                                        // get what is below the click if any:
                                        const target = interaction.hitTest(pixiPoint, container);

                                        if (target && target.popup) {
                                            target.popup.openOn(map);
                                        }
                                    });
                                });
                            }

                            if (firstDraw || prevZoom !== zoom) {
                                clients.forEach(function (client) {
                                    client.currentScale = client.scale.x;
                                    client.targetScale = iconScale / scale;
                                });
                            }

                            // animation when zooming in/out
                            const duration = 100;
                            let start;

                            function animate(timestamp) {
                                let progress;
                                if (start === null) start = timestamp;

                                progress = timestamp - start;
                                let lambda = progress / duration;

                                if (lambda > 1) lambda = 1;

                                lambda = lambda * (0.4 + lambda * (2.2 + lambda * -1.6));

                                clients.forEach(function (client) {
                                    client.scale.set(client.currentScale + lambda * (client.targetScale - client.currentScale));
                                });

                                renderer.render(container);

                                if (progress < duration) {
                                    frame = requestAnimationFrame(animate);
                                }
                            }

                            if (!firstDraw && prevZoom !== zoom) {
                                start = null;
                                frame = requestAnimationFrame(animate);
                            }

                            // spinning animation
                            if (event.type === 'zoomanim') {
                                zoomChangeTs = 0;
                                const targetScale = iconScale / getScale(event.zoom);

                                clients.forEach(function(client) {
                                    client.currentScale = client.scale.x;
                                    client.targetScale = targetScale;
                                });
                                return;
                            }


                            if (event.type === 'redraw') {
                                const delta = event.delta;

                                clients.forEach(function(client) {
                                    if (client.notify === true){
                                        client.rotation -= 0.03 * delta;
                                    }

                                });

                                if (zoomChangeTs !== null) {
                                    const spinDuration = 17;
                                    zoomChangeTs += delta;
                                    let lambda = zoomChangeTs / spinDuration;

                                    if (lambda > 1) {
                                        lambda = 1;
                                        zoomChangeTs = null;
                                    }

                                    lambda = easing(lambda);

                                    clients.forEach(function(client) {
                                        client.scale.set(client.currentScale + lambda * (client.targetScale - client.currentScale));
                                    });
                                }
                                else {
                                    clients.forEach(function(client) {
                                        client.scale.set(invScale);
                                    });
                                }
                            }

                            firstDraw = false;
                            prevZoom = zoom;
                            renderer.render(container);

                        }, pixiContainer, {
                            doubleBuffering: doubleBuffering,
                            autoPreventDefault: false
                        });
                    })();

                    pixiOverlay.addTo(map);

                    const ticker = new PIXI.Ticker;

                    ticker.add(function(delta) {
                        pixiOverlay.redraw({type: 'redraw', delta: delta});
                    });

                    ticker.start();

                    map.on('zoomanim', pixiOverlay.redraw, pixiOverlay);
                });
            });
        </script>
    </x-push>
</x-layouts.ampp>
