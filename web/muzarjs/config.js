define(function(require, exports, module) {
	var $ = require('jquery');
	var config = {
		debug: (window.location.pathname.indexOf('/app_dev.php') === 0)
	};
	return $.extend(true, config, module.config());
});