var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .enableVersioning()

    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
	.addEntry('adm',  './assets/js/adm.js' )


	.createSharedEntry('vendor', [
        'jquery', 
        'bootstrap',
    ]) 

    // uncomment if you use Sass/SCSS files
    .enableSassLoader(function(sassOptions) {}, {
         resolveUrlLoader: false
     })

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
	.enableSourceMaps(!Encore.isProduction())
    .cleanupOutputBeforeBuild()
	.enableBuildNotifications()
;

module.exports = Encore.getWebpackConfig();
