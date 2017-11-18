/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2017 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.TopTenView', {
    extend: 'Ext.Container',
    width: 700,
    height: 600,
    layout: 'fit',
    items: {
	xtype: 'cartesian',
        store: Ext.create('Heinz.cdarchive.ArtistTopTenStore', {
            model: Ext.create('Heinz.cdarchive.ArtistTopTen'),
            proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
                extraParams: {
                  cmd: 'album_topten'
                }
            }),
        }),
	axes: [{
            type: 'numeric',
            position: 'bottom'
	},{
            type: 'category',
            position: 'left'
	}],
        series: [{
            type: 'bar',
            xField: 'artist_name',
            yField: 'count',
        }],
	flipXY: true
    }
});
