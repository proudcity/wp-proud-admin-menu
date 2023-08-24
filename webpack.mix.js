// webpack.mix.js

let mix = require('laravel-mix');

mix.webpackConfig({
    devtool: "source-map"
});

mix.sass('assets/styles/proud-admin-menu.scss', 'dist/styles', {
    sassOptions:{
        outputStyle: "compressed",
    }
})
    .sourceMaps();
