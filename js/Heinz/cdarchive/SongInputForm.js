/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.SongInputForm', {
    extend: 'Ext.panel.Panel',
    layout: {
        type: 'vbox',
        align: 'stretch',
    },
    initComponent: function() {
        this.newSongStore = Ext.create('Heinz.cdarchive.NewSongStore');
        this.items = [
            Ext.create('Heinz.cdarchive.SongCountForm',
                       {
                           initialSongCount: this.newSongStore.songCountValue,
                           changeFunction: this.newSongStore.songCountChange,
                           context: this.newSongStore
                       }),
            Ext.create('Heinz.cdarchive.SongGridForm',
                       {
                           store: this.newSongStore
                       })
        ];

        this.callParent();
   },
    getStore: function() {
        return this.newSongStore;
    },
    reset: function() {
        this.items.getAt(0).reset();
        this.newSongStore.initializeSongStoreData();
    },
    setSongCountValue: function(value) {
        this.items.getAt(0).setValue(value);
    }
});
