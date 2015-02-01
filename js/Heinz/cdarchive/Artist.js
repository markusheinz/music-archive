/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.Artist', {
    extend: 'Ext.data.Model',
    fields: [
	{name: 'artist_id', type: 'int'},
        {name: 'artist_name', type: 'string'}
    ]
});
