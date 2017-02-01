var GhostBanner = function() {
	this.$el = $('<div/>')
	this.$appendTo = $('body');

	this.init();
}

GhostBanner.prototype.init = function() {
	
	this.$el.addClass('jd-ghost-banner');
	this.$appendTo.addClass('is-ghosting');

	this.addEventHandlers();
	this.buildDisplay();
	this.render();
}

GhostBanner.prototype.addEventHandlers = function() {

	this.$el.on('click', function() {
		window.location.href = '/jd/ghostuser/logout';
	});

}

GhostBanner.prototype.buildDisplay = function() {
	this.$el.html('<div class="jd-ghost-banner-content">Ghosting</div>');
}

GhostBanner.prototype.render = function() {
	this.$appendTo.append(this.$el);
}

$(function() {
	var ghosting = new GhostBanner()
});