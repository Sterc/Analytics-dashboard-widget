GoogleAnalytics.panel.Access = function(config) {
	config = config || {};

    Ext.apply(config, {
        id			: 'googleanalytics-panel-access',
        cls			: 'container',
        items		: [{
            html		: '<h2>'+_('googleanalytics')+'</h2>',
            id			: 'googleanalytics-header',
            cls			: 'modx-page-header'
        }, {
        	layout		: 'form',
            items		: [{
            	html         : '<div class="google-analytics-summary"></div><p>' + _('googleanalytics.stats_desc') + '</p>',
                bodyCssClass : 'panel-desc google-analytics-description'
            },
            {
                html         : '<div class="google-analytics-getstarted"><h2>' + _('googleanalytics.getstarted_title') + '</h2><p>' + _('googleanalytics.getstarted_desc') + '</p></div>',
                cls          : 'x-panel main-wrapper x-panel-noborder'
            }]
        }]
    });

	GoogleAnalytics.panel.Access.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Access, MODx.FormPanel);

Ext.reg('googleanalytics-panel-access', GoogleAnalytics.panel.Access);