GoogleAnalytics.panel.Home = function(config) {
	config = config || {};

    Ext.apply(config, {
        id			: 'googleanalytics-panel-home',
        cls			: 'container',
        items		: [{
            html		: '<h2>'+_('googleanalytics')+'</h2>',
            id			: 'googleanalytics-header',
            cls			: 'modx-page-header'
        }, {
            layout		: 'form',
            items		: [{
                html		: '<div class="google-analytics-summary">' +
					'<div id="google-analytics-realtime"></div>' +
					'<div class="google-analytics-data">' +
						'<strong>' + GoogleAnalytics.config.authorized_profile.url + '</strong>' +
						'<span>' + GoogleAnalytics.config.authorized_profile.property_id + '</span>' +
					'</div>' +
				'</div>' +
				'<p>' + _('googleanalytics.stats_desc') + '</p>',
                bodyCssClass	: 'panel-desc google-analytics-description'
            }, {
                xtype		: 'modx-vtabs',
                items		: this.getPanels()
            }]
        }],
        listeners	: {
	        'afterrender' : {
		        fn 		: function() {
			        this.setRealTimeData();
			        
			    	setInterval((this.setRealTimeData).bind(this), 5000);
		        },
		        scope	: this
	        }
        }
    });

	GoogleAnalytics.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Home, MODx.FormPanel, {
	getPanels: function() {
		var panels = [];
	
		var availablePanels = {
			summary 		: 'googleanalytics-panel-summary',
			visitors		: 'googleanalytics-panel-visitors',
			sources			: 'googleanalytics-panel-sources',
			content			: 'googleanalytics-panel-content',
			content_search	: 'googleanalytics-panel-content-search',
            goals			: 'googleanalytics-panel-goals'
		};

        for (var key in availablePanels) {
        	if (-1 !== GoogleAnalytics.config.panels.indexOf(key)) {
                panels.push({
					xtype : availablePanels[key]
				});
			}
        }
		
		return panels;
	},
	setRealTimeData: function() {
		MODx.Ajax.request({
            url			: GoogleAnalytics.config.connector_url,
            params		: {
            	action 		: 'mgr/getdata',
            	profile		: GoogleAnalytics.config.authorized_profile.id,
				data		: 'realtime'
            },
            listeners	: {
                'success'	: {
	                fn			: function(data) {
			            if (undefined !== (realtime = Ext.get(Ext.query('#google-analytics-realtime')))) {
				            if (data.results[0]) {
					            realtime.update(
						        	String.format('<span class="google-analytics-realtime-amount">{0}</span><span class="google-analytics-realtime-description">{1}</span>', data.results[0].activeUsers, (1 == data.results[0].activeUsers ? _('googleanalytics.online_visitor') : _('googleanalytics.online_visitors'))) 
					            );
					        } else {
						       realtime.update(
						        	String.format('<span class="google-analytics-realtime-amount">{0}</span><span class="google-analytics-realtime-description">{1}</span>', '0', _('googleanalytics.online_visitors')) 
					            ); 
					        }
				        }
		            },
					scope 		: this
                }
            }
        });
	}
});

Ext.reg('googleanalytics-panel-home', GoogleAnalytics.panel.Home);

GoogleAnalytics.panel.Summary = function(config) {
    config = config || {};

    Ext.apply(config, {
		title		:  _('googleanalytics.title_summary'),
		items		: [{
			xtype		: 'panel',
			title		: '<h2>' + _('googleanalytics.title_block_summary', {
				history		: GoogleAnalytics.config.history
			}) + '</h2>',
			items		: [{
        		xtype		: 'googleanalytics-line-chart',
				height		: 300,
				pieConfig	: {
        			params		: {
                        data		: 'visits'
                    },
                    fields		: ['date', 'date_short', 'date_long', 'visits', 'pageviews'],
                    nameField	: 'date_long',
                    dateField	: 'date',
                    series		: [{
	                    name		: _('googleanalytics.visitors'),
	                	dateField	: 'visits'
                    }, {
        				name		: _('googleanalytics.pageviews'),
						dateField	: 'pageviews'
					}]
				}
			}]
        }, {			
			xtype		: 'googleanalytics-panel-meta'
		}]
	});

    GoogleAnalytics.panel.Summary.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Summary, MODx.Panel);

Ext.reg('googleanalytics-panel-summary', GoogleAnalytics.panel.Summary);

GoogleAnalytics.panel.Visitors = function(config) {
    config = config || {};

    Ext.apply(config, {
		title		: _('googleanalytics.title_visitors'),
		items		: [{
        	layout		: 'column',
        	border		: false,
        	items		: [{
	        	columnWidth	: .5,
	        	title		: '<h2>' + _('googleanalytics.title_block_visitors') + '</h2>',
	        	items		: [{
	        		xtype		: 'googleanalytics-pie-chart',
					height		: 250,
					pieConfig	: {
	        			params		: {
                            data		: 'visiters'
                        },
                        fields		: ['visitorType', 'visits'],
                        nameField	: 'visitorType',
                        dataField	: 'visits'
					}
				}]
	        }, {
		        columnWidth	: .5,
		        title		: '<h2>' + _('googleanalytics.title_block_language') + '</h2>',
		        style		: 'margin-right: 0;',
		        items		: [{
	        		xtype		: 'googleanalytics-pie-chart',
					height		: 250,
					pieConfig	: {
	        			params		: {
                            data		: 'language'
                        },
                        fields		: ['language', 'visits', 'visitors', 'pageviews'],
                        nameField	: 'language',
                        dataField	: 'visitors'
					}
				}]
		    }]
	    }, {			
			xtype		: 'panel',
			title		: '<h2>' + _('googleanalytics.title_visitors') + '</h2>',
			items		: [{
				xtype		: 'googleanalytics-grid-visitors'
			}]
		}]
	});

    GoogleAnalytics.panel.Visitors.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Visitors, MODx.Panel);

Ext.reg('googleanalytics-panel-visitors', GoogleAnalytics.panel.Visitors);

GoogleAnalytics.panel.Sources = function(config) {
    config = config || {};

    Ext.apply(config, {
		title		: _('googleanalytics.title_sources'),
		items		: [{
        	layout		: 'column',
        	border		: false,
        	items		: [{
	        	columnWidth	: .5,
	        	title		: '<h2>' + _('googleanalytics.title_block_sources') + '</h2>',
				items		: [{
	        		xtype		: 'googleanalytics-pie-chart',
					height		: 250,
					pieConfig	: {
	        			params		: {
                            data		: 'sources-summary'
                        },
                        fields		: ['name', 'visits'],
                        nameField	: 'name',
                        dataField	: 'visits'
					}
				}]
	        }, {
		        columnWidth	: .5,
		        title		: '<h2>' + _('googleanalytics.title_block_devices') + '</h2>',
		        style		: 'margin-right: 0;',
		        items		: [{
	        		xtype		: 'googleanalytics-pie-chart',
					height		: 250,
					pieConfig	: {
	        			params		: {
                            data		: 'devices'
                        },
                        fields		: ['deviceCategory', 'visits'],
                        nameField	: 'deviceCategory',
                        dataField	: 'visits'
					}
				}]
	        }]	
	    }, {			
			xtype		: 'panel',
			title		: '<h2>' + _('googleanalytics.title_sources') + '</h2>',
			items		: [{
				xtype		: 'googleanalytics-grid-sources'
			}]
		}]
	});

    GoogleAnalytics.panel.Sources.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Sources, MODx.Panel);

Ext.reg('googleanalytics-panel-sources', GoogleAnalytics.panel.Sources);

GoogleAnalytics.panel.Content = function(config) {
    config = config || {};

    Ext.apply(config, {
		title		: _('googleanalytics.title_content'),
		items		: [{			
			xtype		: 'panel',
			title		: '<h2>' + _('googleanalytics.title_block_content_high') + '</h2>',
			items		: [{
				xtype		: 'googleanalytics-grid-content-high'
			}]
		}, {
			html 		: '<br />'	
		}, {			
			xtype		: 'panel',
			title		: '<h2>' + _('googleanalytics.title_block_content_low') + '</h2>',
			items		: [{
				xtype		: 'googleanalytics-grid-content-low'
			}]
		}]
	});

    GoogleAnalytics.panel.Content.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Content, MODx.Panel);

Ext.reg('googleanalytics-panel-content', GoogleAnalytics.panel.Content);

GoogleAnalytics.panel.ContentSearch = function(config) {
    config = config || {};

    Ext.apply(config, {
		title		: _('googleanalytics.title_content_search'),
		items		: [{			
			xtype		: 'panel',
			title		: '<h2>' + _('googleanalytics.title_block_content_search') + '</h2>',
			items		: [{
				xtype		: 'googleanalytics-grid-content-search'
			}]
		}]
	});

    GoogleAnalytics.panel.ContentSearch.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.ContentSearch, MODx.Panel);

Ext.reg('googleanalytics-panel-content-search', GoogleAnalytics.panel.ContentSearch);

GoogleAnalytics.panel.Goals = function(config) {
    config = config || {};

    Ext.apply(config, {
        title		:  _('googleanalytics.title_goals'),
        items		: [{
            xtype		: 'panel',
            title		: '<h2>' + _('googleanalytics.title_block_goals') + '</h2>',
            items		: [{
                xtype		: 'googleanalytics-grid-goals'
            }]
        }]
    });

    GoogleAnalytics.panel.Goals.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Goals, MODx.Panel);

Ext.reg('googleanalytics-panel-goals', GoogleAnalytics.panel.Goals);

GoogleAnalytics.panel.Meta = function(config) {
    config = config || {};   
     
    this.tpl = new Ext.XTemplate('<tpl>' + 
        '<div class="google-analytics-metas">' + 
        	'<tpl if="results.length">' +
        		'<h2>' + _('googleanalytics.title_block_meta', {
	        		history : GoogleAnalytics.config.history
        		}) + '</h2>' +
        	'</tpl>' +
			'<table cellspacing="0" cellpadding="0">' +
        		'<tbody>' +
		            '<tpl for="results">' +
		                '<tr>' +
		                    '<tpl for=".">' +
		                        '<td>' +
		                            '<div class="google-analytics-meta">' +
		                            	'<span class="google-analytics-meta-value">{value}</span>' +
		                            	'<span class="google-analytics-meta-label">{name}</span>' +
		                            	'<span class="google-analytics-meta-progress">' +
		                            		'{[this.renderProgress(values.progress)]}' +
											'<span>{[this.renderPercent(values.progress)]}</span>' +
										'</span>' + 
									'</div>' +
		                        '</td>' +
		                    '</tpl>' +
		                '</tr>' +
		            '</tpl>' +
				'</tbody>' +
			'</table>' +
		'</div>' +
    '</tpl>', {
        compiled : true,
        renderProgress: function(d) {
	        if (0 < d) {
		        return '<i class="icon icon-caret-up green"></i>';
	        } else if (0 > d) {
		        return '<i class="icon icon-caret-down red"></i>';
	        }
        },
        renderPercent: function(d) {
    		return Ext.util.Format.number(d, '0,0') + '%';
    	}
    });
    
	MODx.Ajax.request({
        url			: GoogleAnalytics.config.connector_url,
        params		: {
        	action 		: 'mgr/getdata',
        	profile		: GoogleAnalytics.config.authorized_profile.id,
			data		: 'meta-summary'
        },
        listeners	: {
            'success'	: {
                fn			: function(data) {
		        	this.setData(data);
	            },
				scope 		: this
            }
        }
    });
    
	GoogleAnalytics.panel.Meta.superclass.constructor.call(this,config);
};

Ext.extend(GoogleAnalytics.panel.Meta, Ext.Panel, {
    setData: function(data) {      
        this.tpl.overwrite(this.body, data);
    }
});

Ext.reg('googleanalytics-panel-meta', GoogleAnalytics.panel.Meta);