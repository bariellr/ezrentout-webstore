<script setup>
import { toRefs, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useLocationsStore } from "../stores/locations";
import { useAssetsStore } from "../stores/assets";
import { ListBulletIcon, Squares2X2Icon } from "@heroicons/vue/24/solid";
import LoadingHero from "../components/LoadingHero.vue";
import Asset from "../components/Asset.vue";

const router = useRouter();

const locationsStore = useLocationsStore();
const assetsStore = useAssetsStore();

const props = defineProps({
    locationId: Number,
    page: Number,
});

const { locationId, page } = toRefs(props);

const location = locationsStore.getLocationById(locationId.value);

const changeLocation = (e) => {
    router.push({ name: "Assets", params: { locationId: e.target.value } });
};

onMounted(async () => {
    // redirect to locations
    if (!locationsStore.hasLocations || !location) {
        router.push({ name: "Locations" });
        return;
    }

    // fetch assets
    await assetsStore.fetchAssets(location.id, page.value);
});
</script>

<template>
    <div>
        <h2
            class="x-text-4xl x-font-light x-text-center x-mb-10 x-leading-snug"
        >
            {{ location?.name }} Assets
        </h2>

        <div class="x-flex x-gap-4 x-items-center x-justify-between x-mb-6">
            <!-- location selector -->
            <div class="x-form-control">
                <select
                    @change.prevent="(e) => changeLocation(e)"
                    class="x-select x-select-bordered x-w-full x-max-w-xs"
                >
                    <option disabled>Select a Location</option>
                    <option
                        v-for="location in locationsStore.locations"
                        :value="location.id"
                        v-text="location.name"
                        :selected="location.id == locationId"
                    ></option>
                </select>
            </div>

            <!-- layout selector -->
            <div class="x-btn-group" v-if="assetsStore.hasAssets">
                <button
                    @click="assetsStore.disableGridLayout()"
                    class="x-btn x-btn-square"
                    :class="{ 'x-btn-active': !assetsStore.gridLayout }"
                >
                    <ListBulletIcon class="x-w-6 x-h-6"></ListBulletIcon>
                </button>
                <button
                    @click="assetsStore.enableGridLayout()"
                    class="x-btn x-btn-square"
                    :class="{ 'x-btn-active': assetsStore.gridLayout }"
                >
                    <Squares2X2Icon class="x-w-6 x-h-6"></Squares2X2Icon>
                </button>
            </div>
        </div>

        <LoadingHero
            v-if="assetsStore.isFetching"
            text="Loading assets..."
        ></LoadingHero>

        <div
            v-if="assetsStore.isFetchingDone && !assetsStore.hasAssets"
            class="x-alert x-alert-error"
        >
            No assets available.
        </div>

        <!-- items -->
        <div
            :class="[
                assetsStore.gridLayout
                    ? 'x-grid x-grid-cols-2 x-gap-4'
                    : 'x-flex x-justify-center x-flex-row x-flex-wrap x-gap-6',
            ]"
        >
            <transition-group
                enter-active-class="x-transition x-duration-100 x-ease-out"
                enter-from-class="x-transform x-scale-95 x-opacity-0"
                enter-to-class="x-transform x-scale-100 x-opacity-100"
                leave-active-class="x-transition x-duration-75 x-ease-in"
                leave-from-class="x-transform x-scale-100 x-opacity-100"
                leave-to-class="x-transform x-scale-95 x-opacity-0"
            >
                <Asset
                    v-for="asset in assetsStore.assets"
                    :key="asset.sequence_num"
                    :asset="asset"
                ></Asset>
            </transition-group>
        </div>

        <!-- pagination -->
        <div class="x-btn-group x-mt-10" v-if="assetsStore.totalPages > 1">
            <router-link
                class="x-btn"
                :class="{ 'x-btn-active': page == index }"
                v-for="index in assetsStore.totalPages"
                :to="{
                    name: 'Assets',
                    params: { locationId: locationId },
                    query: { page: index },
                }"
                v-text="index"
            ></router-link>
        </div>
    </div>
</template>
