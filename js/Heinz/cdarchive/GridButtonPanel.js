/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.GridButtonPanel', {
    extend: 'Ext.panel.Panel',
    layout: {
        type: 'hbox',
        align: 'stretch',
        padding: '0'
    },
    items: [
        {
            xtype: 'button',
            text: 'Display',
            margin: '0 0 0 20',
            listeners: {
                'click': function (button, events, eOpts) {
                    var result = button.up().getSelectionParameters(button);
                    
                    if (result) {
                        result.grid.showAlbumSongs(result.id, result.title);
                    }
                }
            }
        },
        {
            xtype: 'button',
            text: 'Edit',
            margin: '0 0 0 150',
            listeners: {
                'click': function (button, events, eOpts) {
                    var result = button.up().getSelectionParameters(button);
                    
                    if (result) {
                        var tabpanel = button.up().up().up();
                        tabpanel.items.getAt(1).fillAlbumForm(result.id);
                        tabpanel.setActiveTab(1);
                    }
                }
            }
        },
        {
            xtype: 'button',
            text: 'Delete',
            margin: '0 0 0 150',
            listeners: {
                'click': function (button, events, eOpts) {
                    var result = button.up().getSelectionParameters(button);

                    if (result) {
                        button.up().deleteAlbum(result.id, result.title);
                    }
                }
            }
        },
        {
            xtype: 'button',
            text: 'Random',
            margin: '0 0 0 150',
            listeners: {
                'click': function (button, events, eOpts) {
                    button.up().selectRandomAlbum();
                }
            }
        }
    ],
    getSelectionParameters: function(button) {
        var grid = button.up().up().items.getAt(1);
        var selModel = grid.getSelectionModel();
        var selRecord = selModel.getSelection()[0];

        if (selRecord) {
            var albumId = selRecord.get('album_id');
            var albumTitle = selRecord.get('album_title');
            
            return {grid: grid, id: albumId, title: albumTitle};
        } else {
            return false;
        }
    },
    deleteAlbum: function (albumId, albumTitle) {
        Ext.MessageBox.confirm('Confirmation required',
                               'Do you really want to delete "' + albumTitle +
                               '"?', function (btn) {
                                   if (btn == 'yes') {
                                       this.doDeleteRequest(albumId);
                                   }
                               }, this);
    },
    doDeleteRequest: function (albumId) {
        Ext.Ajax.request({
            url: '../php/dorequest.php',
            params: {
                cmd: 'delete_album',
                albumId: albumId
            },
            success: function(response){
                var text = response.responseText;
                
                if (text == 'true') {
                    Ext.Msg.alert('Delete Album',
                                  'Changes saved successfully.');
                    Ext.data.StoreManager.lookup('albumStore').reload();

                } else {
                    Ext.Msg.alert('Delete Album', 
                                  'Changes could not be saved.');
                }
            },
            failure: function(response) {
                Ext.Msg.alert('Delete Album', 'Could not contact server.');
            }
        });
    },
    selectRandomAlbum: function () {
        var store = Ext.data.StoreManager.lookup('albumStore');
        var num = store.getTotalCount();
        var toSelect = Ext.Number.randomInt(0, num - 1);
        var pageSize = store.getPageSize();
        var page = Math.floor(toSelect / pageSize) + 1;

        store.loadPage(page);
    },
    getDisplayButton: function () {
        return this.items.getAt(0);
    },
    getEditButton: function () {
        return this.items.getAt(1);
    },
    getDeleteButton: function () {
        return this.items.getAt(2);
    },
    getRandomButton: function () {
        return this.items.getAt(3);
    }
});
