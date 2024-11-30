/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {"verde":"#BEED80","turquesa":"#59D999","azul":"#31ADA1","violeta":"#51647A","oscuro":"#453C5C", "darkred":"#8B0000"},
      },
    }, fontFamily:{
      'body': ['Montserrat']
    },
  },
  plugins: [],
}

