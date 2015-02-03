/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.ItemFilter', {
    extend: 'Heinz.cdarchive.LabelForm',
    initComponent: function () {
        this.formItem = Ext.create('Ext.form.field.ComboBox', {
            store: this.store,
            displayField: this.displayField,
            valueField: this.valueField,
            margin: '0 5 0 0',
            queryMode: 'local',
        });
        
        this.callParent();
    },
    reload: function () {
        this.items.getAt(1).getStore().reload();
    }
});
