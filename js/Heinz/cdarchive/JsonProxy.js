/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.JsonProxy', {
    extend: 'Ext.data.proxy.Ajax',
    url: '../php/getjson.php',
    reader: {
	type: 'json',
	rootProperty: 'result',
	totalProperty: 'count'
    }
});
