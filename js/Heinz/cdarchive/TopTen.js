/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2017 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.TopTen', {
    extend: 'Ext.panel.Panel',
    layout: {
        type: 'vbox',
        align: 'stretch',
        padding: '0',
    },
    title: 'Top Ten',
    items: [
        Ext.create('Heinz.cdarchive.TopTenView')
    ]
});

