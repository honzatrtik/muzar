QUnit.config.reorder = false;
requirejs([

	'boot',
	'tests/controls/item/list',
	'tests/controls/ad/list',
	'tests/models/category',
	'tests/models/ad',
	'tests/controls/menu/menu',
	'tests/controls/ad/detail',
	'tests/components/link_initializer'

], function() {

});
