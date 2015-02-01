/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.Location', {
    extend: 'Ext.data.Model',
    fields: [
	{name: 'location_id', type: 'int'},
        {name: 'location_desc', type: 'string'}
    ]
});
