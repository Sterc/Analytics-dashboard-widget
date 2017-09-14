Ext.onReady(function() {
    MODx.load({xtype: 'googleanalytics-widget-visitors'});
});

GoogleAnalytics.panel.WidgetVisitors = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        renderTo    : 'googleanalytics-widget-visitors-div',
        items       : [{
            xtype       : 'googleanalytics-line-chart',
            height      : 200,
            pieConfig   : {
                params      : {
                    data    : 'visits'
                },
                fields      : ['date', 'date_short', 'date_long', 'visits', 'pageviews'],
                nameField   : 'date_long',
                dateField   : 'date',
                series      : [{
                    name        : _('googleanalytics.visitors'),
                    dateField   : 'visits'
                }, {
                    name        : _('googleanalytics.pageviews'),
                    dateField   : 'pageviews'
                }]
            }
        }],
        buttons : [{
            text   : _('googleanalytics.view_more'),
            cls    : 'primary-button',
            handler: function () {
                MODx.loadPage(MODx.action['googleanalytics:index'], 'namespace=googleanalytics');
            }
        }]
    });

    GoogleAnalytics.panel.WidgetVisitors.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.WidgetVisitors, MODx.FormPanel);

Ext.reg('googleanalytics-widget-visitors', GoogleAnalytics.panel.WidgetVisitors);