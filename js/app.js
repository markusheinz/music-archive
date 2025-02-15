/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2017, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.application({
    name   : 'Ext JS 5 CD Archive',

    launch : function() {

        Ext.widget({
            xtype: 'tabpanel',
            renderTo: Ext.getBody(),
            width: 800,
            height: 830,
            title: 'Music Archive',
            items: [
                Ext.create('Heinz.cdarchive.AlbumView'),
                Ext.create('Heinz.cdarchive.AlbumForm'),
                Ext.create('Heinz.cdarchive.Statistic'),
                Ext.create('Heinz.cdarchive.Timeline'),
                Ext.create('Heinz.cdarchive.TopTen')
            ]
        })
    }
});
