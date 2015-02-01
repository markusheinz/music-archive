/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.SongGridForm', {
    extend: 'Ext.panel.Panel',
    title: 'Song Details',
    width: 600,
    height: 400,
    layout: 'fit',
    items: {
        xtype: 'grid',
        border: false,
	title: '',
 	columns: {
            items: [
                { text: 'Track', dataIndex: 'index', width: 70 },
                { text: 'Title', dataIndex: 'title', width: 500,
                  editor: {
                      xtype: 'textfield',
                      allowBlank: false
                  }
                },
            ]
        },
        selType: 'cellmodel',
        plugins: {
            ptype: 'cellediting',
            clicksToEdit: 1
        },
        listeners:
        {
            'edit': function(editor, context, eOpts ) {
                var store = Ext.data.StoreManager.lookup('newSongStore');
                if (context.rowIdx < store.songCountValue - 1) {
                    var row = context.rowIdx + 1;
                    editor.startEdit(row, 1);
                }
            }
        },
    },
    initComponent: function() {
        this.config = this;
        this.callParent();
    },
    listeners: {
        'beforerender': function () {
            this.items.getAt(0).setStore(this.config.store);
        }
    },
});
