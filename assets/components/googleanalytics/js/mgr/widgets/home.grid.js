GoogleAnalytics.grid.Visitors = function(config) {
    config = config || {};

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('googleanalytics.date'),
            dataIndex   : 'date_formatted',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.visits'),
            dataIndex   : 'visits',
            sortable    : true,
            editable    : false,
            width       : 100
        }, {
            header      : _('googleanalytics.visitors'),
            dataIndex   : 'visitors',
            sortable    : true,
            editable    : false,
            width       : 100
        }, {
            header      : _('googleanalytics.pageviews'),
            dataIndex   : 'pageviews',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.visitors_time'),
            dataIndex   : 'avgSessionDuration',
            sortable    : true,
            editable    : false,
            width       : 100
        }, {
            header      : _('googleanalytics.visits_new') + ' (%)',
            dataIndex   : 'percentNewVisits',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderPercent
        }, {
            header      : _('googleanalytics.bouncerate')  + ' (%)',
            dataIndex   : 'visitBounceRate',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderPercent
        }]
    });

    Ext.applyIf(config, {
        cm          : columns,
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
            profile     : GoogleAnalytics.config.authorized_profile.id,
            data        : 'visits'
        },
        fields      : ['date', 'date_formatted', 'visits', 'visitors', 'pageviews', 'pageviewsPerVisit', 'avgSessionDuration', 'percentNewVisits', 'visitBounceRate'],
        paging      : false,
        sortBy      : 'date'
    });

    GoogleAnalytics.grid.Visitors.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.grid.Visitors, MODx.grid.Grid, {
    renderPercent: function(d, c) {
        var percent = Ext.util.Format.number(d, '0,0') + '%';

        return String.format('<div class="google-analytics-percent">' +
            '<span class="google-analytics-percent-wrapper">' +
                '<span class="google-analytics-percent-bar" style="width: {0};"></span>' +
                '<span class="google-abalytics-percent-value">{1}</span>' +
            '</span>' +
        '</div>', percent, percent);
    }
});

Ext.reg('googleanalytics-grid-visitors', GoogleAnalytics.grid.Visitors);

GoogleAnalytics.grid.Sources = function(config) {
    config = config || {};

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('googleanalytics.source'),
            dataIndex   : 'source',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderUrl
        }, {
            header      : _('googleanalytics.visits'),
            dataIndex   : 'visits',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.visitors'),
            dataIndex   : 'visitors',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.visitors_time'),
            dataIndex   : 'avgSessionDuration',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.visits_new') + ' (%)',
            dataIndex   : 'percentNewVisits',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderPercent
        }, {
            header      : _('googleanalytics.bouncerate')  + ' (%)',
            dataIndex   : 'visitBounceRate',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderPercent
        }]
    });

    Ext.applyIf(config, {
        cm          : columns,
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
            profile     : GoogleAnalytics.config.authorized_profile.id,
            data        : 'sources'
        },
        fields      : ['source', 'visits', 'visitors', 'pageviewsPerVisit', 'avgSessionDuration', 'percentNewVisits', 'visitBounceRate'],
        paging      : true,
        sortBy      : 'visits'
    });

    GoogleAnalytics.grid.Sources.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.grid.Sources, MODx.grid.Grid, {
    renderUrl: function(d, c) {
        if (d !== '' && d !== '(direct)' && (/\./.test(d))) {
            if (!(/(http(s?)):\/\//gi.test(d))) {
                d = 'http://' + d;
            }

            return '<a href="' + d + '" target="_blank">' + d + '</a>';
        }

        return d;
    },
    renderPercent: function(d, c) {
        var percent = Ext.util.Format.number(d, '0,0') + '%';

        return String.format('<div class="google-analytics-percent">' +
            '<span class="google-analytics-percent-wrapper">' +
                '<span class="google-analytics-percent-bar" style="width: {0};"></span>' +
                '<span class="google-abalytics-percent-value">{1}</span>' +
            '</span>' +
        '</div>', percent, percent);
    }
});

Ext.reg('googleanalytics-grid-sources', GoogleAnalytics.grid.Sources);

GoogleAnalytics.grid.ContentHigh = function(config) {
    config = config || {};

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('googleanalytics.content'),
            dataIndex   : 'pagePath',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderUrl
        }, {
            header      : _('googleanalytics.entrances'),
            dataIndex   : 'entrances',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.bounces'),
            dataIndex   : 'bounces',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.bouncerate') + ' (%)',
            dataIndex   : 'entranceBounceRate',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderPercent
        }]
    });

    Ext.applyIf(config, {
        cm          : columns,
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
            profile     : GoogleAnalytics.config.authorized_profile.id,
            data        : 'content-high'
        },
        fields      : ['pagePath', 'entrances', 'bounces', 'entranceBounceRate', 'exits'],
        paging      : false,
        pageSize    : 15,
        sortBy      : 'entrances'
    });

    GoogleAnalytics.grid.ContentHigh.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.grid.ContentHigh, MODx.grid.Grid, {
    renderUrl: function(d, c) {
        return '<a href="' + (GoogleAnalytics.config.authorized_profile.url + d) + '" target="_blank">' + d + '</a>';
    },
    renderPercent: function(d, c) {
        var percent = Ext.util.Format.number(d, '0,0') + '%';

        return String.format('<div class="google-analytics-percent">' +
            '<span class="google-analytics-percent-wrapper">' +
                '<span class="google-analytics-percent-bar" style="width: {0};"></span>' +
                '<span class="google-abalytics-percent-value">{1}</span>' +
            '</span>' +
        '</div>', percent, percent);
    }
});

Ext.reg('googleanalytics-grid-content-high', GoogleAnalytics.grid.ContentHigh);

GoogleAnalytics.grid.ContentLow = function(config) {
    config = config || {};

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('googleanalytics.content'),
            dataIndex   : 'pagePath',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderUrl
        }, {
            header      : _('googleanalytics.exits'),
            dataIndex   : 'exits',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.bounces'),
            dataIndex   : 'bounces',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.exitrate'),
            dataIndex   : 'exitRate',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderPercent
        }]
    });

    Ext.applyIf(config, {
        cm          : columns,
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
            profile     : GoogleAnalytics.config.authorized_profile.id,
            data        : 'content-low'
        },
        fields      : ['pagePath', 'exits', 'bounces', 'pageviews', 'exitRate'],
        paging      : false,
        pageSize    : 15,
        sortBy      : 'exits'
    });

    GoogleAnalytics.grid.ContentLow.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.grid.ContentLow, MODx.grid.Grid, {
    renderUrl: function(d, c) {
        return '<a href="' + (GoogleAnalytics.config.authorized_profile.url + d) + '" target="_blank">' + d + '</a>';
    },
    renderPercent: function(d, c) {
        var percent = Ext.util.Format.number(d, '0,0') + '%';

        return String.format('<div class="google-analytics-percent">' +
            '<span class="google-analytics-percent-wrapper">' +
                '<span class="google-analytics-percent-bar" style="width: {0};"></span>' +
                '<span class="google-abalytics-percent-value">{1}</span>' +
            '</span>' +
        '</div>', percent, percent);
    }
});

Ext.reg('googleanalytics-grid-content-low', GoogleAnalytics.grid.ContentLow);

GoogleAnalytics.grid.ContentSearch = function(config) {
    config = config || {};

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('googleanalytics.keyword'),
            dataIndex   : 'keyword',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.visits'),
            dataIndex   : 'visits',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.pageviews'),
            dataIndex   : 'pageviewsPerVisit',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.visitors_time'),
            dataIndex   : 'avgSessionDuration',
            sortable    : true,
            editable    : false,
            width       : 125
        }, {
            header      : _('googleanalytics.visits_new') + ' (%)',
            dataIndex   : 'percentNewVisits',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderPercent
        }, {
            header      : _('googleanalytics.bouncerate') + ' (%)',
            dataIndex   : 'visitBounceRate',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderPercent
        }]
    });

    Ext.applyIf(config, {
        cm          : columns,
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
            profile     : GoogleAnalytics.config.authorized_profile.id,
            data        : 'content-search'
        },
        fields      : ['keyword', 'visits', 'pageviewsPerVisit', 'avgSessionDuration', 'percentNewVisits', 'visitBounceRate'],
        paging      : true,
        sortBy      : 'visits'
    });

    GoogleAnalytics.grid.ContentSearch.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.grid.ContentSearch, MODx.grid.Grid, {
    renderPercent: function(d, c) {
        var percent = Ext.util.Format.number(d, '0,0') + '%';

        return String.format('<div class="google-analytics-percent">' +
            '<span class="google-analytics-percent-wrapper">' +
                '<span class="google-analytics-percent-bar" style="width: {0};"></span>' +
                '<span class="google-abalytics-percent-value">{1}</span>' +
            '</span>' +
        '</div>', percent, percent);
    }
});

Ext.reg('googleanalytics-grid-content-search', GoogleAnalytics.grid.ContentSearch);

GoogleAnalytics.grid.Goals = function(config) {
    config = config || {};

    var columns = new Ext.grid.ColumnModel({
        columns     : [{
            header      : _('googleanalytics.location'),
            dataIndex   : 'goalCompletionLocation',
            sortable    : true,
            editable    : false,
            width       : 125,
            renderer    : this.renderUrl
        }, {
            header      : _('googleanalytics.goal'),
            dataIndex   : 'goalCompletionsAll',
            sortable    : true,
            editable    : false,
            width       : 150
        }, {
            header      : _('googleanalytics.goal') + ' (%)',
            dataIndex   : 'goalCompletionsAllPercent',
            sortable    : true,
            editable    : false,
            width       : 150,
            renderer    : this.renderPercent
        }]
    });

    Ext.applyIf(config, {
        cm          : columns,
        url         : GoogleAnalytics.config.connector_url,
        baseParams  : {
            action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
            profile     : GoogleAnalytics.config.authorized_profile.id,
            data        : 'goals'
        },
        fields      : ['goalCompletionLocation', 'goalStartsAll', 'goalStartsAllPercent', 'goalCompletionsAll', 'goalCompletionsAllPercent'],
        paging      : true,
        sortBy      : 'goalCompletionsAll'
    });

    GoogleAnalytics.grid.Goals.superclass.constructor.call(this, config);
};

Ext.extend(GoogleAnalytics.grid.Goals, MODx.grid.Grid, {
    renderUrl: function(d, c) {
        return '<a href="' + (GoogleAnalytics.config.authorized_profile.url + d) + '" target="_blank">' + d + '</a>';
    },
    renderPercent: function(d, c) {
        var percent = Ext.util.Format.number(d, '0,0') + '%';

        return String.format('<div class="google-analytics-percent">' +
            '<span class="google-analytics-percent-wrapper">' +
                '<span class="google-analytics-percent-bar" style="width: {0};"></span>' +
                '<span class="google-abalytics-percent-value">{1}</span>' +
            '</span>' +
        '</div>', percent, percent);
    }
});

Ext.reg('googleanalytics-grid-goals', GoogleAnalytics.grid.Goals);