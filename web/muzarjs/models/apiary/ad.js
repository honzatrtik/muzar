define(['can/util/string', 'models/base'], function (can, BaseModel) {

	var AdModel = BaseModel.extend({
		findAll: 'GET http://muzarcz.apiary.io/ads',
		findOne: 'GET http://muzarcz.apiary.io/ads/{id}',
		create: 'POST http://muzarcz.apiary.io/ads',
		update: 'PUT http://muzarcz.apiary.io/ads/{id}',
		destroy: 'DELETE http://muzarcz.apiary.io/ads/{id}'
	}, {});

	AdModel.List = BaseModel.List.extend();

	return AdModel;

});