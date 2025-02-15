/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.LabelForm', {
    extend: 'Ext.panel.Panel',
    layout: {
        type: 'hbox',
        align: 'stretch',
        padding: 10
    },
    initComponent: function() {
        this.items = [
            {
                xtype: 'label',
                text: this.labelText,
                margin: '0 10 0 0',
                width: 55
           }           
        ];

        if (this.formItem instanceof Array) {
            for (var i = 0; i < this.formItem.length; i++) {
                this.items[i + 1] = this.formItem[i];
            }
        } else {
            this.items[1] = this.formItem;
        }

        this.callParent();
    },
    getValue: function() {
        return this.items.getAt(1).value;
    },
    reset: function() {
        this.items.getAt(1).reset();
    },
    setValue: function(value) {
        this.items.getAt(1).setValue(value);
    },
    reload: function() {
	// nothing to do
    }
});
