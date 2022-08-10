GoogleAnalytics.panel.PieChart = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        cls : 'google-analytics-chart-loading'
    });

    GoogleAnalytics.panel.PieChart.superclass.constructor.call(this, config);

    this.on('afterrender', this.getData, this);
};

Ext.extend(GoogleAnalytics.panel.PieChart, MODx.Panel, {
    getData: function() {
        var params = this.chart.params || {};

        MODx.Ajax.request({
            url         : GoogleAnalytics.config.connector_url,
            params      : Ext.apply(params, {
                action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
                profile     : GoogleAnalytics.config.authorized_profile.id
            }),
            listeners   : {
                'success'   : {
                    fn          : function (data) {
                        var series = [];

                        data.results.forEach((function(value, index) {
                            series.push({
                                name    : value[this.chart.nameField],
                                y       : parseInt(value[this.chart.dataField])
                            });
                        }).bind(this));

                        this.loadChart(series);

                        this.removeClass('google-analytics-chart-loading');
                    },
                    scope       : this
                }
            }
        });
    },
    loadChart: function(series) {
        new Highcharts.Chart({
            chart       : {
                renderTo    : this.id,
                height      : this.height,
                borderWidth : 1,
                borderColor : 'transparent',
                shadow      : false
            },
            colors      : ['#058dc7', '#50b432', '#6cb1e8', '#ed561b', '#edef00', '#24cbe5', '#cccccc'],
            title       : {
                text        : null
            },
            credits     : {
                enabled     : false
            },
            tooltip     : {
                borderWidth : 1,
                borderColor : '#e4e4e4',
                shadow      : false,
                style       : {
                    color       : '#555555',
                    fontFamily  : 'Helvetica, Arial, Tahoma, sans-serif',
                    fontSize    : '12px',
                    lineHeight  : '18px',
                    fontWeight  : '400'
                },
                formatter   : function() {
                    return this.point.name + ' (' + Math.round(this.percentage) + '%)';
                }
            },
            plotOptions : {
                pie         : {
                    cursor      : 'pointer',
                    dataLabels  : {
                        enabled     : false
                    },
                    showInLegend: true,
                    allowPointSelect: true
                }
            },
            series      : [{
                type        : 'pie',
                keys        : this.chart.fields,
                data        : series
            }],
            legend      : {
                floating    : false,
                align       : 'right',
                verticalAlign : 'middle',
                layout      : 'vertical',
                borderWidth : 0,
                padding     : 0,
                width       : 150,
                symbolWidth : 12,
                symbolHeight : 12,
                itemStyle   : {
                    color       : '#555555',
                    fontFamily  : 'Helvetica, Arial, Tahoma, sans-serif',
                    fontSize    : '12px',
                    lineHeight  : '18px',
                    fontWeight  : '400'
                },
                labelFormatter  : function() {
                    return this.name + ' (' + this.y + ')';
                }
            }
        });
    }
});

Ext.reg('googleanalytics-pie-chart', GoogleAnalytics.panel.PieChart);

GoogleAnalytics.panel.LineChart = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        cls : 'google-analytics-chart-loading'
    });

    GoogleAnalytics.panel.LineChart.superclass.constructor.call(this, config);

    this.on('afterrender', this.getData, this);
};

Ext.extend(GoogleAnalytics.panel.LineChart, MODx.Panel, {
    getData: function() {
        var params = this.chart.params || {};

        MODx.Ajax.request({
            url         : GoogleAnalytics.config.connector_url,
            params      : Ext.apply(params, {
                action      : '\\Sterc\\GoogleAnalytics\\Processors\\Mgr\\Data\\Get',
                profile     : GoogleAnalytics.config.authorized_profile.id
            }),
            listeners   : {
                'success'   : {
                    fn          : function (data) {
                        var series = [];

                        Ext.iterate(this.chart.series, (function (serie) {
                            var values = [];

                            data.results.forEach((function(value, index) {
                                values.push({
                                    name        : value[this.chart.nameField],
                                    name_short  : value[this.chart.nameField + '_short'],
                                    x           : (new Date(value[this.chart.dateField])).getTime(),
                                    y           : parseInt(value[serie.dateField])
                                });
                            }).bind(this));

                            series.push({
                                type            : 'area',
                                name            : serie.name,
                                data            : values,
                                marker          : {
                                    symbol          : 'circle',
                                    radius          : 5
                                }
                            });
                        }).bind(this));

                        this.loadChart(series);

                        this.removeClass('google-analytics-chart-loading');
                    },
                    scope       : this,
                }
            },
        });
    },
    loadChart: function(series) {
        new Highcharts.Chart({
            chart       : {
                renderTo    : this.id,
                height      : this.height,
                borderWidth : 1,
                borderColor : 'transparent',
                shadow      : false,
                marginBottom: 50
            },
            colors      : ['#058dc7', '#50b432', '#6cb1e8', '#ed561b', '#edef00', '#24cbe5', '#cccccc'],
            title       : {
                text        : null
            },
            credits     : {
                enabled     : false
            },
            xAxis       : [{
                type        : 'datetime',
                gridLineColor : '#e5e5e5',
                lineColor   : '#e5e5e5',
                tickWidth   : 0,
                tickColor   : '#c0c0c0',
                tickInterval : 3 * 24 * 3600 * 1000,
                gridLineWidth : 1,
                labels      : {
                    align       : 'center',
                    x           : 0,
                    y           : 18,
                    style       : {
                        color       : '#555555',
                        fontFamily  : 'Helvetica, Arial, Tahoma, sans-serif',
                        fontSize    : '12px',
                        lineHeight  : '18px',
                        fontWeight  : '400'
                    },
                    formatter   : function() {
                        var value = this.value;

                        series[0].data.forEach((function(data) {
                            if (data.x === this.value) {
                                value = data.name_short || data.name;
                            }
                        }).bind(this));

                        return value;
                    }
                }
            }],
            yAxis       : [{
                title       : {
                    text        : null
                },
                tickWidth   : 0,
                tickColor   : '#e5e5e5',
                gridLineColor : '#e5e5e5',
                labels      : {
                    align       : 'right',
                    x           : -10,
                    y           : 3,
                    formatter   : function() {
                        return Highcharts.numberFormat(this.value, 0);
                    }
                },
                showFirstLabel : false
            }],
            tooltip     : {
                shared      : true,
                crosshairs  : true,
                borderWidth : 1,
                borderColor : '#e4e4e4',
                shadow      : false,
                style       : {
                    color       : '#555555',
                    fontFamily  : 'Helvetica, Arial, Tahoma, sans-serif',
                    fontSize    : '12px',
                    lineHeight  : '18px',
                    fontWeight  : '400'
                },
                formatter   : function() {
                    var output = ['<span style="font-weight: bold;">' + this.points[0].point.name + '</span>'];

                    Ext.iterate(this.points, function(point) {
                        output.push('<span style="color: ' + point.series.color + ';">' + point.series.name + '</span>: <span style="font-weight: bold;">' + point.y + '</span>');
                    });

                    return output.join('<br />');
                }
            },
            plotOptions : {
                area        : {
                    fillOpacity : 0.1
                }
            },
            series      : series,
            legend      : {
                floating    : true,
                align       : 'left',
                verticalAlign : 'bottom',
                layout      : 'horizontal',
                borderWidth : 0,
                padding     : 0,
                symbolWidth : 12,
                symbolHeight : 12,
                itemStyle   : {
                    color       : '#555555',
                    fontFamily  : 'Helvetica, Arial, Tahoma, sans-serif',
                    fontSize    : '12px',
                    lineHeight  : '18px',
                    fontWeight  : '400'
                }
            }
        });
    }
});

Ext.reg('googleanalytics-line-chart', GoogleAnalytics.panel.LineChart);