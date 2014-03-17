requirejs.config({
	baseUrl: '/muzarjs/',
	paths: {
		can: 'bower_components/canjs/amd/can',
		jquery: 'bower_components/jquery/jquery',
		funcunit: 'bower_components/funcunit/dist/funcunit',
		mustache: 'bower_components/require-can-renderers/lib/mustache',
		simpleStorage: 'bower_components/simpleStorage/simpleStorage',
		pace: 'bower_components/pace/pace',
		async: 'bower_components/requirejs-plugins/src/async',
		font: 'bower_components/requirejs-plugins/src/font',
		goog: 'bower_components/requirejs-plugins/src/goog',
		image: 'bower_components/requirejs-plugins/src/image',
		json: 'bower_components/requirejs-plugins/src/json',
		noext: 'bower_components/requirejs-plugins/src/noext',
		mdown: 'bower_components/requirejs-plugins/src/mdown',
		momentjs: 'bower_components/momentjs/moment',
		propertyParser : 'bower_components/requirejs-plugins/src/propertyParser',
		boot: 'boot',
		common: 'common'
	},
	shim: {
		funcunit: {
			deps: ['jquery'],
			exports: 'F'
		}
	},
	urlArgs: 'bust=' + (new Date()).getTime()

});
