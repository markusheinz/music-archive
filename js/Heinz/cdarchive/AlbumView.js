/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.AlbumView', {
    extend: 'Ext.panel.Panel',
    layout: {
        type: 'vbox',
        align: 'stretch',
        padding: '0'
    },
    title: 'Album List',
    items: [
        Ext.create('Heinz.cdarchive.FilterPanel'),
        Ext.create('Heinz.cdarchive.AlbumGrid'),
        Ext.create('Heinz.cdarchive.GridButtonPanel')
    ],
    dockedItems: [{
	xtype: 'pagingtoolbar',
	store: 'albumStore',
	dock: 'bottom',
	displayInfo: true
    }],
});
