GoogleAnalytics.panel.PieChart = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        cls: 'google-analytics-chart-loading',
        listeners		: {
            /*'afterrender'	: {
                fn				: this.getData,
                scope			: this
            }*/
        }
    });

    GoogleAnalytics.panel.PieChart.superclass.constructor.call(this, config);

    this.on('afterrender', this.getData, this);
};

Ext.extend(GoogleAnalytics.panel.PieChart, MODx.Panel, {
    getData: function() {
        var params = this.pieConfig.params || {};

        Ext.apply(params, {
            action		: 'mgr/getdata',
            profile		: GoogleAnalytics.config.authorized_profile.id
        });

        Ext.Ajax.request({
            url			: GoogleAnalytics.config.connector_url,
            params 		: params,
            method		: 'GET',
            success		: function (result, request) {
                var series = [];

                if (data = Ext.util.JSON.decode(result.responseText)) {
                    for (var i = 0; i < data.results.length; i++) {
                        series.push({
                            name	: data.results[i][this.pieConfig.nameField],
                            y 		: parseInt(data.results[i][this.pieConfig.dataField])
                        });
                    }
                }

                this.loadChart(series);

                this.removeClass('google-analytics-chart-loading');
            },
            scope		: this,
        });
    },
    loadChart: function(series) {
        new Highcharts.Chart({
            chart		: {
                renderTo	: this.id,
                height		: this.height,
                borderWidth	: 1,
                borderColor	: 'transparent',
                shadow		: false
            },
            colors		: ['#058dc7', '#50b432', '#6cb1e8', '#ed561b', '#edef00', '#24cbe5', '#cccccc'],
            title		: {
                text		: null
            },
            credits		: {
                enabled		: false
            },
            tooltip		: {
                borderWidth	: 1,
                borderColor	: '#e4e4e4',
                shadow		: false,
                style		: {
                    color		: '#53595F',
                    fontFamily	: 'Helvetica, Arial, Tahoma, sans-serif',
                    fontSize	: '11px',
                    lineHeight	: '17.5px',
                    fontWeight	: '400'
                },
                formatter	: function() {
                    return String.format('{0} ({1}%)', this.point.name, Math.round(this.percentage));
                }
            },
            plotOptions	: {
                pie			: {
                    cursor		: 'pointer',
                    dataLabels	: {
                        enabled		: false
                    },
                    showInLegend: true,
                    allowPointSelect: true
                }
            },
            series		: [{
                type		: 'pie',
                keys		: this.pieConfig.fields,
                data		: series
            }],
            legend		: {
                floating	: false,
                align		: 'right',
                verticalAlign : 'middle',
                layout		: 'vertical',
                borderWidth	: 0,
                padding		: 0,
                width		: 150,
                itemStyle	: {
                    color			: '#53595F',
                    fontFamily		: 'Helvetica, Arial, Tahoma, sans-serif',
                    fontSize		: '13px',
                    lineHeight		: '19.5px',
                    fontWeight		: '400'
                },
                labelFormatter	: function() {
                    return String.format('{0} ({1})', this.name, this.y);
                }
            }
        });
    }
});

Ext.reg('googleanalytics-pie-chart', GoogleAnalytics.panel.PieChart);

GoogleAnalytics.panel.LineChart = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        cls: 'google-analytics-chart-loading',
        listeners		: {
            /*'afterrender'	: {
                fn				: this.getData,
                scope			: this
            }*/
        }
    });

    GoogleAnalytics.panel.LineChart.superclass.constructor.call(this, config);

    this.on('afterrender', this.getData, this);
};

Ext.extend(GoogleAnalytics.panel.LineChart, MODx.Panel, {
    getData: function() {
        var params = this.pieConfig.params || {};

        Ext.apply(params, {
            action		: 'mgr/getdata',
            profile		: GoogleAnalytics.config.authorized_profile.id
        });

        Ext.Ajax.request({
            url			: GoogleAnalytics.config.connector_url,
            params 		: params,
            method		: 'GET',
            success		: function (result, request) {
                var series = [];

                if (data = Ext.util.JSON.decode(result.responseText)) {
                    for (var i = 0; i < this.pieConfig.series.length; i++) {
                        var serie = [],
                            begin = null;

                        for (var ii = 0; ii < data.results.length; ii++) {
                            if (ii == 0) {
                                begin = data.results[ii][this.pieConfig.dateField];
                            }

                            serie.push({
                                name	: data.results[ii][this.pieConfig.nameField],
                                x		: (new Date(data.results[ii][this.pieConfig.dateField])).getTime(),
                                y 		: parseInt(data.results[ii][this.pieConfig.series[i].dateField])
                            });
                        }

                        series.push({
                            //type			: 'area',
                            name			: this.pieConfig.series[i].name,
                            data			: serie,
                            pointStart		: new Date(begin),
                            pointInterval	: 24 * 3600 * 1000,
                            marker			: {
                                radius			: 4,
                                symbol			: 'circle',
                                fillColor		: '#ffffff',
                                lineWidth		: 2,
                                lineColor		: null
                            }
                        });
                    }
                }

                this.loadChart(series);

                this.removeClass('google-analytics-chart-loading');
            },
            scope		: this,
        });
    },
    loadChart: function(series) {
        new Highcharts.Chart({
            chart		: {
                renderTo	: this.id,
                height		: this.height,
                borderWidth	: 1,
                borderColor	: 'transparent',
                shadow		: false,
                marginBottom: 50
            },
            colors		: ['#058dc7', '#50b432', '#6cb1e8', '#ed561b', '#edef00', '#24cbe5', '#cccccc'],
            title		: {
                text		: null
            },
            credits		: {
                enabled		: false
            },
            xAxis		: [{
                type		: 'datetime',
                gridLineColor : '#E5E5E5',
                lineColor	: '#E5E5E5',
                tickWidth	: 0,
                tickColor	: '#c0c0c0',
                tickInterval: 7 * 24 * 3600 * 1000,
                gridLineWidth : 1,
                labels		: {
                    align		: 'center',
                    x			: 0,
                    y			: 18
                }
            }],
            yAxis		: [{
                title		: {
                    text		: null
                },
                tickWidth	: 0,
                tickColor	: '#E5E5E5',
                gridLineColor : '#E5E5E5',
                labels		: {
                    align		: 'right',
                    x			: -10,
                    y			: 3,
                    formatter	: function() {
                        return Highcharts.numberFormat(this.value, 0);
                    }
                },
                showFirstLabel: false
            }],
            tooltip		: {
                shared		: true,
                crosshairs	: true,
                borderWidth	: 1,
                borderColor	: '#e4e4e4',
                shadow		: false,
                style		: {
                    color		: '#53595F',
                    fontFamily	: 'Helvetica, Arial, Tahoma, sans-serif',
                    fontSize	: '11px',
                    lineHeight	: '17.5px',
                    fontWeight	: '400'
                },
                formatter	: function() {
                    var xPoint = '',
                        output = [];

                    $.each(this.points, function(i, point) {
                        if (Ext.isEmpty(xPoint)) {
                            if (label = point.point.name) {
                                xPoint = String.format('<span style="font-weight: bold;">{0}</span>', (label.charAt(0).toUpperCase() + label.slice(1)));
                            }
                        }

                        output.push(String.format('<span style="color: {0};">{1}</span>: <span style="font-weight: bold;">{2}</span>', point.series.color, point.series.name, point.y));
                    });

                    output.unshift(xPoint);

                    return output.join('<br />');
                }
            },
            plotOptions	: {
                area		: {
                    fillOpacity	: 0.1
                }
            },
            series		: series,
            legend		: {
                floating	: true,
                align		: 'left',
                verticalAlign : 'bottom',
                layout		: 'horizontal',
                borderWidth	: 0,
                padding		: 0,
                itemStyle	: {
                    color			: '#53595F',
                    fontFamily		: 'Helvetica, Arial, Tahoma, sans-serif',
                    fontSize		: '13px',
                    lineHeight		: '19.5px',
                    fontWeight		: '400'
                },
            }
        });
    }
});

Ext.reg('googleanalytics-line-chart', GoogleAnalytics.panel.LineChart);