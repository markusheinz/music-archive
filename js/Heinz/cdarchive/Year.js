/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.Year', {
    extend: 'Ext.data.Model',
    fields: [
	{name: 'album_year', type: 'string', useNull: true, 
         convert: function (value, row) {
             return value == null ? 'unknown' : 
                 value == -1 ? 'unspecified' : value;
         }}
    ]
});
