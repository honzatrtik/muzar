requirejs.config({
	paths: {
		can: 'bower_components/canjs/amd/can',
		jquery: 'bower_components/jquery/jquery',
		funcunit: 'bower_components/funcunit/dist/funcunit',
		mustache: 'bower_components/require-can-renderers/lib/mustache'
	},
	shim: {
		funcunit: {
			deps: ['jquery'],
			exports: 'F'
		}
	},
	urlArgs: 'bust=' + (new Date()).getTime()

});
