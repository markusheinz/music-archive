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

function validateItem($item) {
  if (isset($item)) {
    $length = strlen($item);

    if ($length > 0 && $length <= 128) {
      return true;
    }
  }
  
  return false;
}

function validateAlbum($album) {
  if ((int) $album->artist_id <= 0) return false;
  if (!validateItem($album->title)) return false;
  if ((int) $album->genre_id <= 0) return false;
  if ((int) $album->location_id <= 0) return false;

  if (is_array($album->songs) && sizeof($album->songs) > 0)
    foreach ($album->songs as $song)
      if (!validateItem($song[1]))
        return false;

  return true;
}

if (isset($_POST['cmd'])) {

  $archive = new CDArchive(CONNECTION_STRING);

  switch ($_POST['cmd']) {
  case 'add_artist':
    if (validateItem($_POST['item']))
      echo $archive->addArtist($_POST['item']) ? 'true' : 'false';
    else
      echo 'false';

    break;
  
  case 'add_genre':
    if (validateItem($_POST['item']))
      echo $archive->addGenre($_POST['item']) ? 'true' : 'false';
    else
      echo 'false';

    break;

  case 'add_location':
    if (validateItem($_POST['item']))
      echo $archive->addLocation($_POST['item']) ? 'true' : 'false';
    else
      echo 'false';

    break;

  case 'add_album':
    $album = json_decode($_POST['album']);
    
    if ($album && validateAlbum($album)) 
      echo $archive->addAlbum($album) ? 'true' : 'false';
    else 
      echo 'false';
    
    break;

  case 'delete_album':
    $albumId = (int) $_POST['albumId'];

    if ($albumId > 0) 
      echo $archive->deleteAlbum($albumId) ? 'true' : 'false';
    else
      echo 'false';

    break;

  case 'update_album':
    $album = json_decode($_POST['album']);
    $albumId = $_POST['id'];

    if ($album && validateAlbum($album) && (int) $albumId > 0) 
      echo $archive->updateAlbum($album, $albumId) ? 'true' : 'false';
    else 
      echo 'false';

    break;
  }
}

?>
