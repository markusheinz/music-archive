/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.SongFilter', {
    extend: 'Heinz.cdarchive.LabelForm',
    labelText: 'Song:',
    labelMargin: '0 5 0 0',
    formItem: {
        xtype: 'textfield',
        margin: '0 5 0 0',
        width: 190
    }
});
