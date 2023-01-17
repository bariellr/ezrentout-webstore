import Locations from "./pages/Locations.vue";
import Assets from "./pages/Assets.vue";

const routes = [
    {
        path: "/",
        name: "Locations",
        component: Locations,
        props: true,
    },
    {
        path: "/locations/:locationId/assets",
        name: "Assets",
        component: Assets,
        props: ({ params, query }) => ({
            locationId: Number.parseInt(params.locationId),
            page: query.page ? Number.parseInt(query.page) : 1,
        }),
    },
];

export default routes;
