import { ref } from "vue";
import { defineStore } from "pinia";
import axios from "axios";

const endpoints = ref(window?.ezrentoutwebstore_object?.endpoints);

export const useLocationsStore = defineStore("locations", {
    state: () => ({
        locations: [],
        isFetching: false,
        isFetchingDone: false,
        totalPages: 1,
    }),
    getters: {
        hasLocations: (state) => state.locations.length > 0,

        getLocationById: (state) => {
            return (id) =>
                state.locations.find((location) => location.id == id);
        },
    },
    actions: {
        async fetchLocations(page = 1) {
            if (this.hasLocations) {
                return;
            }

            this.locations = [];
            this.isFetchingDone = false;
            this.totalPages = 1;

            this.isFetching = true;

            await axios
                .get(endpoints.value.locations, {
                    params: {
                        page: page,
                    },
                })
                .then((res) => {
                    this.locations = res.data.locations;
                    this.totalPages = res.data.total_pages;
                })
                .finally(() => {
                    this.isFetching = false;
                    this.isFetchingDone = true;
                });
        },
    },
});
