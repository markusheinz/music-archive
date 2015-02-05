/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.application({
    name   : 'Ext JS 5 CD Archive',

    launch : function() {

        Ext.widget({
            xtype: 'tabpanel',
            renderTo: Ext.getBody(),
            width: 727,
            height: 830,
            title: 'Music Archive',
            items: [
                Ext.create('Heinz.cdarchive.AlbumView'),
                Ext.create('Heinz.cdarchive.AlbumForm')
            ]
        })
    }
});
