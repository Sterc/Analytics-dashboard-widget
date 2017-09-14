Ext.onReady(function() {
	MODx.load({xtype: 'googleanalytics-page-home'});
});

GoogleAnalytics.page.Home = function(config) {
	config = config || {};
	
	config.buttons = [];

	if (GoogleAnalytics.config.branding_url) {
		config.buttons.push({
			text 		: 'GoogleAnalytics ' + GoogleAnalytics.config.version,
			cls			: 'x-btn-branding',
			handler		: this.loadBranding
		});
	}

	if (GoogleAnalytics.config.authorized_profile) {
		config.buttons.push({
			xtype 		: 'googleanalytics-combo-profiles',
			name 		: 'googleanalytics-filter-profiles',
	        id			: 'googleanalytics-filter-profiles',
	        emptyText	: _('googleanalytics.filter_profiles'),
	        value 		: MODx.request.profile || GoogleAnalytics.config.authorized_profile.id,
	        listeners	: {
		        'change'	: {
		        	fn			: this.filterProfile,
		        	scope		: this
		        }
		    },
		    baseParams 	: {
	        	action		: 'mgr/data/getprofiles',
	            account		: GoogleAnalytics.config.authorized_profile.account_id,
	            property	: GoogleAnalytics.config.authorized_profile.property_id
	        },
		    width		: 200
		});
	}
	
	config.buttons.push({
		text 		: _('googleanalytics.open_googleanalytics'),
		cls 		: 'primary-button',
		handler		: this.loadGoogleAnalytics
	});
	
	if (GoogleAnalytics.config.has_permission) {
        if (GoogleAnalytics.config.authorized) {
            config.buttons.push({
				text   	: _('googleanalytics.settings'),
				handler	: this.updateSettings
			}, {
                text	: _('googleanalytics.auth_revoke'),
                handler	: this.revokeAuth,
                scope	: this
            });
        } else {
            config.buttons.push({
				text   	: _('googleanalytics.auth'),
				handler	: this.updateAuth
			});
        }
	}

    //if (GoogleAnalytics.config.branding_url_help) {
    //    config.buttons.push({
    //        text   : _('help_ex'),
    //        handler: MODx.loadHelpPane,
    //        scope  : this
    //    });
    //}
	
	if (GoogleAnalytics.config.authorized_profile) {
		Ext.applyIf(config, {
			components	: [{
				xtype		: 'googleanalytics-panel-home',
				renderTo	: 'googleanalytics-panel-home-div'
			}]
		});
	} else {
		Ext.applyIf(config, {
			components	: [{
				xtype		: 'googleanalytics-panel-access',
				renderTo	: 'googleanalytics-panel-access-div'
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
		var request = MODx.request || {};
		
        Ext.apply(request, {
	    	'profile' : tf.getValue()  
	    });
        
        MODx.loadPage('?' + Ext.urlEncode(request));
	},
    updateAuth: function(btn, e) {
        if (this.updateAuthWindow) {
            this.updateAuthWindow.destroy();
        }

        this.updateAuthWindow = MODx.load({
			modal		: true,
			xtype		: 'googleanalytics-window-auth-update',
			closeAction	: 'close',
			listeners	: {
				'success'	: {
					fn			: function() {
						window.location.reload();
					},
					scope		: this
				}
			}
		});

        this.updateAuthWindow.show(e.target);
	},
    revokeAuth : function(btn, e) {
        MODx.msg.confirm({
			title 		: _('googleanalytics.auth_revoke'),
			text		: _('googleanalytics.auth_revoke_confirm'),
			url			: GoogleAnalytics.config.connector_url,
			params		: {
				action		: 'mgr/settings/revokeauth',
			},
			listeners	: {
				'success'	: {
                    fn			: function() {
                        window.location.reload();
                    },
                    scope		: this
				}
			}
		});
    },
	updateSettings: function(btn, e) {
        if (this.updateSettingsWindow) {
	        this.updateSettingsWindow.destroy();
        }
        
        this.updateSettingsWindow = MODx.load({
	        modal		: true,
	        xtype		: 'googleanalytics-window-settings-update',
	        closeAction	: 'close',
	        listeners	: {
		        'success'	: {
		        	fn			: function() {
			        	window.location.reload(); 
			        },
		        	scope		: this
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
        autoHeight	: true,
        title 		: _('googleanalytics.auth'),
        url			: GoogleAnalytics.config.connector_url,
        baseParams	: {
            action		: 'mgr/settings/auth'
        },
        fields		: [{
            xtype       : 'textfield',
            fieldLabel  : _('googleanalytics.label_code'),
            description : MODx.expandHelp ? '' : _('googleanalytics.label_code_desc'),
            name        : 'code',
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('googleanalytics.label_code_desc'),
            cls         : 'desc-under'
        }, {
            xtype		: 'button',
            text		: _('googleanalytics.auth_code'),
            anchor		: '100%',
            handler		: this.getAuthorizePopup,
            scope		: this
        }],
        saveBtnText	: _('googleanalytics.auth')
    });

    GoogleAnalytics.window.UpdateAuth.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.window.UpdateAuth, MODx.Window, {
    getAuthorizePopup : function() {
        window.open(GoogleAnalytics.config.authorize_url, 'googleanalytics_authorize', 'height=500,width=450');
    }
});

Ext.reg('googleanalytics-window-auth-update', GoogleAnalytics.window.UpdateAuth)

GoogleAnalytics.window.UpdateSettings = function(config) {
    config = config || {};

    Ext.applyIf(config, {
    	width		: 600,
    	autoHeight	: true,
        title 		: _('googleanalytics.settings'),
        url			: GoogleAnalytics.config.connector_url,
        baseParams	: {
            action		: 'mgr/settings/save'
        },
        fields		: [{
            layout		: 'column',
            border		: false,
            defaults	: {
                layout		: 'form',
                labelSeparator : ''
            },
            items		: [{
                columnWidth	: .5,
                items		: [{
                    xtype		: 'googleanalytics-combo-accounts',
                    fieldLabel	: _('googleanalytics.label_account'),
                    description	: MODx.expandHelp ? '' : _('googleanalytics.label_account_desc'),
                    id			: 'google-analytics-setting-account',
                    name		: 'account',
                    anchor		: '100%',
                    listeners	: {
                        'change'	: {
                            fn			: this.unLockPropertyField,
                            scope		: this
                        }
                    }
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('googleanalytics.label_account_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype		: 'googleanalytics-combo-properties',
                    fieldLabel	: _('googleanalytics.label_property'),
                    description	: MODx.expandHelp ? '' : _('googleanalytics.label_property_desc'),
                    id			: 'google-analytics-setting-property',
                    name		: 'property',
                    anchor		: '100%',
                    listeners	: {
                        'change'	: {
                            fn			: this.unLockProfileField,
                            scope		: this
                        }
                    }
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('googleanalytics.label_property_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype		: 'googleanalytics-combo-profiles',
                    fieldLabel	: _('googleanalytics.label_profile'),
                    description	: MODx.expandHelp ? '' : _('googleanalytics.label_profile_desc'),
                    id			: 'google-analytics-setting-profile',
                    name		: 'profile',
                    anchor		: '100%'
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('googleanalytics.label_profile_desc'),
                    cls			: 'desc-under'
                }]
            }, {
                columnWidth	: .5,
                style      	: 'margin-right: 0;',
                items      	: [{
                    xtype		: 'googleanalytics-combo-history',
                    fieldLabel	: _('googleanalytics.label_history'),
                    description	: MODx.expandHelp ? '' : _('googleanalytics.label_history_desc'),
                    name		: 'history',
                    anchor		: '100%',
                    value		: GoogleAnalytics.config.history
                }, {
                    xtype		: MODx.expandHelp ? 'label' : 'hidden',
                    html		: _('googleanalytics.label_history_desc'),
                    cls			: 'desc-under'
                }, {
                    xtype		: 'checkboxgroup',
                    fieldLabel	: _('googleanalytics.label_panels'),
                    columns		: 2,
                    vertical	: false,
                    items		: [{
                        boxLabel	: _('googleanalytics.title_summary'),
                        inputValue	: 'summary',
                        name		: 'panels[]',
                        checked		: -1 != GoogleAnalytics.config.panels.indexOf('summary')
                    }, {
                        boxLabel	: _('googleanalytics.title_visitors'),
                        inputValue	: 'visitors',
                        name		: 'panels[]',
                        checked		: -1 != GoogleAnalytics.config.panels.indexOf('visitors')
                    }, {
                        boxLabel	: _('googleanalytics.title_sources'),
                        inputValue	: 'sources',
                        name		: 'panels[]',
                        checked		: -1 != GoogleAnalytics.config.panels.indexOf('sources')
                    }, {
                        boxLabel	: _('googleanalytics.title_content'),
                        inputValue	: 'content',
                        name		: 'panels[]',
                        checked		: -1 != GoogleAnalytics.config.panels.indexOf('content')
                    }, {
                        boxLabel	: _('googleanalytics.title_content_search'),
                        inputValue  : 'content_search',
                        name		: 'panels[]',
                        checked		: -1 != GoogleAnalytics.config.panels.indexOf('content_search')
                    }, {
                        boxLabel	: _('googleanalytics.title_goals'),
                        inputValue  : 'goals',
                        name		: 'panels[]',
                        checked		: -1 != GoogleAnalytics.config.panels.indexOf('goals')
                    }]
                }]
            }]
		}]
    });
    
    GoogleAnalytics.window.UpdateSettings.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.window.UpdateSettings, MODx.Window, {
	unLockPropertyField: function() {
		var account = Ext.getCmp('google-analytics-setting-account').getValue();
		
		if (undefined !== (propertyField = Ext.getCmp('google-analytics-setting-property'))) {
			propertyField.getStore().setBaseParam('account', account);
			propertyField.getStore().load();

			propertyField.fireEvent('change');
			
			propertyField.reset();
		}
	},
	unLockProfileField: function() {
		var account = Ext.getCmp('google-analytics-setting-account').getValue();
		var property = Ext.getCmp('google-analytics-setting-property').getValue();

		if (undefined !== (profileField = Ext.getCmp('google-analytics-setting-profile'))) {
			profileField.getStore().setBaseParam('account', account);
			profileField.getStore().setBaseParam('property', property);
			profileField.getStore().load();
			
			profileField.fireEvent('change');
			
			profileField.reset();
		}
	}
});

Ext.reg('googleanalytics-window-settings-update', GoogleAnalytics.window.UpdateSettings);

GoogleAnalytics.combo.Accounts = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: GoogleAnalytics.config.connector_url,
        baseParams 	: {
            action		: 'mgr/data/getaccounts'
        },
        fields		: ['id', 'name'],
        hiddenName	: 'account',
        valueField	: 'id',
        displayField: 'name',
        editable	: true,
        typeAhead	: true
    });
    
    GoogleAnalytics.combo.Accounts.superclass.constructor.call(this,config);
};

Ext.extend(GoogleAnalytics.combo.Accounts, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-accounts', GoogleAnalytics.combo.Accounts);

GoogleAnalytics.combo.Properties = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: GoogleAnalytics.config.connector_url,
        baseParams 	: {
            action		: 'mgr/data/getproperties'
        },
        fields		: ['id', 'name'],
        hiddenName	: 'property',
        valueField	: 'id',
        displayField: 'name',
        editable	: true,
        typeAhead	: true,
    });
    
    GoogleAnalytics.combo.Properties.superclass.constructor.call(this,config);
};

Ext.extend(GoogleAnalytics.combo.Properties, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-properties', GoogleAnalytics.combo.Properties);

GoogleAnalytics.combo.Profiles = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: GoogleAnalytics.config.connector_url,
        baseParams 	: {
            action		: 'mgr/data/getprofiles'
        },
        fields		: ['id', 'name'],
        hiddenName	: 'profile',
        valueField	: 'id',
        displayField: 'name',
        editable	: true,
        typeAhead	: true
    });
    
    GoogleAnalytics.combo.Profiles.superclass.constructor.call(this,config);
};

Ext.extend(GoogleAnalytics.combo.Profiles, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-profiles', GoogleAnalytics.combo.Profiles);

GoogleAnalytics.combo.History = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
			mode	: 'local',
			fields	: ['type','label'],
			data	: [
				[7, _('googleanalytics.history_1')],
				[14, _('googleanalytics.history_2')],
				[21, _('googleanalytics.history_3')],
                [28, _('googleanalytics.history_4')]
			]
		}),
        remoteSort	: ['label', 'asc'],
        hiddenName	: 'history',
        valueField	: 'label',
        displayField: 'label',
        mode		: 'local',
        value		: 14
    });

    GoogleAnalytics.combo.History.superclass.constructor.call(this,config);
};

Ext.extend(GoogleAnalytics.combo.History, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-history', GoogleAnalytics.combo.History);

/*GoogleAnalytics.combo.PropertiesGrouped = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        url			: GoogleAnalytics.config.connector_url,
        baseParams 	: {
            action		: 'mgr/data/getproperties'
        },
        fields		: ['id', 'name', 'account_id', 'account_name', 'value'],
        hiddenName	: 'property',
        valueField	: 'value',
        displayField: 'name',
        editable	: true,
        typeAhead	: true,
        tpl			: new Ext.XTemplate('<tpl for=".">' +
    			'<tpl if="!this.isHeader(values.account_id)">' +
    				'<div class="x-combo-list-group">'	+
						'<div class="x-combo-list-item">{name}</div>' + 
					'</div>' +
				'</tpl>' +
				'<tpl if="this.isHeader(values.account_id)">' +
					'<div class="x-combo-list-header">{[this.getHeader(values.account_id, values.account_name)]}</div>' +
					'<div class="x-combo-list-group">' +
						'<div class="x-combo-list-item">{name}</div>' + 
					'</div>' +
				'</tpl>' +
			'</tpl>',
			{
				isHeader: function(header) {
					return this.header != header;
				},
				getHeader: function(header, label) {
					this.header = header;
					
					return label;
				}
			}
		)
    });
    
    GoogleAnalytics.combo.PropertiesGrouped.superclass.constructor.call(this,config);
};

Ext.extend(GoogleAnalytics.combo.PropertiesGrouped, MODx.combo.ComboBox);

Ext.reg('googleanalytics-combo-properties-grouped', GoogleAnalytics.combo.PropertiesGrouped);*/