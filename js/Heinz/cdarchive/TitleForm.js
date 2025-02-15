/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.TitleForm', {
    extend: 'Heinz.cdarchive.LabelForm',
    labelText: 'Title:',
    formItem: {
        xtype: 'textfield',
        margin: '0 10 0 0' 
    }
});
