const mix = require('laravel-mix')
require('laravel-mix-tailwind')
require('laravel-mix-purgecss')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
   module: {
      rules: [
         {
            // Matches all PHP or JSON files in `resources/lang` directory.
            test: /resources[\\\/]lang.+\.(php|json)$/,
            loader: 'laravel-localization-loader',
         }
      ]}
   })
   .js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copy('./node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
   .tailwind('tailwind.config.js')
   .purgeCss({
      enabled: mix.inProduction(),
      folders: ['src', 'templates'],
      extensions: ['twig', 'html', 'js', 'php', 'vue'],
   })
   .setPublicPath('public')
   .browserSync('http://localhost:8000');
