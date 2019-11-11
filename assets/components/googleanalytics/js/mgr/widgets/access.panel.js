GoogleAnalytics.panel.Access = function(config) {
    config = config || {};

    Ext.apply(config, {
        id          : 'googleanalytics-panel-access',
        cls         : 'container',
        items       : [{
            html        : '<h2>' + _('googleanalytics.no_oauth_title') + '</h2>',
            cls         : 'modx-page-header'
        }, {
            layout      : 'form',
            items       : [{
                html         : '<p>' + _('googleanalytics.stats_desc') + '</p>',
                bodyCssClass : 'panel-desc'
            }, {
                html         : '<p>' + _('googleanalytics.no_oauth_content') + '</p>',
                cls          : 'main-wrapper'
            }]
        }]
    });

    GoogleAnalytics.panel.Access.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Access, MODx.FormPanel);

Ext.reg('googleanalytics-panel-access', GoogleAnalytics.panel.Access);