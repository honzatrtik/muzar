requirejs.config({
	paths: {
		can: 'bower_components/canjs/amd/can',
		jquery: 'bower_components/jquery/jquery',
		mustache: 'bower_components/require-can-renderers/lib/mustache',
		ejs: 'bower_components/require-can-renderers/lib/ejs',
		funcunit: 'bower_components/funcunit/dist/funcunit'
	},
	shim: {
		funcunit: {
			deps: ['jquery'],
			exports: 'F'
		}
	},
	urlArgs: 'bust=' + (new Date()).getTime()

});
