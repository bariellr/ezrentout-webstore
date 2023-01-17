/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.{vue,js,ts,jsx,tsx}"],
    prefix: "x-",
    theme: {
        extend: {},
    },
    plugins: [require("daisyui")],
};
