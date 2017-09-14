var GoogleAnalytics = function(config) {
	config = config || {};
	
	GoogleAnalytics.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics, Ext.Component, {
	page	: {},
	window	: {},
	grid	: {},
	tree	: {},
	panel	: {},
	combo	: {},
	config	: {}
});

Ext.reg('googleanalytics', GoogleAnalytics);

GoogleAnalytics = new GoogleAnalytics();