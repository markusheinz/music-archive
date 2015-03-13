<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
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
      
      $albums = $archive->getAlbums($_GET['start'], $_GET['limit'], $filters);
      HtmlSanitizer::sanitize($albums);
      $result = new JsonResult($albums, $archive->getAlbumCount($filters));
    }
    break;

  case 'album_detail':
    if (isset($_GET['id']) && isset($_GET['start']) && 
        isset($_GET['limit'])) {
      
      $songs = $archive->getAlbumSongs($_GET['id'], $_GET['start'], 
                                       $_GET['limit']);

      HtmlSanitizer::sanitize($songs);
      $result = new JsonResult($songs, 
                               $archive->getAlbumSongCount($_GET['id']));
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
    $genres = $archive->getGenres();
    HtmlSanitizer::sanitize($genres);

    $result = new JsonResult($genres, $archive->getGenreCount());
    break;

  case 'artist_list':
    $artists = $archive->getArtists();
    HtmlSanitizer::sanitize($artists);
   
    $result = new JsonResult($artists, $archive->getArtistCount());
    break;

    case 'location_list':
      $locations = $archive->getLocations();
      HtmlSanitizer::sanitize($locations);

      $result = new JsonResult($locations, $archive->getLocationCount());
    break;

    case 'year_list':
      $years = $archive->getYears();
      HtmlSanitizer::sanitize($years);

      $result = new JsonResult($years, $archive->getYearCount());
    break;
  }

  if (isset($result)) echo $result->toJson();
}
?>
