import { ref } from "vue";
import { defineStore } from "pinia";
import { vsprintf } from "sprintf-js";
import axios from "axios";

const endpoints = ref(window?.ezrentoutwebstore_object?.endpoints);

export const useAssetsStore = defineStore("assets", {
    state: () => ({
        assets: [],
        isFetching: false,
        isFetchingDone: false,
        totalPages: 1,
        gridLayout: false,
    }),
    getters: {
        hasAssets: (state) => state.assets.length > 0,
    },
    actions: {
        async fetchAssets(locationId, page = 1) {
            this.assets = [];
            this.isFetchingDone = false;
            this.totalPages = 1;

            this.isFetching = true;

            await axios
                .get(vsprintf(endpoints.value.assets, locationId), {
                    params: {
                        page: page,
                    },
                })
                .then((res) => {
                    this.assets = res.data.assets;
                    this.totalPages = res.data.total_pages;
                })
                .finally(() => {
                    this.isFetching = false;
                    this.isFetchingDone = true;
                });
        },

        enableGridLayout() {
            this.gridLayout = true;
        },

        disableGridLayout() {
            this.gridLayout = false;
        },
    },
});
