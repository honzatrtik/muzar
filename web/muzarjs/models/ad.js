define(['can/util/string', 'models/base'], function (can, BaseModel) {

	var AdModel = BaseModel.extend({
		findAll: 'GET /app_dev.php/api/ads',
		findOne: 'GET /app_dev.php/api/ads/{id}',
		create: 'POST /app_dev.php/api/ads',
		update: 'PUT /app_dev.php/api/ads/{id}',
		destroy: 'DELETE /app_dev.php/api/ads/{id}'
	}, {});

	AdModel.List = BaseModel.List.extend();

	return AdModel;

});