let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

let sourcePath = 'app/assets';
let publicPath = 'public';

mix
    .js(sourcePath + '/js/app.js', 'js')
    .sass(sourcePath + '/sass/app.scss', 'css')
    .setPublicPath(publicPath)
    .version();