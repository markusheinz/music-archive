/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2017 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.AlbumTimeline', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'album_year', type: 'int'},
        {name: 'count', type: 'int'}
    ]
});
