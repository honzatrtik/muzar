define([

	'can/util/string',
	'models/base',
	'config'

], function (can, BaseModel, config) {

	var prefix = config.debug
		? '/app_dev.php/'
		: '/';

	var AdModel = BaseModel.extend({
		findAll: 'GET ' + prefix  + 'api/ads',
		findOne: 'GET ' + prefix  + 'api/ads/{id}',
		create: 'POST ' + prefix  + 'api/ads',
		update: 'PUT ' + prefix  + 'api/ads/{id}',
		destroy: 'DELETE ' + prefix  + 'api/ads/{id}'
	}, {});

	AdModel.List = BaseModel.List.extend();

	return AdModel;

});