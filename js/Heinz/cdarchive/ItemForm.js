/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.ItemForm', {
    extend: 'Heinz.cdarchive.LabelForm',
    initComponent: function() {
        this.formItem = [
            {
                xtype: 'combo',
                store: this.store,
                displayField: this.displayField,
                valueField: this.valueField,
                margin: '0 10 0 0' 
            },
            {
                xtype: 'button',
                text: 'New'
            }
        ];

        this.config = this;
        this.callParent();        
    },
    listeners: {
        'beforerender': function () {
            this.items.getAt(2).addListener('click', this.addItem, this, 
                                            {handlerClass: this});
        }
    },
    addItem: function (scope, e, eOpts) {
        var panel = eOpts.handlerClass;
        var newItem = this.getValue();
        var config = this.config;

        if (newItem != null && newItem.length > 0) {
            Ext.Ajax.request({
                url: '../php/dorequest.php',
                params: {
                    cmd: config.addCmd,
                    item: newItem
                },
                success: function(response){
                    var text = response.responseText;

                    if (text == 'true') {
                        Ext.Msg.alert(config.headline,
                                      'Changes saved successfully.');
                        
                        panel.items.getAt(1).getStore().reload();
                        panel.reset();

                        Ext.getCmp('FilterPanel').reload();
                    } else {
                        Ext.Msg.alert(config.headline,
                                      'Changes could not be saved.');
                    }
                },
                failure: function(response) {
                    Ext.Msg.alert(config.headline, 'Could not contact server.')
                }
            });
        }
    },
});
