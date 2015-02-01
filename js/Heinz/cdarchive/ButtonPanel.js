/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.ButtonPanel', {
    extend: 'Ext.panel.Panel',
    layout: {
        type: 'hbox',
        align: 'stretch',
        padding: '10'
    },
    items: [
        {
            xtype: 'button',
            text: 'Reset',
            margin: '0 455 10 0',
        },
        {
            xtype: 'button',
            text: 'Submit',
            margin: '0 10 10 0',
        }
    ],
    getResetButton: function() {
        return this.items.getAt(0);
    },
    getSubmitButton: function () {
        return this.items.getAt(1);
    }
});
