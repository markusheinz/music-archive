/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2025 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.SongCountForm', {
    extend: 'Heinz.cdarchive.LabelForm',
    labelText: 'Number of Songs:',
    initComponent: function() {
        this.config = this;

        this.formItem = Ext.create('Ext.slider.Single', {
            margin: '0 10 0 0',
            width: 223,
            increment: 1,
            minValue: 0,
            maxValue: 99,
        });

        this.formItem.setValue(this.config.initialSongCount);
        this.formItem.resetOriginalValue();

        this.callParent();
    },
    listeners:
    {
        'beforerender': function () {
            this.items.getAt(1).addListener('change', 
                                            this.config.changeFunction, 
                                            this.config.context);
        },
    },
    setValue: function(value) {
        this.items.getAt(1).setValue(value, true);
    }
});
