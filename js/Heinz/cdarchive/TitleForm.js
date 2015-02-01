/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.TitleForm', {
    extend: 'Heinz.cdarchive.LabelForm',
    labelText: 'Title:',
    labelMargin: '0 88 0 0',
    formItem: {
        xtype: 'textfield',
        margin: '0 10 0 0' 
    }
});
