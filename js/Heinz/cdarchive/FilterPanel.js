/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.FilterPanel', {
    extend: 'Ext.panel.Panel',
    layout: {
        type: 'vbox',
    },
    id: 'FilterPanel',
    items: [
        {
            type: 'panel',
            layout: {
                type:'hbox'
            },
            items: [
                Ext.create('Heinz.cdarchive.ArtistFilter'),
                Ext.create('Heinz.cdarchive.GenreFilter'),
                Ext.create('Heinz.cdarchive.LocationFilter')
            ]
        },
        {
            type: 'panel',
            layout: {
                type:'hbox'
            },
            items: [
                Ext.create('Heinz.cdarchive.YearFilter'),
                Ext.create('Heinz.cdarchive.OriginalFilter'),
                {
                    xtype: 'button',
                    margin: '10 10 0 10',
                    text: 'Reset',
                    listeners: {
                        'click': function () {
                            Ext.getCmp('FilterPanel').reset();
                        }
                    }
                },
                {
                    xtype: 'button',
                    margin: '10 10 0 10',
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
    reload: function() {
        for (var p = 0; p < this.items.getCount(); p++) {
            var panel = this.items.getAt(p);

            for (var i = 0; i < panel.items.getCount(); i++) {
                if (p == 1 && i == panel.items.getCount() - 3) {
                    break;
                }
               
                panel.items.getAt(i).reload();
                
            }
        }
    },    
    reset: function() {
        for (var p = 0; p < this.items.getCount(); p++) {
            var panel = this.items.getAt(p);

            for (var i = 0; i < panel.items.getCount(); i++) {
                if (p == 1 && i == panel.items.getCount() - 3) {
                    break;
                }
               
                panel.items.getAt(i).reset();
                
            }
        }

        this.items.getAt(1).items.getAt(1).items.getAt(1).setValue(-1);

        var store = Ext.getStore('albumStore');
        store.getProxy().setExtraParams({cmd: 'album_list'});
        store.loadPage(1);
    },
    filter: function() {
        var artistId = this.items.getAt(0).items.getAt(0).getValue();
        var genreId = this.items.getAt(0).items.getAt(1).getValue();
        var locationId = this.items.getAt(0).items.getAt(2).getValue();
        var year = this.items.getAt(1).items.getAt(0).getValue();
        var original = this.items.getAt(1).items.getAt(1).getValue();

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
       
        store.loadPage(1);
    }
});
