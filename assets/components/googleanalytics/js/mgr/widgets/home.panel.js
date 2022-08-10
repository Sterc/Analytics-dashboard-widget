GoogleAnalytics.panel.Home = function(config) {
    config = config || {};

    Ext.apply(config, {
        id          : 'googleanalytics-panel-home',
        cls         : 'container',
        items       : [{
            html        : '<h2>' + _('googleanalytics') + '</h2>',
            cls         : 'modx-page-header'
        }, {
            layout      : 'form',
            items       : [{
                bodyCssClass    : 'panel-desc',
                items           : [{
                    layout      : 'column',
                    items       : [{
                        columnWidth : .6,
                        items       : [{
                            html        : '<p>' + _('googleanalytics.stats_desc') + '</p>'
                        }]
                    }, {
                        columnWidth : .4,
                        cls         : 'googleanalytics-summary',
                        items       : [{
                            layout      : 'column',
                            items       : [{
                                columnWidth : .4,
                                items       : [{
                                    autoEl      : {
                                        tag         : 'div',
                                        html        : '<span class="googleanalytics-summary-total" id="google-analytics-realtime">N/A</span><span class="googleanalytics-summary-visitors">' + _('googleanalytics.online_visitors') + '</span>'
                                    }
                                }]
                            }, {
                                columnWidth : .6,
                                items       : [{
                                    autoEl      : {
                                        tag         : 'div',
                                        html        : '<span class="googleanalytics-summary-url">' + GoogleAnalytics.config.authorized_profile.url + '</span>'
                                    }
                                }, {
                                    autoEl      : {
                                        tag         : 'div',
                                        html        : '<span class="googleanalytics-summary-ua">' + GoogleAnalytics.config.authorized_profile.property_id + '</span>'
                                    }
                                }]
                            }]
                        }]
                    }]
                }]
            }, {
                xtype       : 'modx-vtabs',
                items       : this.getPanels()
            }]
        }],
        listeners   : {
            'afterrender' : {
                fn          : function() {
                    this.setRealTimeData();

                    setInterval((this.setRealTimeData).bind(this), 5000);
                },
                scope       : this
            }
        }
    });

    GoogleAnalytics.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Home, MODx.FormPanel, {
    getPanels: function() {
        var output = [];

        var panels = {
            summary         : 'googleanalytics-panel-summary',
            visitors        : 'googleanalytics-panel-visitors',
            sources         : 'googleanalytics-panel-sources',
            content         : 'googleanalytics-panel-content',
            content_search  : 'googleanalytics-panel-content-search',
            goals           : 'googleanalytics-panel-goals'
        };

        GoogleAnalytics.config.panels.forEach(function (value) {
            output.push({
                xtype : panels[value]
            });
        });

        return output;
    },
    setRealTimeData: function() {
        MODx.Ajax.request({
            url         : GoogleAnalytics.config.connector_url,
            params      : {
                action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
                profile     : GoogleAnalytics.config.authorized_profile.id,
                data        : 'realtime'
            },
            listeners   : {
                'success'   : {
                    fn          : function(data) {
                        var realtime = Ext.get('google-analytics-realtime');

                        if (realtime) {
                            if (data.results[0]) {
                                realtime.update(data.results[0].activeUsers || '0');
                            } else {
                                realtime.update('0');
                            }
                        }
                    },
                    scope       : this
                }
            }
        });
    }
});

Ext.reg('googleanalytics-panel-home', GoogleAnalytics.panel.Home);

GoogleAnalytics.panel.Summary = function(config) {
    config = config || {};

    Ext.apply(config, {
        title       : '<i class="icon icon-bar-chart"></i>' +  _('googleanalytics.title_summary'),
        items       : [{
            title       : '<h2>' + _('googleanalytics.title_block_summary', {
                history     : GoogleAnalytics.config.history,
                date_1      : GoogleAnalytics.config.dates.date_1_formatted,
                date_2      : GoogleAnalytics.config.dates.date_2_formatted
            }) + '</h2>',
            items       : [{
                xtype       : 'googleanalytics-line-chart',
                height      : 300,
                chart       : {
                    params      : {
                        data        : 'visits'
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
            }]
        }, {
            html        : '<br />'
        }, {
            xtype       : 'googleanalytics-panel-meta'
        }, {
            html        : '<br />'
        }, {
            xtype       : 'googleanalytics-panel-speed'
        }]
    });

    GoogleAnalytics.panel.Summary.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Summary, MODx.Panel);

Ext.reg('googleanalytics-panel-summary', GoogleAnalytics.panel.Summary);

GoogleAnalytics.panel.Visitors = function(config) {
    config = config || {};

    Ext.apply(config, {
        title       : '<i class="icon icon-users"></i>' + _('googleanalytics.title_visitors'),
        items       : [{
            layout      : 'column',
            items       : [{
                columnWidth : .5,
                title       : '<h2>' + _('googleanalytics.title_block_visitors') + '</h2>',
                items       : [{
                    xtype       : 'googleanalytics-pie-chart',
                    height      : 250,
                    chart       : {
                        params      : {
                            data        : 'visiters'
                        },
                        fields      : ['visitorType', 'visits'],
                        nameField   : 'visitorType',
                        dataField   : 'visits'
                    }
                }]
            }, {
                columnWidth : .5,
                title       : '<h2>' + _('googleanalytics.title_block_language') + '</h2>',
                items       : [{
                    xtype       : 'googleanalytics-pie-chart',
                    height      : 250,
                    chart       : {
                        params      : {
                            data        : 'language'
                        },
                        fields      : ['language', 'visits', 'visitors', 'pageviews'],
                        nameField   : 'language',
                        dataField   : 'visitors'
                    }
                }]
            }]
        }, {
            title       : '<h2>' + _('googleanalytics.title_visitors') + '</h2>',
            items       : [{
                xtype       : 'googleanalytics-grid-visitors'
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
        title       : '<i class="icon icon-car"></i>' + _('googleanalytics.title_sources'),
        items       : [{
            layout      : 'column',
            items       : [{
                columnWidth : .5,
                title       : '<h2>' + _('googleanalytics.title_block_sources') + '</h2>',
                items       : [{
                    xtype       : 'googleanalytics-pie-chart',
                    height      : 250,
                    chart       : {
                        params      : {
                            data        : 'sources-summary'
                        },
                        fields      : ['name', 'visits'],
                        nameField   : 'name',
                        dataField   : 'visits'
                    }
                }]
            }, {
                columnWidth : .5,
                title       : '<h2>' + _('googleanalytics.title_block_devices') + '</h2>',
                items       : [{
                    xtype       : 'googleanalytics-pie-chart',
                    height      : 250,
                    chart       : {
                        params      : {
                            data        : 'devices'
                        },
                        fields      : ['deviceCategory', 'visits'],
                        nameField   : 'deviceCategory',
                        dataField   : 'visits'
                    }
                }]
            }]
        }, {
            title       : '<h2>' + _('googleanalytics.title_sources') + '</h2>',
            items       : [{
                xtype       : 'googleanalytics-grid-sources'
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
        title       : '<i class="icon icon-book"></i>' + _('googleanalytics.title_content'),
        items       : [{
            title       : '<h2>' + _('googleanalytics.title_block_content_high') + '</h2>',
            items       : [{
                xtype       : 'googleanalytics-grid-content-high'
            }]
        }, {
            html        : '<br />'
        }, {
            title       : '<h2>' + _('googleanalytics.title_block_content_low') + '</h2>',
            items       : [{
                xtype       : 'googleanalytics-grid-content-low'
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
        title       : '<i class="icon icon-search"></i>' +  _('googleanalytics.title_content_search'),
        items       : [{
            title       : '<h2>' + _('googleanalytics.title_block_content_search') + '</h2>',
            items       : [{
                xtype       : 'googleanalytics-grid-content-search'
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
        title       :  '<i class="icon icon-trophy"></i>' + _('googleanalytics.title_goals'),
        items       : [{
            title       : '<h2>' + _('googleanalytics.title_block_goals') + '</h2>',
            items       : [{
                xtype       : 'googleanalytics-grid-goals'
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
                                        '<span class="google-analytics-meta-label">{name}</span>' +
                                        '<span class="google-analytics-meta-value">{value}</span>' +
                                        '<span class="google-analytics-meta-progress">' +
                                            '{[this.renderProgress(values.progress)]}' +
                                            '<span>{[this.renderNumber(values.progress)]} %</span>' +
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
        renderNumber: function(d) {
            return Ext.util.Format.number(d, '0');
        }
    });

    MODx.Ajax.request({
        url         : GoogleAnalytics.config.connector_url,
        params      : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
            profile     : GoogleAnalytics.config.authorized_profile.id,
            data        : 'meta-summary'
        },
        listeners   : {
            'success'   : {
                fn          : function(data) {
                    this.setData(data);
                },
                scope       : this
            }
        }
    });

    GoogleAnalytics.panel.Meta.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Meta, Ext.Panel, {
    setData: function(data) {
        this.tpl.overwrite(this.body, data);
    }
});

Ext.reg('googleanalytics-panel-meta', GoogleAnalytics.panel.Meta);

GoogleAnalytics.panel.Speed = function(config) {
    config = config || {};

    this.tpl = new Ext.XTemplate('<tpl>' +
        '<div class="google-analytics-metas">' +
            '<tpl if="results.length">' +
                '<h2>' + _('googleanalytics.title_block_speed', {
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
                                        '<span class="google-analytics-meta-label">{name}</span>' +
                                        '<span class="google-analytics-meta-value">{[this.renderNumber(values.value)]}</span>' +
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
           renderNumber: function(d) {
               return Ext.util.Format.number(d, '0.00');
           }
       });

    MODx.Ajax.request({
        url         : GoogleAnalytics.config.connector_url,
        params      : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
            profile     : GoogleAnalytics.config.authorized_profile.id,
            data        : 'speed'
        },
        listeners   : {
            'success'   : {
                fn          : function(data) {
                    this.setData(data);
                },
                scope       : this
            }
        }
    });

    GoogleAnalytics.panel.Speed.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.panel.Speed, Ext.Panel, {
    setData: function(data) {
        this.tpl.overwrite(this.body, data);
    }
});

Ext.reg('googleanalytics-panel-speed', GoogleAnalytics.panel.Speed);