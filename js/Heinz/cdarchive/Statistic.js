/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2016 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.Statistic', {
    extend: 'Ext.panel.Panel',
    layout: {
        type: 'vbox',
        align: 'stretch',
        padding: '0',
    },
    title: 'Statistic',
    items: [
        Ext.create('Heinz.cdarchive.GenreStatisticView')
    ]
});

