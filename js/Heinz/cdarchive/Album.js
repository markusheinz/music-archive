/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.Album', {
    extend: 'Ext.data.Model',
    fields: [
	{name: 'album_id', type: 'string'},
        {name: 'artist_name', type: 'string'},
        {name: 'album_title', type: 'string'},
        {name: 'album_year', type: 'string'},
        {name: 'location_desc', type: 'string'}
    ]
});
