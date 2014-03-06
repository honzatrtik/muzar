define([

	'jquery',
	'can/model',
	'simpleStorage'

], function($, Model, cache) {


	return CategoryModel = Model.extend({

		// Caching
		makeFindAll: function(findAllData){
			// A place to store requests
			return function(params, success, error){
				// is this not cached?
				var key = JSON.stringify(params);
				if(!cache.canUse() || !cache.get(key) ) {
					var self = this;
					// make the request for data, save deferred
					return findAllData(params).then(function(data){
						// convert the raw data into instances
						var models = self.models(data);
						cache.set(key, models, { TTL: 60 * 1000 })
						return models;
					});
				} else {
					var def = can.Deferred();
					def.then(success, error);
					return def.resolve(cache.get(key));
				}
			}
		},

		findAll: function(){
			return $.get('/app_dev.php/api/categories');
		},

		models : function(data) {

			var models = can.Model.models.call(this, data.data);
			var map = can.Map();

			(function(map, children) {
				var func = arguments.callee;
				return can.each(children, function(value, index) {
					map.attr(value.attr('strId'), value);
					func(map, value.attr('children'));
				});
			})(map, models);

			// Set list medadata from server
			models.attr('map', map);
			return models;
		}

	}, {});


});

