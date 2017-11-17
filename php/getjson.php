<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2016, 2017 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

require_once "config.php";
require_once "cdarchive.php";
require_once "jsonresult.php";
require_once "htmlsanitizer.php";

if (isset($_GET['cmd'])) {

  $archive = new CDArchive(CONNECTION_STRING);

  switch ($_GET['cmd']) {

    case 'album_list':
      if (isset($_GET['start']) && isset($_GET['limit'])) {

        $filters = array();
        $filterKeys = array('artist_id', 'genre_id', 'location_id', 'year', 
                            'original', 'song');

        foreach ($filterKeys as $key) {
          if (isset($_GET[$key])) {

            if ($key === 'song') {

              $filters[$key] = $_GET[$key];

            } else {

              $filters[$key] = (int) $_GET[$key];

              if (strlen($_GET[$key]) == 0) { // year may be unspecified
                $filters[$key] = -1; 
              }

            }
          }
        }
        
        if ($archive->beginTransaction()) {
          $albums = $archive->getAlbums($_GET['start'], $_GET['limit'], 
                                        $filters);
          HtmlSanitizer::sanitize($albums);
          $result = new JsonResult($albums, $archive->getAlbumCount($filters));
        
          $archive->commitTransaction();
        }
      }
      break;

    case 'album_detail':
      if (isset($_GET['id']) && isset($_GET['start']) && 
          isset($_GET['limit'])) {
        
        if ($archive->beginTransaction()) {
          $songs = $archive->getAlbumSongs($_GET['id'], $_GET['start'], 
                                           $_GET['limit']);

          HtmlSanitizer::sanitize($songs);
          $result = new JsonResult($songs, 
                                   $archive->getAlbumSongCount($_GET['id']));
                                   
          $archive->commitTransaction();
        }
      }
      break;

    case 'album_edit':
      if (isset($_GET['id'])) {
        
        $album = $archive->getAlbum($_GET['id']);

        if ($album) {
          HtmlSanitizer::sanitize($album);
          $result = new JsonResult($album, 1);
        } else {
          $result = new JsonResult(new stdClass, 0);
        }
      }
      break;

    case 'genre_list':
      if ($archive->beginTransaction()) {
        $genres = $archive->getGenres();
        HtmlSanitizer::sanitize($genres);

        $result = new JsonResult($genres, $archive->getGenreCount());
        
        $archive->commitTransaction();
      }
      break;

    case 'artist_list':
      if ($archive->beginTransaction()) {
        $artists = $archive->getArtists();
        HtmlSanitizer::sanitize($artists);
       
        $result = new JsonResult($artists, $archive->getArtistCount());
        
        $archive->commitTransaction();
      } 
      break;

    case 'location_list':
      if ($archive->beginTransaction()) {
        $locations = $archive->getLocations();
        HtmlSanitizer::sanitize($locations);

        $result = new JsonResult($locations, $archive->getLocationCount());
        
        $archive->commitTransaction();
      }
      break;

    case 'year_list':
      if ($archive->beginTransaction()) {
        $years = $archive->getYears();
        HtmlSanitizer::sanitize($years);

        $result = new JsonResult($years, $archive->getYearCount());
        
        $archive->commitTransaction();
      }
      break;
    
    case 'genre_statistic':
      if ($archive->beginTransaction()) {
        
        $statistic = $archive->getGenreStatistic();
        HtmlSanitizer::sanitize($statistic);
        $result = new JsonResult($statistic, sizeof($statistic));
        
        $archive->commitTransaction();
      }
      break;

    case 'album_timeline':
      if ($archive->beginTransaction()) {

        $timeline = $archive->getAlbumTimeline();
        HtmlSanitizer::sanitize($timeline);
        $result = new JsonResult($timeline, sizeof($timeline));

        $archive->commitTransaction();
      }
      break;
  }

  if (isset($result)) echo $result->toJson();
}
