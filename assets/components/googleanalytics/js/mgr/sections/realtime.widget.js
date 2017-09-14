Ext.onReady(function() {
	MODx.load({xtype: 'googleanalytics-widget-realtime'});
});

GoogleAnalytics.panel.WidgetRealTime = function(config) {
	config = config || {};
	
	Ext.applyIf(config, {
		renderTo	: 'googleanalytics-widget-realtime-div',
		id			: 'google-analytics-realtime',
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
	
	GoogleAnalytics.panel.WidgetRealTime.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.WidgetRealTime, MODx.Panel, {
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
						        	String.format('<span class="google-analytics-realtime-description">{0}</span><span class="google-analytics-realtime-amount">{1}</span><span class="google-analytics-realtime-description">{2}</span>', _('googleanalytics.online_now'), data.results[0].activeUsers, (1 == data.results[0].activeUsers ? _('googleanalytics.online_visitor') : _('googleanalytics.online_visitors'))) 
					            );
					        } else {
						       realtime.update(
						        	String.format('<span class="google-analytics-realtime-description">{0}</span><span class="google-analytics-realtime-amount">{1}</span><span class="google-analytics-realtime-description">{2}</span>', _('googleanalytics.online_now'), '0', _('googleanalytics.online_visitors')) 
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

Ext.reg('googleanalytics-widget-realtime', GoogleAnalytics.panel.WidgetRealTime);