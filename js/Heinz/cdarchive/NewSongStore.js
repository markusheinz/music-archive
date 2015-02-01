/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2014 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.NewSongStore', {
    extend: 'Ext.data.ArrayStore',
    storeId: 'newSongStore',
    fields: [
       {name: 'index', type: 'int'},
       {name: 'title', type: 'string'},
    ],
    autoSync: true,
    songCountValue: 12,
    autoLoad: true,
    initializeSongStoreData: function () {
        this.songStoreArray = [];
        
        for (var i = 0; i < this.songCountValue; i++) {
            this.songStoreArray[i] = [i + 1, ''];
        }
        this.setData(this.songStoreArray);
    },
    songCountChange: function (slider, newValue, thumb, eOpts) {
        var oldValue = this.songCountValue;
        var difference = newValue - oldValue;

        if (difference > 0) {
            for (var j = 0; j < difference; j++) {
                this.add({index: oldValue + j + 1, title: ''});
            }
        } else if (difference < 0) {
            difference *= -1;
            this.removeAt(this.getCount() - difference, difference);
        }

        if (difference != 0) {
            this.songCountValue = newValue;
        }
    },
    listeners: {
        'load': function() {
            this.initializeSongStoreData();
        }
    }
});
