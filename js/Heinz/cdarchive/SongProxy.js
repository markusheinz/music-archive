/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2016 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.SongProxy', {
    extend: 'Heinz.cdarchive.JsonProxy',
    extraParams: {
	cmd: 'album_detail'
    }
});
