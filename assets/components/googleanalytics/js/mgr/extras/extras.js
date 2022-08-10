GoogleAnalytics.combo.History = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        store       : new Ext.data.ArrayStore({
            mode        : 'local',
            fields      : ['type','label'],
            data        : [
                [7, _('googleanalytics.history_1')],
                [14, _('googleanalytics.history_2')],
                [21, _('googleanalytics.history_3')],
                [28, _('googleanalytics.history_4')]
            ]
        }),
        remoteSort  : ['label', 'asc'],
        hiddenName  : 'history',
        valueField  : 'label',
        displayField : 'label',
        mode        : 'local',
        value       : 14
    });

    GoogleAnalytics.combo.History.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.combo.History, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-history', GoogleAnalytics.combo.History);

GoogleAnalytics.combo.Accounts = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Accounts\\Get'
        },
        fields      : ['id', 'name'],
        hiddenName  : 'account',
        valueField  : 'id',
        displayField: 'name',
        editable    : true,
        typeAhead   : true
    });

    GoogleAnalytics.combo.Accounts.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.combo.Accounts, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-accounts', GoogleAnalytics.combo.Accounts);

GoogleAnalytics.combo.Properties = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url             : GoogleAnalytics.config.connector_url,
        baseParams      : Ext.apply({
            action          : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Properties\\Get',
            account         : '',
        }, config.params || {}),
        fields          : ['id', 'name'],
        hiddenName      : 'property',
        valueField      : 'id',
        displayField    : 'name',
        editable        : true,
        typeAhead       : true
    });

    GoogleAnalytics.combo.Properties.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.combo.Properties, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-properties', GoogleAnalytics.combo.Properties);

GoogleAnalytics.combo.Profiles = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url             : GoogleAnalytics.config.connector_url,
        baseParams      : Ext.apply({
            action          : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Profiles\\Get',
            account         : '',
            property        : ''
        }, config.params || {}),
        fields          : ['id', 'name'],
        hiddenName      : 'profile',
        valueField      : 'id',
        displayField    : 'name',
        editable        : true,
        typeAhead       : true
    });

    GoogleAnalytics.combo.Profiles.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.combo.Profiles, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-profiles', GoogleAnalytics.combo.Profiles);
