import type { Config } from 'tailwindcss';

export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/Filament/**/*.php',
    './vendor/filament/**/*.blade.php',
    './vendor/blade-ui-kit/**/*.blade.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
} satisfies Config;
