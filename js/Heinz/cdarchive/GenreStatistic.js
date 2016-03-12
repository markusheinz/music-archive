/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2016 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

Ext.define('Heinz.cdarchive.GenreStatistic', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'genre', type: 'string'},
        {name: 'genre_count', type: 'int'}
    ]
});
