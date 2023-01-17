import { defineStore } from "pinia";
import mapboxgl, { LngLat, Map, Marker, Popup, LngLatBounds } from "mapbox-gl";

mapboxgl.accessToken = window.ezrentoutwebstore_object?.mapbox_token;

export const useMapStore = defineStore("map", {
    state: () => ({
        mapbox: mapboxgl,
        map: null,
    }),
    actions: {
        createMap(element) {
            if (!element || !this.mapbox.accessToken) {
                return;
            }

            this.map = new Map({
                container: element,
                center: [-95.844032, 36.966428],
                style: "mapbox://styles/mapbox/streets-v11",
                zoom: 3.25,
            });
        },

        addMarkers(places) {
            if (!this.map) {
                return;
            }

            let coordinates = [];

            for (let i = 0; i < places.length; i++) {
                const place = places[i];

                coordinates.push([place.lng, place.lat]);

                const popup = new Popup({
                    anchor: "bottom",
                    offset: 30,
                    closeOnClick: false,
                    closeButton: false,
                }).setHTML(`
                    <a href="#/locations/${place.id}/assets" title="Visit store">
                        <span></span>
                        ${place.name}
                    </a>
                `);

                const marker = new Marker()
                    .setLngLat(new LngLat(place.lng, place.lat))
                    .setPopup(popup)
                    .addTo(this.map)
                    .togglePopup();
            }

            // adjust bounds to fit markers
            this.map.fitBounds(
                coordinates.reduce(function (bounds, coord) {
                    return bounds.extend(coord);
                }, new LngLatBounds(coordinates[0], coordinates[0])),
                {
                    padding: 80,
                }
            );
        },

        registerOnLoadCallback(callback) {
            this.map?.on("load", () => {
                callback();
            });
        },

        registerOnMoveCallback(callback) {
            this.map?.on("move", () => {
                callback();
            });
        },
    },
});
