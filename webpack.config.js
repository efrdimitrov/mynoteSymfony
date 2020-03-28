// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where all compiled assets will be stored
    .setOutputPath('public/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/rapp/public/build')

    // will create public/build/main.js and public/build/main.css
    .addEntry('main', './assets/js/main.js')
    //Add entry if other js/css needed. first parameter is the generated filename.
    .addEntry('reader', './assets/js/reader.js')

    //file upload with dropzone
    .addEntry('dropzone', './assets/js/dropzone.js')

    //Admin chapter js
    .addEntry('admin-chapter', './assets/js/chapter.js')

    // allow sass/scss files to be processed
    .enableSassLoader()

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // create hashed filenames (e.g. app.abc123.css)
    .enableVersioning()

    .createSharedEntry('vendor', [
        'jquery',
    ])

    .configureFilenames({
        images: '[path][name].[hash:8].[ext]'
    })
;

// export the final configuration
module.exports = Encore.getWebpackConfig();