/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.GenreForm', {
    extend: 'Heinz.cdarchive.ItemForm',
    labelText: 'Genre:',
    displayField: 'genre',
    valueField: 'genre_id',
    store: Ext.create('Heinz.cdarchive.ItemStore', {
        model: Ext.create('Heinz.cdarchive.Genre'),
        proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
            extraParams: {
                cmd: 'genre_list'
            }
        })
    }),
    addCmd: 'add_genre',
    headline: 'Add Genre',
});
