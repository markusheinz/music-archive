/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2017 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.ArtistTopTen', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'artist_name', type: 'string'},
        {name: 'count', type: 'int'}
    ]
});
