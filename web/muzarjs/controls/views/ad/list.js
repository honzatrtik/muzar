define([

	'can',
	'jquery',
	'controls/menu/menu',
	'controls/ad/list',
	'models/category',
	'util/scroll_top_restorer',
	'mustache!./templates/list',
	'controls/base'

], function(can, $, MenuControl, AdListControl, categoryModel, ScrollTopRestorer, renderer, BaseControl){



	return can.Control.extend({}, {

		init: function(element, options) {

			BaseControl.prototype.init.apply(this, arguments);

			element.html(renderer);

			this.options.adListControl = new AdListControl($('#list'), {
				state: this.options.state
			});

			this.options.menuControl = new MenuControl($('#menu'), {
				model: categoryModel,
				state: this.options.state
			});

			var listener = new ScrollTopRestorer($('body'));
			can.bind.call(this.options.adListControl, 'beforeListSpliced', function() {
				listener.saveHeight();
			});
			can.bind.call(this.options.adListControl, 'afterListSpliced', function() {
				listener.restoreScrollTop();
			});



			var self = this;
			var $query = $('#query');
			this.options.state.bind('query', function(event, newVal, oldVal) {
				$query.val(newVal);
			});

			$query.parents('form').bind('click', function(event) {
				event.preventDefault();
				var query = $query.val();
				if (query) {
					self.options.state.attr('query', query);
				} else {
					self.options.state.removeAttr('query');
				}
			});

		},

		update: function() {
			return can.when(
				this.options.menuControl.update(),
				this.options.adListControl.update()
			);
		}
	});


});