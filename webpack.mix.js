let mix = require('laravel-mix')

//mix.setPublicPath('dist').js('resources/js/card.js', 'js')
mix.setPublicPath('dist').js('resources/js/card.js', 'dist/js')
    .vue({ version: 2 })
    .webpackConfig({
        externals: {
            Vue: 'vue',
        }
    });
