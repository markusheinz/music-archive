/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.AlbumStore', {
    extend: 'Ext.data.Store',
    storeId: 'albumStore',
    pageSize: 20,
    model: 'Heinz.cdarchive.Album',
    proxy: Ext.create('Heinz.cdarchive.JsonProxy', {
        extraParams: {
            cmd: 'album_list'
        }
    }),
    autoLoad: true
});
