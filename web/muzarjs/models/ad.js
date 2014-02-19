define(['can/util/string', 'models/base'], function (can, BaseModel) {

	return BaseModel.extend({
		findAll: 'GET http://muzarcz.apiary.io/ad',
		findOne: 'GET http://muzarcz.apiary.io/ad/{id}',
		create: 'POST http://muzarcz.apiary.io/ad',
		update: 'PUT http://muzarcz.apiary.io/ad/{id}',
		destroy: 'DELETE http://muzarcz.apiary.io/ad/{id}'
	}, {});

});