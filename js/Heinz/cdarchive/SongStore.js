/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014, 2016 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.SongStore', {
    extend: 'Ext.data.Store',
    model: 'Heinz.cdarchive.Song',
    pageSize: 10,
    autoLoad: true
});
