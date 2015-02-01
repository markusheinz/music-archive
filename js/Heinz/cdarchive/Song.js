/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.Song', {
    extend: 'Ext.data.Model',
    fields: [
	{name: 'track_number', type: 'string'},
        {name: 'song_titel', type: 'string'}
    ]
});
