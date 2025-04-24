/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')
module.exports = {
  content: [
    './**/*.html',
    './src/**/*.vue',
    './src/**/*.jsx',
    './formkit.config.js',
    './node_modules/vue-tailwind-datepicker/**/*.js',
  ],
  theme: {
  },
  plugins: [
    require('@tailwindcss/forms'),
    // Otros plugins si los necesitas
  ],
};
