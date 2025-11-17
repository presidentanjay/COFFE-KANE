import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  // Paths untuk file yang menggunakan kelas Tailwind agar dapat di-purge saat build
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.js',
  ],

  theme: {
    extend: {
      fontFamily: {
        // Menambahkan font custom 'Figtree' plus font default sans
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  // Plugin Tailwind resmi untuk styling form yang konsisten dan accessible
  plugins: [forms],
};
