var activeTabs = Ext.decode(MODx.config.analytics_activetabs);

Ext.chart.Chart.CHART_URL=GA.assets_url+'swf/charts.swf';


MODx.panel.GADashboardWidget = function(config) {
    config = config || {};
    Ext.applyIf(config,{
    cls: 'vertical-tabs-panel'
        ,xtype: 'modx-vtabs'
        ,renderTo: 'analytics-panel-widget'
        ,id: 'ga-widget-tabs'
        ,activeTab: 0
        ,border:false
        ,autoWidth: true
        ,resizable: true
        ,monitorResize:true
        ,deferredRender: false
        ,defaults: {
			bodyCssClass: 'vertical-tabs-body'
            ,autoScroll: true
            ,autoHeight: true
            ,autoWidth: true
			,layout: 'form'
		}
        		,bwrapCfg: { tag: 'div', cls: 'x-tab-panel-bwrap vertical-tabs-bwrap' }
        ,headerCfg: {
            tag: 'div'
            ,cls: 'x-tab-panel-header vertical-tabs-header'
            ,id: 'modx-resource-vtabs-header'
        }
        ,items:[
            {xtype:'ga-tab-visitors' ,id:'ga-tab-visitors' ,title:_('analytics.visitors')}
            ,{xtype:'ga-tab-traffic-sources' ,id:'ga-tab-traffic-sources' ,title: _('analytics.traffic_sources')}
            ,{contentEl:'tab3' ,id:'ga-tab-top-content' ,title: _('analytics.top_content')}
            ,{xtype: 'ga-tab-goals' ,id:'ga-tab-goals' ,title: _('analytics.goals')}
            ,{contentEl:'tab5' ,id:'ga-tab-keywords' ,title: _('analytics.keywords')}
            ,{contentEl:'tab6' ,id:'ga-tab-sitesearch' ,title: _('analytics.site_search')}
            ,{xtype: 'ga-tab-settings' ,id:'ga-tab-settings' ,title: _('analytics.settings')}
        ]
    });
    MODx.panel.GADashboardWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.GADashboardWidget,Ext.TabPanel);
Ext.reg('ga-panel-dashboard-widget',MODx.panel.GADashboardWidget);




MODx.panel.GATabVisitors = function(config) {
    config = config || {};
	var visitors = new Ext.data.JsonStore({
	  url: GA.connector_url+'?action=data&data=visits&format=json'
	  ,fields: ['date','visits','pageviews']
	});
	visitors.load({params: {id: "1"}});

    Ext.applyIf(config,{
        border: false
        ,items: [{
            xtype: 'columnchart'
            ,height: 150
            ,store: visitors
            ,url:GA.assets_url+'swf/charts.swf'
            ,xField: 'date'
            ,yAxis: new Ext.chart.NumericAxis({
                displayName: 'Visits',
                labelRenderer : Ext.util.Format.numberRenderer('0,0')
            })
            ,tipRenderer : function(chart, record, index, series){
                if(series.yField == 'visits'){
                    return record.data.visits + ' visits on ' + record.data.date;
                }else{
                    return record.data.pageviews + ' page views on ' + record.data.date;
                }
            }
            ,chartStyle: {
                        legend: {
                            display: 'right',
                            padding: 5,
                            font: {
                                family: 'Tahoma',
                                size: 13
                            }
                        },
                    
                animationEnabled: true,
                dataTip: {
                    padding: 5,
                    border: {
                        color: 0x99bbe8,
                        size:2
                    },
                    background: {
                        color: 0xFFFFFF,
                        alpha: .9
                    },
                    font: {
                        name: 'Tahoma',
                        color: 0x15428B,
                        size: 10,
                        bold: true
                    }
                },
                xAxis: {
                    color: 0x69aBc8,
                    majorGridLines: {size: 1, color: 0xeeeeee}
                },
                yAxis: {
                    color: 0x69aBc8,
                    majorGridLines: {size: 1, color: 0xdfe8f6}
                }
            },
            series: [{
                type: 'line',
                displayName: 'Page Views',
                yField: 'pageviews',
                style: {
                    color:0x0172ce

                }
            },{
                type:'line',
                displayName: 'Visits',
                yField: 'visits',
                style: {
                    color: 0x6cb1e8
                }
            }]
        },{
        	border: false
            ,contentEl: 'tab1-holder'
        }]
        
    });
    MODx.panel.GATabVisitors.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.GATabVisitors,Ext.Panel);
Ext.reg('ga-tab-visitors',MODx.panel.GATabVisitors);

MODx.panel.GATabTrafficSources = function(config) {
	var traffic = new Ext.data.JsonStore({
	  url: GA.connector_url+'?action=data&data=trafficsourceschararr&format=json',
	  fields: ['name','visits']
	});
	traffic.load({params: {id: "1"}});

	var devices = new Ext.data.JsonStore({
	  url: GA.connector_url+'?action=data&data=devices&format=json',
	  fields: ['operatingSystem','visits']
	});
	devices.load({params: {id: "1"}});

    var mobile = new Ext.data.JsonStore({
      url: GA.connector_url+'?action=data&data=mobile&format=json',
      fields: ['isMobile','visits']
    });
    mobile.load({params: {id: "1"}});

	
    config = config || {};
    Ext.applyIf(config,{
        border: false
        ,items: [{
            layout: 'column'
            ,border: false
            ,items: [{
				border: false
                ,items: [{
                    url:GA.assets_url+'swf/charts.swf'
                    ,title: _('analytics.traffic_char_header')
                    ,border: false
                    ,width: 410
                    ,height: 200
                    ,items: {
                        store: traffic,
                        xtype: 'piechart',
                        dataField: 'visits',
                        categoryField: 'name',
                        series: [{
                            style: {
                                colors: ["#018bc9", "#49b629", "#f15906", "#eef200"]
                            }
                        }]
                        ,extraStyle: {
                            legend: {
                                display: 'right',
                                padding: 5,
                                font: {
                                    family: 'Tahoma',
                                    size: 13
                                }
                            }
                        }
                    }
                }]
        },{
				border: false
                ,items: [{
                    url:GA.assets_url+'swf/charts.swf'
                    ,title: _('analytics.platform_char_header')
                    ,border: false
                    ,width: 410
                    ,height: 200
                    ,items: {
                        store: devices,
                        xtype: 'piechart',
                        dataField: 'visits',
                        categoryField: 'operatingSystem',
                        series: [{
                            style: {
                                colors: ["#018bc9", "#49b629", "#f15906", "#eef200"]
                            }
                        }]
                        ,extraStyle: {
                            legend: {
                                display: 'right',
                                padding: 5,
                                font: {
                                    family: 'Tahoma',
                                    size: 13
                                }
                            }
                        }
                    }
                }]
        },{ 
            url:GA.assets_url+'swf/charts.swf'
            ,title: _('analytics.mobile_char_header')
            ,border: false
            ,width: 410
            ,height: 200
	        ,items: {
	            store: mobile
	            ,xtype: 'piechart'
	            ,dataField: 'visits'
	            ,categoryField: 'isMobile'
	            ,series: [{
                    style: {
                        colors: ["#018bc9", "#49b629", "#f15906", "#eef200"]
                    }
                }]
                ,extraStyle: {
	                legend: {
	                    display: 'right',
	                    padding: 5,
	                    font: {
	                        family: 'Tahoma',
	                        size: 13
	                    }
	                }
	            }
	        }
	        }]
        },{
            contentEl: 'tab2-holder'
            ,border: false
        }]
    });
    MODx.panel.GATabTrafficSources.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.GATabTrafficSources,Ext.Panel);
Ext.reg('ga-tab-traffic-sources',MODx.panel.GATabTrafficSources);


MODx.panel.GATabSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'form'
        ,id: 'settingsform'
        ,cls: 'container form-with-labels'
        ,labelWidth: 300
        ,autoHeight: true
        ,defaults: {
            anchor: '100%'
            ,msgTarget: 'under'
        }
        ,items: [{
		    xtype: 'ga-combo-days-amount'
		    ,fieldLabel: _('analytics.select_days')
            ,value: MODx.config.analytics_days
        },{
		    xtype: 'ga-combo-cache-time'
		    ,fieldLabel: _('analytics.cachingtime')
            ,value: MODx.config.analytics_cachingtime
		},{
		    xtype: 'ga-combo-site-profile'
		    ,fieldLabel: _('analytics.select_profile')
		    ,value: MODx.config.analytics_sitename
		},{
                xtype: 'panel'
                ,border: false
                ,layout: 'form'
                ,labelWidth: 100
                ,fieldLabel: _('analytics.available_tabs')
                ,items: [{
                			xtype: 'checkbox'
                			,fieldLabel: _('analytics.visitors')
                			,name: 'showvisitors'
        			        ,inputValue: 1
        			        ,checked: activeTabs['visitors']
        			        ,handler: function() {
        			        	activeTabs['visitors'] = this.getValue();
								MODx.gaUpdateSetting('analytics_activetabs',Ext.encode(activeTabs));
			                }
                		},{
                			xtype: 'checkbox'
                			,fieldLabel: _('analytics.traffic_sources')
                			,name: 'showtrafficsources'
        			        ,inputValue: 1
        			        ,checked: activeTabs['traffic-sources']
        			        ,handler: function() {
        			        	activeTabs['traffic-sources'] = this.getValue();
								MODx.gaUpdateSetting('analytics_activetabs',Ext.encode(activeTabs));
			                }
                		},{
                			xtype: 'checkbox'
                			,fieldLabel: _('analytics.top_content')
                			,name: 'showtopcontent'
        			        ,inputValue: 1
        			        ,checked: activeTabs['top-content']
        			        ,handler: function() {
        			        	activeTabs['top-content'] = this.getValue();
								MODx.gaUpdateSetting('analytics_activetabs',Ext.encode(activeTabs));
			                }
                		},{
                			xtype: 'checkbox'
                			,fieldLabel: _('analytics.goals')
                			,name: 'showgoals'
        			        ,inputValue: 1
        			        ,checked: activeTabs['goals']
        			        ,handler: function() {
        			        	activeTabs['goals'] = this.getValue();
								MODx.gaUpdateSetting('analytics_activetabs',Ext.encode(activeTabs));
			                }        			        
                		},{
                			xtype: 'checkbox'
                			,fieldLabel: _('analytics.keywords')
                			,name: 'showkeywords'
        			        ,inputValue: 1
        			        ,checked: activeTabs['keywords']
        			        ,handler: function() {
        			        	activeTabs['keywords'] = this.getValue();
								MODx.gaUpdateSetting('analytics_activetabs',Ext.encode(activeTabs));
			                }        			        
                		},{
                			xtype: 'checkbox'
                			,fieldLabel: _('analytics.site_search')
                			,name: 'showsitesearch'
        			        ,inputValue: 1
        			        ,checked: activeTabs['sitesearch']
        			        ,handler: function() {
        			        	activeTabs['sitesearch'] = this.getValue();
								MODx.gaUpdateSetting('analytics_activetabs',Ext.encode(activeTabs));
			                }        			        
                		}]
            }]
    });
    MODx.panel.GATabSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.GATabSettings,Ext.Panel);
Ext.reg('ga-tab-settings',MODx.panel.GATabSettings);

MODx.panel.GATabGoals = function(config) {
    config = config || {};
	 var goals = new Ext.data.JsonStore({
	  url: GA.connector_url+'?action=data&data=goals&format=json',
	  fields: ['date','oalCompletionsAll']
	});
	goals.load({params: {id: "1"}});
    Ext.applyIf(config,{
        border: false
        ,items: [{
            url:GA.assets_url+'swf/charts.swf'
            ,store: goals
            ,height: 150
            ,xtype: 'columnchart'
            ,xField: 'date'
            ,tipRenderer : function(chart, record, index, series){
                return Ext.util.Format.number(record.data.oalCompletionsAll, '0,0') + ' goal completions';
            }
            ,chartStyle: {
                animationEnabled: true,
                dataTip: {
                    padding: 5,
                    border: {
                        color: 0x99bbe8,
                        size:1
                    },
                    background: {
                        color: 0xDAE7F6,
                        alpha: .9
                    },
                    font: {
                        name: 'Tahoma',
                        color: 0x15428B,
                        size: 10,
                        bold: true
                    }
                }
            }
            ,series: [{
                type: 'line',
                displayName: 'date',
                yField: 'oalCompletionsAll',
                style: {
                    color:0x0172ce
                }
            }]
        },{
            contentEl: 'goals-holder'
            ,border: false
        }]
    });
    MODx.panel.GATabGoals.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.GATabGoals,Ext.Panel);
Ext.reg('ga-tab-goals',MODx.panel.GATabGoals);

MODx.gaUpdateSetting = function (setting,value){
    Ext.Ajax.request({
        url : GA.connector_url+'?action=settings' ,
        params : { setting : setting, value: value },
        method: 'POST',
        success: function () {
                Ext.MessageBox.show({
                   title: 'Please wait',
                   msg: 'Saving data...',
                   width:300,
                   progress:false,
                   closable:false
               });
            window.location.reload();
        }
    });
};

MODx.combo.GADaysAmount = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'comboboxdays'
        ,width:250
        ,typeAhead: true
        ,triggerAction: 'all'
        ,emptyText: 'select amount of days'
        ,mode: 'local'
        ,store: new Ext.data.ArrayStore({
            id: 1,
            fields: [
                'days'
            ],
            data: [[3], [4], [5], [6], [7], [8], [9], [10], [11], [12], [13], [14], [15], [16], [17], [18], [19], [20], [21], [22], [23], [24], [25], [26], [27], [28], [29], [30]]
        })
         ,listeners: {
            select: function(combo, record, index) {
                MODx.gaUpdateSetting('analytics_days',record.data.days);
            }
          }
        ,valueField: 'days'
        ,displayField: 'days'
        ,editable: false
    });
    MODx.combo.GADaysAmount.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.GADaysAmount,Ext.form.ComboBox);
Ext.reg('ga-combo-days-amount',MODx.combo.GADaysAmount);

MODx.combo.GACacheTime = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'comboboxcache'
        ,anchor: '100%'
        ,typeAhead: true
        ,triggerAction: 'all'
        ,emptyText: 'select cachingtime in minutes'
        ,mode: 'local'
        ,editable: false
        ,store: new Ext.data.ArrayStore({
            id: 1,
            fields: [
                'seconds','minutes'
            ],
            data: [[600,'10 min'], [1200,'20 min'], [1800,'30 min'], [2400,'40 min'], [3000,'50 min'], [3600,'60 min']]
        })
        ,listeners: {
           select: function(combo, record, index) {
               MODx.gaUpdateSetting('analytics_cachingtime',record.data.seconds);
           }
        }
        ,valueField: 'seconds'
        ,displayField: 'minutes'
    });
    MODx.combo.GACacheTime.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.GACacheTime,Ext.form.ComboBox);
Ext.reg('ga-combo-cache-time',MODx.combo.GACacheTime);


MODx.combo.GASiteProfiles = function(config) {
    config = config || {};

	var profiles = new Ext.data.JsonStore({
	  url: GA.connector_url+'?action=data&data=profiles&format=json',
	  fields: ['title','entryid','tableId','accountId','webPropertyId']
	});
	profiles.load({params: {id: "1"}});
    Ext.applyIf(config,{
        id: 'comboboxprofiles'
        ,width:250
        ,typeAhead: true
        ,triggerAction: 'all'
        ,emptyText: 'select site profile'
        ,mode: 'local'
        ,store: profiles
        ,editable: false
        ,listeners: {
            select: function(combo, record, index) {
                 MODx.gaUpdateSetting('analytics_sitename',record.data.title);
                 MODx.gaUpdateSetting('analytics_accountId',record.data.accountId);
                 MODx.gaUpdateSetting('analytics_profileId',record.data.tableId);
                 MODx.gaUpdateSetting('analytics_webPropertyId',record.data.webPropertyId);
            }
        }
        ,valueField: 'title'
        ,displayField: 'title'
    });
    MODx.combo.GASiteProfiles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.GASiteProfiles,Ext.form.ComboBox);
Ext.reg('ga-combo-site-profile',MODx.combo.GASiteProfiles);

Ext.onReady(function() {
	var tabs = MODx.load({
	    xtype: 'ga-panel-dashboard-widget'
    });
    
for(var index in activeTabs) {
  if(activeTabs[index] == false){
  	Ext.getCmp('ga-widget-tabs').remove('ga-tab-'+index);
  }
}
    
});