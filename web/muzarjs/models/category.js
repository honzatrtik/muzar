define([

	'jquery',
	'can/model'

], function($, Model) {


	return CategoryModel = Model.extend({

		// Caching
		makeFindAll: function(findAllData){
			// A place to store requests
			var cachedRequests = {};
			return function(params, success, error){
				// is this not cached?
				if(!cachedRequests[JSON.stringify(params)] ) {
					var self = this;
					// make the request for data, save deferred
					cachedRequests[JSON.stringify(params)] =
						findAllData(params).then(function(data){
							// convert the raw data into instances
							return self.models(data)
						})
				}
				// get the saved request
				var def = cachedRequests[JSON.stringify(params)]
				// hookup success and error
				def.then(success,error)
				return def;
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

