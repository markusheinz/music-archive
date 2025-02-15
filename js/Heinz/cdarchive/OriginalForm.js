/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.OriginalForm', {
    extend: 'Heinz.cdarchive.LabelForm',
    labelText: 'Original:',
    formItem: {
        xtype: 'checkboxfield',
        margin: '0 10 0 0' 
    }
});
