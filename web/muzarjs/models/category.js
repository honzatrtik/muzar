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
				var self = this;
				var key = JSON.stringify(params);
				var def;

				if(!cache.canUse() || !cache.get(key) ) {

					// make the request for data, save deferred
					def = findAllData(params).then(function(data){
						// convert the raw data into instances
						cache.set(key, data, { TTL: 60 * 1000 });
						return self.models(data);
					});
					def.then(success, error);
					return def;

				} else {

					def = can.Deferred();
					def.then(success, error);
					return def.resolve(self.models(cache.get(key)));
				}
			}
		},

		findAll: function(){
			return $.get('/app_dev.php/api/categories');
		},

		models : function(data) {

			var models = can.Model.models.call(this, data.data);
			var mapId = can.Map();
			var mapStrId = can.Map();

			(function(mapId, mapStrId, children, parent) {
				var func = arguments.callee;
				return can.each(children, function(value, index) {
					value.attr('parent', parent);
					mapId.attr(value.attr('id'), value);
					mapStrId.attr(value.attr('strId'), value);
					func(mapId, mapStrId, value.attr('children'), value);
				});
			})(mapId, mapStrId, models, null);

			// Set list medadata from server
			models.attr('mapId', mapId);
			models.attr('mapStrId', mapStrId);
			return models;
		}

	}, {});


});

