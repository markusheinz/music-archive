/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.AlbumForm', {
    extend: 'Ext.panel.Panel',
    title: 'Add / Edit Album',
    layout: {
        type: 'vbox',
        align: 'stretch',
    },
    id: 'albumForm',
    albumId: -1,
    initComponent: function () {
        this.items = [
            Ext.create('Heinz.cdarchive.ArtistForm'),
            Ext.create('Heinz.cdarchive.TitleForm'),
            Ext.create('Heinz.cdarchive.GenreForm'),
            Ext.create('Heinz.cdarchive.LocationForm'),
            Ext.create('Heinz.cdarchive.YearForm'),
            Ext.create('Heinz.cdarchive.OriginalForm'),
            Ext.create('Heinz.cdarchive.SongInputForm'),
            Ext.create('Heinz.cdarchive.ButtonPanel')
        ];

        this.items[7].getResetButton().addListener('click', 
                                                   this.resetAlbumForm, 
                                                   this);
        this.items[7].getSubmitButton().addListener('click', 
                                                    this.submitAlbumForm, 
                                                    this);
        this.callParent();
    },
    resetAlbumForm: function () {
        for (var i = 0; i < 7; i++) {
            this.items.getAt(i).reset();
        }

        this.albumId = -1;
    },
    submitAlbumForm: function () {
        var album = {};
        album.artist_id = this.items.getAt(0).getValue();
        album.title = this.items.getAt(1).getValue();
        album.genre_id = this.items.getAt(2).getValue();
        album.location_id = this.items.getAt(3).getValue();
        album.year = this.items.getAt(4).getValue();
        album.original = this.items.getAt(5).getValue();

        album.songs = [];
        var store = this.items.getAt(6).getStore();

        for (var i = 0; i < store.getCount(); i++) {
            album.songs[i] = [];
            album.songs[i][0] = store.getAt(i).get('index');
            album.songs[i][1] = store.getAt(i).get('title');
        }

        if (this.albumId > 0) {
            var params = {
                cmd: 'update_album',
                album: Ext.JSON.encode(album),
                id: this.albumId
            };
        } else {
            var params = {
                cmd: 'add_album',
                album: Ext.JSON.encode(album)
            };
        }

        Ext.Ajax.request({
            url: '../php/dorequest.php',
            params: params,
            success: function(response) {
                var text = response.responseText;
                
                if (text == 'true') {
                    Ext.Msg.alert('Add / Edit Album', 
                                  'Changes saved successfully.');
                    Ext.getCmp('albumForm').resetAlbumForm();
                    Ext.data.StoreManager.lookup('albumStore').reload();
                    Ext.getCmp('FilterPanel').reload();
                } else {
                    Ext.Msg.alert('Add / Edit Album', 
                                  'Changes could not be saved.');
                }
            },
            failure: function(response) {
                Ext.Msg.alert('Add Album', 'Could not contact server.');
            }
        });
    },
    fillAlbumForm: function(albumId) {
        this.albumId = albumId;
        var items = this.items;

        Ext.Ajax.request({
            url: '../php/getjson.php',
            method: 'GET',
            params: {
                cmd: 'album_edit',
                id: albumId
            },
            success: function(response) {
                var text = response.responseText;
                var json = Ext.JSON.decode(text);
                

                if (json.count == 1) {

                    var album = json.result;

                    // load the data into the form

                    items.getAt(0).setValue(parseInt(album.artist_id));
                    items.getAt(1).setValue(album.album_title);
                    items.getAt(2).setValue(parseInt(album.genre_id));
                    items.getAt(3).setValue(parseInt(album.location_id));
                    items.getAt(4).setValue(album.album_year );
                    items.getAt(5).setValue(album.album_original);

                    var store = items.getAt(6).getStore();
        
                    store.removeAll();
                    store.songCountValue = album.songs.length;

                    items.getAt(6).setSongCountValue(album.songs.length);
                    
                    for (var i = 0; i < album.songs.length; i++) {
                        store.add({
                            index: album.songs[i].track_number,
                            title: album.songs[i].song_title
                        });
                    }

                } else {
                    Ext.Msg.alert('Edit Album', 'Album could not be loaded.');
                }
            },
            failure: function(response) {
                Ext.Msg.alert('Edit Album', 'Could not contact server.');
            }
        });
    }
});
