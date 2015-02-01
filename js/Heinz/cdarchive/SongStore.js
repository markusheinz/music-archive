/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.SongStore', {
    extend: 'Ext.data.Store',
    storeId: 'songStore',
    model: 'Heinz.cdarchive.Song',
    pageSize: 10,
    proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
        extraParams: {
            cmd: 'album_detail'
        }
    }),
    autoLoad: true
});
