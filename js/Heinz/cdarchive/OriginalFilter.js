/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.OriginalFilter', {
    extend: 'Heinz.cdarchive.ItemFilter',
    labelText: 'Original:',
    displayField: 'text',
    valueField: 'value',
    store: Ext.create('Ext.data.Store', {
        model: Ext.create('Ext.data.Model', {
            fields: [
                {name: 'text', type: 'string'},
                {name: 'value', type: 'int'}
            ]
        }),
        data: [
            {text: 'unspecified', value: -1},
            {text: 'yes', value: 1},
            {text: 'no', value: 0}
        ],
    })
});
