Ext.onReady(function() {
    MODx.load({
        xtype : 'googleanalytics-page-home'
    });
});

GoogleAnalytics.page.Home = function(config) {
    config = config || {};

    config.buttons = [];

    if (GoogleAnalytics.config.branding_url) {
        config.buttons.push({
            text        : 'GoogleAnalytics ' + GoogleAnalytics.config.version,
            cls         : 'x-btn-branding',
            handler     : this.loadBranding
        });
    }

    if (GoogleAnalytics.config.authorized_profile) {
        config.buttons.push({
            xtype       : 'googleanalytics-combo-profiles',
            name        : 'googleanalytics-filter-profiles',
            id          : 'googleanalytics-filter-profiles',
            emptyText   : _('googleanalytics.filter_profiles'),
            value       : MODx.request.profile || GoogleAnalytics.config.authorized_profile.id,
            params      : {
                account     : GoogleAnalytics.config.authorized_profile.account_id,
                property    : GoogleAnalytics.config.authorized_profile.property_id
            },
            listeners   : {
                'change'    : {
                    fn          : this.filterProfile,
                    scope       : this
                }
            }
        });
    }

    config.buttons.push({
        text        : '<i class="icon icon-window-restore"></i>' + _('googleanalytics.open_googleanalytics'),
        cls         : 'primary-button',
        handler     : this.loadGoogleAnalytics
    });

    if (GoogleAnalytics.config.permissions.admin) {
        if (GoogleAnalytics.config.authorized) {
            config.buttons.push({
                text        : '<i class="icon icon-cog"></i>' + _('googleanalytics.settings'),
                handler     : this.updateSettings
            }, {
                text        : '<i class="icon icon-refresh"></i>' + _ ('googleanalytics.refresh_settings'),
                handler     : this.refreshSettings
            });
        } else {
            config.buttons.push({
                text        : '<i class="icon icon-unlock"></i>' + _('googleanalytics.auth_create'),
                handler     : this.createAuth
            });
        }
    }

    if (GoogleAnalytics.config.branding_url_help) {
        config.buttons.push({
            text        : _('help_ex'),
            handler     : MODx.loadHelpPane,
            scope       : this
        });
    }

    if (GoogleAnalytics.config.authorized_profile) {
        Ext.applyIf(config, {
            components  : [{
                xtype       : 'googleanalytics-panel-home',
                renderTo    : 'googleanalytics-panel-home-div'
            }]
        });
    } else {
        Ext.applyIf(config, {
            components  : [{
                xtype       : 'googleanalytics-panel-access',
                renderTo    : 'googleanalytics-panel-access-div'
            }]
        });
    }

    GoogleAnalytics.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.page.Home, MODx.Component, {
    loadBranding: function(btn) {
        window.open(GoogleAnalytics.config.branding_url);
    },
    loadGoogleAnalytics: function(btn) {
        window.open(GoogleAnalytics.config.google_analytics_url);
    },
    filterProfile: function(tf, nv, ov) {
        MODx.loadPage('home', 'namespace=' + MODx.request.namespace + '&profile=' +  tf.getValue());
    },
    createAuth: function(btn, e) {
        if (this.createAuthWindow) {
            this.createAuthWindow.destroy();
        }

        this.createAuthWindow = MODx.load({
            xtype       : 'googleanalytics-window-auth-create',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        window.location.reload();
                    },
                    scope       : this
                }
            }
        });

        this.createAuthWindow.show(e.target);
    },
    refreshSettings : function(btn, e) {
        MODx.msg.confirm({
            title       : _('googleanalytics.refresh_settings'),
            text        : _('googleanalytics.refresh_settings_confirm'),
            url         : GoogleAnalytics.config.connector_url,
            params      : {
                action      : 'mgr/settings/refresh'
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        window.location.reload();
                    },
                    scope       : this
                }
            }
        });
    },
    updateSettings: function(btn, e) {
        if (this.updateSettingsWindow) {
            this.updateSettingsWindow.destroy();
        }

        this.updateSettingsWindow = MODx.load({
            modal       : true,
            xtype       : 'googleanalytics-window-settings-update',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        window.location.reload();
                    },
                    scope       : this
                }
            }
        });

        this.updateSettingsWindow.show(e.target);
    },
});

Ext.reg('googleanalytics-page-home', GoogleAnalytics.page.Home);

GoogleAnalytics.window.UpdateAuth = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('googleanalytics.auth_create'),
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : 'mgr/settings/createauth'
        },
        fields      : [{
            html        : '<p>' + _('googleanalytics.auth_create_desc') + '</p>',
            cls         : 'panel-desc'
        }, {
            xtype       : 'button',
            text        : '<i class="icon icon-unlock"></i>' + _('googleanalytics.label_get_code'),
            cls         : 'primary-button',
            anchor      : '100%',
            handler     : this.getAuthorizePopup,
            scope       : this
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('googleanalytics.label_code'),
            description : MODx.expandHelp ? '' : _('googleanalytics.label_code_desc'),
            name        : 'code',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('googleanalytics.label_code_desc'),
            cls         : 'desc-under'
        }],
        saveBtnText : _('googleanalytics.auth_create')
    });

    GoogleAnalytics.window.UpdateAuth.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.window.UpdateAuth, MODx.Window, {
    getAuthorizePopup : function() {
        window.open(GoogleAnalytics.config.authorize_url, 'googleanalytics_authorize', 'height=500,width=450');
    }
});

Ext.reg('googleanalytics-window-auth-create', GoogleAnalytics.window.UpdateAuth);

GoogleAnalytics.window.UpdateSettings = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        width       : 600,
        autoHeight  : true,
        title       : _('googleanalytics.settings'),
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : 'mgr/settings/save'
        },
        fields      : [{
            layout      : 'column',
            defaults    : {
                layout      : 'form',
                labelSeparator : ''
            },
            items       : [{
                columnWidth : .5,
                items       : [{
                    xtype       : 'googleanalytics-combo-accounts',
                    fieldLabel  : _('googleanalytics.label_account'),
                    description : MODx.expandHelp ? '' : _('googleanalytics.label_account_desc'),
                    id          : 'google-analytics-setting-account',
                    name        : 'account',
                    anchor      : '100%',
                    value       : GoogleAnalytics.config.authorized_profile.account_id,
                    listeners   : {
                        change      : {
                            fn          : this.updateField,
                            scope       : this
                        }
                    }
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('googleanalytics.label_account_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'googleanalytics-combo-properties',
                    fieldLabel  : _('googleanalytics.label_property'),
                    description : MODx.expandHelp ? '' : _('googleanalytics.label_property_desc'),
                    id          : 'google-analytics-setting-property',
                    name        : 'property',
                    anchor      : '100%',
                    value       : GoogleAnalytics.config.authorized_profile.property_id,
                    params      : {
                        account     : GoogleAnalytics.config.authorized_profile.account_id
                    },
                    listeners   : {
                        change      : {
                            fn          : this.updateField,
                            scope       : this
                        }
                    }
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('googleanalytics.label_property_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'googleanalytics-combo-profiles',
                    fieldLabel  : _('googleanalytics.label_profile'),
                    description : MODx.expandHelp ? '' : _('googleanalytics.label_profile_desc'),
                    id          : 'google-analytics-setting-profile',
                    name        : 'profile',
                    anchor      : '100%',
                    value       : GoogleAnalytics.config.authorized_profile.id,
                    params      : {
                        account     : GoogleAnalytics.config.authorized_profile.account_id,
                        property    : GoogleAnalytics.config.authorized_profile.property_id
                    }
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('googleanalytics.label_profile_desc'),
                    cls         : 'desc-under'
                }]
            }, {
                columnWidth : .5,
                items       : [{
                    xtype       : 'googleanalytics-combo-history',
                    fieldLabel  : _('googleanalytics.label_history'),
                    description : MODx.expandHelp ? '' : _('googleanalytics.label_history_desc'),
                    name        : 'history',
                    anchor      : '100%',
                    value       : GoogleAnalytics.config.history
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('googleanalytics.label_history_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'numberfield',
                    fieldLabel  : _('googleanalytics.label_cache_lifetime'),
                    description : MODx.expandHelp ? '' : _('googleanalytics.label_cache_lifetime_desc'),
                    name        : 'cache_lifetime',
                    anchor      : '100%',
                    value       : GoogleAnalytics.config.cache_lifetime
                }, {
                    xtype       : MODx.expandHelp ? 'label' : 'hidden',
                    html        : _('googleanalytics.label_cache_lifetime_desc'),
                    cls         : 'desc-under'
                }, {
                    xtype       : 'checkboxgroup',
                    fieldLabel  : _('googleanalytics.label_panels'),
                    columns     : 2,
                    vertical    : false,
                    items       : [{
                        boxLabel    : _('googleanalytics.title_summary'),
                        inputValue  : 'summary',
                        name        : 'panels[]',
                        checked     : GoogleAnalytics.config.panels.indexOf('summary') !== -1
                    }, {
                        boxLabel    : _('googleanalytics.title_visitors'),
                        inputValue  : 'visitors',
                        name        : 'panels[]',
                        checked     : GoogleAnalytics.config.panels.indexOf('visitors') !== -1
                    }, {
                        boxLabel    : _('googleanalytics.title_sources'),
                        inputValue  : 'sources',
                        name        : 'panels[]',
                        checked     : GoogleAnalytics.config.panels.indexOf('sources') !== -1
                    }, {
                        boxLabel    : _('googleanalytics.title_content'),
                        inputValue  : 'content',
                        name        : 'panels[]',
                        checked     : GoogleAnalytics.config.panels.indexOf('content') !== -1
                    }, {
                        boxLabel    : _('googleanalytics.title_content_search'),
                        inputValue  : 'content_search',
                        name        : 'panels[]',
                        checked     : GoogleAnalytics.config.panels.indexOf('content_search') !== -1
                    }, {
                        boxLabel    : _('googleanalytics.title_goals'),
                        inputValue  : 'goals',
                        name        : 'panels[]',
                        checked     : GoogleAnalytics.config.panels.indexOf('goals') !== -1
                    }]
                }]
            }]
        }],
        buttonAlign : 'left',
        buttons     : [{
            text        : '<i class="icon icon-times"></i>' + _('googleanalytics.auth_remove'),
            handler     : this.removeAuth,
            scope       : this
        }, '->', {
            text        : _('cancel'),
            handler     : function() {
                config.closeAction !== 'close' ? this.hide() : this.close();
            },
            scope       : this
        }, {
            text        : _('save'),
            cls         : 'primary-button',
            handler     : this.submit,
            scope       : this
        }]
    });
    
    GoogleAnalytics.window.UpdateSettings.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.window.UpdateSettings, MODx.Window, {
    updateField: function(tf) {
        var name    = tf.name;
        var value   = tf.getValue();

        if (name === 'account') {
            var fields  = ['property', 'profile'];
        } else {
            var fields  = ['profile'];
        }

        fields.forEach(function(key, index) {
            var field = Ext.getCmp('google-analytics-setting-' + key);

            if (field) {
                field.clearValue();
                field.getStore().setBaseParam(name, value);

                if (index === 0) {
                    field.setDisabled(false);

                    field.getStore().load();
                } else {
                    field.setDisabled(true);
                }
            }
        });
    },
    removeAuth : function(btn, e) {
        MODx.msg.confirm({
            title       : _('googleanalytics.auth_remove'),
            text        : _('googleanalytics.auth_remove_confirm'),
            url         : GoogleAnalytics.config.connector_url,
                params      : {
                action      : 'mgr/settings/removeauth'
            },
            listeners   : {
                'success'   : {
                    fn          : function() {
                        window.location.reload();
                    },
                    scope       : this
                }
            }
        });
    },
});

Ext.reg('googleanalytics-window-settings-update', GoogleAnalytics.window.UpdateSettings);