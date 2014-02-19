define([], function () {

	// Zajistuje udrzeni pozice pri odebrani elementu ze zacatku seznamu
	var ScrollTopRestorer = function($element) {
		this.$element = $element;
	};
	ScrollTopRestorer.prototype.saveHeight = function() {
		this.height = this.$element.height();
	};
	ScrollTopRestorer.prototype.restoreScrollTop = function() {
		this.$element.scrollTop(this.$element.scrollTop() - (this.height - this.$element.height()));
	};

	return ScrollTopRestorer;

});