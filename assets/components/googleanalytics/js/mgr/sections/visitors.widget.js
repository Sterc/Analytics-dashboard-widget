Ext.onReady(function() {
    MODx.load({
        xtype : 'googleanalytics-widget-visitors'
    });
});

GoogleAnalytics.panel.WidgetVisitors = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        renderTo    : 'googleanalytics-widget-visitors-div',
        items       : [{
            xtype       : 'googleanalytics-line-chart',
            height      : 200,
            chart       : {
                params      : {
                    data    : 'visits'
                },
                fields      : ['date', 'date_formatted', 'visits', 'pageviews'],
                nameField   : 'date_formatted',
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
        buttons     : [{
            text        : _('googleanalytics.view_more'),
            cls         : 'primary-button',
            handler     : function () {
                MODx.loadPage('home', 'namespace=googleanalytics');
            }
        }]
    });

    GoogleAnalytics.panel.WidgetVisitors.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.WidgetVisitors, MODx.FormPanel);

Ext.reg('googleanalytics-widget-visitors', GoogleAnalytics.panel.WidgetVisitors);