define([

	'can/util/string',
	'models/base'

], function (can, BaseModel) {

	var AdModel = BaseModel.extend({
		findAll: 'GET http://muzar.localhost/app.php/api/ads',
		findOne: 'GET http://muzar.localhost/app.php/api/ads/{id}',
		create: 'POST http://muzar.localhost/app.php/api/ads',
		update: 'PUT http://muzar.localhost/app.php/api/ads/{id}',
		destroy: 'DELETE http://muzar.localhost/app.php/api/ads/{id}'
	}, {});

	AdModel.List = BaseModel.List.extend();

	return AdModel;

});