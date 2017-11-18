/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2016, 2017 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.GenreStatisticView', {
    extend: 'Ext.Container',
    width: 700,
    height: 800,
    layout: 'fit',
    items: {
        xtype: 'polar',
        interactions: 'rotate',
        store: Ext.create('Heinz.cdarchive.GenreStatisticStore', {
            model: Ext.create('Heinz.cdarchive.GenreStatistic'),
            proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
                extraParams: {
                  cmd: 'genre_statistic'
                }
            }),
        }),
        series: {
            type: 'pie',
            label: {
                field: 'genre',
                display: 'rotate'
            },
            xField: 'genre_count',
            donut: 5
        }
    }
});
