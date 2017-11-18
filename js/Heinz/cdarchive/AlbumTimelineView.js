/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2017 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.AlbumTimelineView', {
    extend: 'Ext.Container',
    width: 700,
    height: 600,
    layout: 'fit',
    items: {
	xtype: 'cartesian',
        store: Ext.create('Heinz.cdarchive.AlbumTimelineStore', {
            model: Ext.create('Heinz.cdarchive.AlbumTimeline'),
            proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
                extraParams: {
                  cmd: 'album_timeline'
                }
            }),
        }),
	axes: [{
            type: 'category',
            position: 'bottom'
	}, {
            type: 'numeric',
            position: 'left'
	}],
        series: [{
            type: 'line',
            xField: 'album_year',
            yField: 'count',
        }]
    }
});
