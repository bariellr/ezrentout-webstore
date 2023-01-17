<script setup>
import { ref, onMounted } from "vue";
import { useLocationsStore } from "../stores/locations";
import { useMapStore } from "../stores/map";
import LoadingHero from "../components/LoadingHero.vue";

const locationsStore = useLocationsStore();
const mapStore = useMapStore();

const mapElement = ref();

onMounted(async () => {
    // fetch locations
    await locationsStore.fetchLocations();

    // create the map
    mapStore.createMap(mapElement.value);

    // create markers when map is loaded
    mapStore.registerOnLoadCallback(() => {
        let places = [];

        for (let i = 0; i < locationsStore.locations.length; i++) {
            const location = locationsStore.locations[i];

            places.push({
                lng: Number.parseInt(location.longi),
                lat: Number.parseInt(location.lati),
                name: location.name,
                id: location.id,
            });
        }

        mapStore.addMarkers(places);
    });
});
</script>

<template>
    <div>
        <h2
            class="x-text-4xl x-font-light x-text-center x-mb-10 x-leading-snug"
        >
            Select a Location
        </h2>

        <LoadingHero
            v-if="locationsStore.isFetching"
            text="Loading locations..."
        ></LoadingHero>

        <div
            v-if="locationsStore.isFetchingDone && !locationsStore.hasLocations"
            class="x-alert x-alert-error"
        >
            No locations available.
        </div>

        <div
            class="x-relative x-w-full x-h-[36rem] x-rounded-lg x-overflow-hidden"
        >
            <div class="x-w-full x-h-full" ref="mapElement"></div>
        </div>
    </div>
</template>

<style lang="postcss">
[class*="mapboxgl-popup-anchor"] .mapboxgl-popup-tip {
    @apply !x-border-t-base-100;
}

.mapboxgl-popup-content {
    @apply !x-bg-base-100 x-text-base-content !x-p-1.5 x-relative;
}

.mapboxgl-popup-content > a {
    @apply hover:x-text-primary hover:x-underline hover:x-underline-offset-2 x-flex x-items-center x-gap-1 x-px-2;
}

.mapboxgl-popup-content > a > span {
    @apply x-inset-0 x-absolute;
}
</style>
