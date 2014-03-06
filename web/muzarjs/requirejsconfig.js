requirejs.config({
	paths: {
		can: 'bower_components/canjs/amd/can',
		jquery: 'bower_components/jquery/jquery',
		funcunit: 'bower_components/funcunit/dist/funcunit',
		mustache: 'bower_components/require-can-renderers/lib/mustache',
		simpleStorage: 'bower_components/simpleStorage/simpleStorage',
		pace: 'bower_components/pace/pace',
		boot: 'boot'
	},
	shim: {
		funcunit: {
			deps: ['jquery'],
			exports: 'F'
		}
	},
	urlArgs: 'bust=' + (new Date()).getTime()

});
