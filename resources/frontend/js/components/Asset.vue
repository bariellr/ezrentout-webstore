<script setup>
import { ref, computed, toRefs, onMounted } from "vue";
import { replace, forEach } from "lodash";

const props = defineProps({
    asset: Object,
});

const { asset } = toRefs(props);

const image = computed(() => {
    return asset.value.display_image == "/images/no-image.jpg"
        ? window?.ezrentoutwebstore_object?.webstore_url +
              asset.value.display_image
        : asset.value.display_image;
});

const rentalPrices = computed(() => {
    let prices = [];

    forEach(asset.value.rental_prices, function (val, key) {
        if (Number.parseFloat(val)) {
            prices.push({
                label: replace(key, "_", " "),
                value: val,
            });
        }
    });

    return prices;
});

const addToCart = (num, $event) => {
    window.ezrAddItemToCartDialog(num, $event.target);
};
</script>

<template>
    <div
        class="x-w-full x-card x-card-compact lg:x-card-side x-group x-bg-base-100 x-transition-colors x-text-base-content"
    >
        <figure class="x-shrink-0 x-bg-black/60">
            <img
                :src="image"
                alt="Album"
                width="300"
                height="300"
                class="x-w-40 x-h-40 x-object-cover"
            />
        </figure>

        <div class="x-card-body x-block x-space-y-1.5">
            <h3
                class="x-card-title x-m-0 x-p-0 x-text-base x-font-semibold"
                v-if="asset.name"
                v-text="asset.name"
            ></h3>

            <p
                class="x-p-0"
                v-if="asset.description"
                v-text="asset.description"
            ></p>

            <p class="x-p-0" v-if="asset.price">
                Price:
                <span class="x-font-bold x-font-mono x-text-primary"
                    >${{ asset.price }}</span
                >
            </p>

            <div v-if="rentalPrices.length">
                <p class="x-p-0">Rental prices:</p>
                <ul class="!x-pb-0 x-m-0">
                    <li v-for="price in rentalPrices">
                        <span class="x-font-bold x-font-mono x-text-primary"
                            >${{ price.value }}</span
                        >
                        {{ price.label }}
                    </li>
                </ul>
            </div>

            <div>
                <button
                    type="button"
                    id="add-to-cart"
                    @click="addToCart(asset.sequence_num, $event)"
                    class="x-btn x-btn-sm x-btn-primary x-normal-case"
                >
                    Add to cart
                </button>
            </div>
        </div>
    </div>
</template>
