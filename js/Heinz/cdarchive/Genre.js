/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.Genre', {
    extend: 'Ext.data.Model',
    fields: [
	{name: 'genre_id', type: 'int'},
        {name: 'genre', type: 'string'}
    ]
});
