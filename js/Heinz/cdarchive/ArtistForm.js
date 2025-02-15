/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.ArtistForm', {
    extend: 'Heinz.cdarchive.ItemForm',
    labelText: 'Artist:',
    displayField: 'artist_name',
    valueField: 'artist_id',
    store: Ext.create('Heinz.cdarchive.ItemStore', {
        model: Ext.create('Heinz.cdarchive.Artist'),
        proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
            extraParams: {
                cmd: 'artist_list'
            }
        })
    }),
    addCmd: 'add_artist',
    headline: 'Add Artist',
});
