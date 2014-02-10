define(['can/util/string', 'can/model'], function (can) {

	return can.Model.extend({
		models: function(data) {
			return can.Model.models.call(this, data.data);
		}
	}, {});

});