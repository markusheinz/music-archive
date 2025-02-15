/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

/*
 * This class contains all user interface elements which are necessary to
 * filter the list of albums.
 */ 
Ext.define('Heinz.cdarchive.FilterPanel', {
    extend: 'Ext.panel.Panel',
    layout: {
	type: 'vbox',
    },
    id: 'FilterPanel',
    items: [
	{
	    xtype: 'panel',
	    layout: {
		type: 'column',
	    },
	    items: [
		Ext.create('Heinz.cdarchive.ArtistFilter'),
		Ext.create('Heinz.cdarchive.GenreFilter'),
		Ext.create('Heinz.cdarchive.LocationFilter')
	    ]
	},
	{
	    xtype: 'panel',
	    layout: {
		type: 'column',
	    },
	    items: [
		Ext.create('Heinz.cdarchive.YearFilter'),
		Ext.create('Heinz.cdarchive.OriginalFilter'),
		Ext.create('Heinz.cdarchive.SongFilter')
	    ]
	},
        {
            type: 'panel',
            layout: {
                type: 'hbox'
            },
            items: [
                {
                    xtype: 'button',
                    margin: '10 10 20 200',
                    text: 'Reset',
                    listeners: {
                        'click': function () {
                            Ext.getCmp('FilterPanel').reset();
                        }
                    }
                },
                {
                    xtype: 'button',
                    margin: '10 10 20 250',
                    text: 'Apply',
                    listeners: {
                        'click': function () {
                            Ext.getCmp('FilterPanel').filter();
                        }
                    }
                }
            ]
        }
    ],
    listeners: {
        'beforerender': function() {
            this.items.getAt(1).items.getAt(1).items.getAt(1).setValue(-1);
        }
    },
    /*
     * This method reloads all combo box values. It is called when some 
     * metadata has been changed.
     */
    reload: function() {
        for (var p = 0; p < this.items.getCount() - 1; p++) {
            var panel = this.items.getAt(p);

            for (var i = 0; i < panel.items.getCount(); i++) {
                panel.items.getAt(i).reload();
            }
        }
    },    
    /*
     * This method resets all filters to their original values. This results
     * in an unfiltered album list.
     */
    reset: function() {
        for (var p = 0; p < this.items.getCount() - 1; p++) {
            var panel = this.items.getAt(p);

            for (var i = 0; i < panel.items.getCount(); i++) {
                panel.items.getAt(i).reset();
                
            }
        }

        this.items.getAt(1).items.getAt(1).items.getAt(1).setValue(-1);

        var store = Ext.getStore('albumStore');
        store.getProxy().setExtraParams({cmd: 'album_list'});
        store.loadPage(1);
    },
    /*
     * This method filters the album list by the specified criteria.
     */
    filter: function() {
        var artistId = this.items.getAt(0).items.getAt(0).getValue();
        var genreId = this.items.getAt(0).items.getAt(1).getValue();
        var locationId = this.items.getAt(0).items.getAt(2).getValue();
        var year = this.items.getAt(1).items.getAt(0).getValue();
        var original = this.items.getAt(1).items.getAt(1).getValue();
        var song = this.items.getAt(1).items.getAt(2).getValue();

        var store = Ext.getStore('albumStore');
        store.getProxy().setExtraParams({cmd: 'album_list'});

        if (artistId > 0) {
            store.getProxy().setExtraParam('artist_id', artistId);
        }

        if (genreId > 0) {
            store.getProxy().setExtraParam('genre_id', genreId);
        }

        if (locationId > 0) {
            store.getProxy().setExtraParam('location_id', locationId);
        }

        if (year == 'unknown') {
            store.getProxy().setExtraParam('year', null);
        } else if (year != null && year >= 0) {
            store.getProxy().setExtraParam('year', year);
        }

        if (original >= 0 && original <= 1) {
            store.getProxy().setExtraParam('original', original);
        }

        if (song.length > 0) {
            store.getProxy().setExtraParam('song', song);
        }
       
        store.loadPage(1);
    }
});
