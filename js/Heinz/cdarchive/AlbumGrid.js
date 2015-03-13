/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.AlbumGrid', {
    extend: 'Ext.grid.Panel',
    width: 750,
    height: 560,
    sortableColumns: false,
    store: Ext.create('Heinz.cdarchive.AlbumStore'),
    id: 'AlbumGrid',
    columns: {
        defaults: {
            width: 100
        },
        items: [
            { text: 'Artist', dataIndex: 'artist_name', width: 200 },
            { text: 'Title', dataIndex: 'album_title', width: 300 },
            { text: 'Year', dataIndex: 'album_year' },
            { text: 'Location', dataIndex: 'location_desc' }
        ]
    },
    listeners : {
	'itemdblclick': function(dv, record, item, index, e) {
	    this.showAlbumSongs(record.data.album_id,
			   record.data.artist_name + " - " +
			   record.data.album_title);
	},
    },
    initComponent: function () {
        this.songStore = Ext.create('Heinz.cdarchive.SongStore');
        this.callParent();
    },
    showAlbumSongs: function (id, title) {
        this.songStore.getProxy().setExtraParam('id', id);
        this.songStore.loadPage(1);

        Ext.create('Ext.window.Window', {
	    title: 'Album Details',
	    width: 600,
	    height: 400,
	    layout: 'fit',
	    items: {
                xtype: 'grid',
                border: false,
	        title    : title,
                sortableColumns: false,
                store    : 'songStore',
	        columns: {
                    items: [
                        { text: 'Track', dataIndex: 'track_number', width: 70 },
                        { text: 'Title', dataIndex: 'song_title', width: 500 },
                    ]
                },
	        dockedItems: [{
		    xtype: 'pagingtoolbar',
		    store: 'songStore',
		    dock: 'bottom',
		    displayInfo: true
	        }],
	    }
        }).show();
    }
});
