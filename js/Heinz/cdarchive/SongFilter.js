/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.SongFilter', {
    extend: 'Heinz.cdarchive.LabelForm',
    labelText: 'Song:',
    formItem: {
        xtype: 'textfield',
        margin: '0 5 0 0',
        width: 170
    }
});
