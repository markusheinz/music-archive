/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.YearForm', {
    extend: 'Heinz.cdarchive.LabelForm',
    labelText: 'Year:',
    formItem: {
        xtype: 'numberfield',
        margin: '0 10 0 0',
        minValue: 1900,
        maxValue: 2100,
        value: new Date().getFullYear()
    }
});
