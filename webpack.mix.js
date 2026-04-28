const mix = require('laravel-mix');

mix
    .js('resources/js/ampp.js', 'public/js')
    .js('resources/js/alpine.js', 'public/js')
    .sass('resources/sass/ampp.scss', 'public/css')
    .sass('resources/sass/pdf.scss', 'public/css')
;

// tiny mce
mix.copyDirectory('node_modules/tinymce/icons', 'public/vendor/tinymce/icons');
mix.copyDirectory('node_modules/tinymce/plugins', 'public/vendor/tinymce/plugins');
mix.copyDirectory('node_modules/tinymce/skins', 'public/vendor/tinymce/skins');
mix.copyDirectory('node_modules/tinymce/themes', 'public/vendor/tinymce/themes');

mix.version();
