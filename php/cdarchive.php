<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

class CDArchive {

  private $con;

  public function __construct($connectionString) {
    $this->con = pg_connect($connectionString);

    if (!$this->con) {
      die("Could not connect to database!");
    }
  }

  public function __destruct() {
    if ($this->con) pg_close($this->con);
  }

  private function executeCommand($query) {
    $r = pg_query($this->con, $query);

    if ($r) {
      pg_free_result($r);
      return true;
    } else {
      return false;
    }
  }

  public function beginTransaction() {
    return $this->executeCommand('begin');
  }

  public function commitTransaction() {
    return $this->executeCommand('commit');
  }
  
  public function rollbackTransaction() {
    return $this->executeCommand('rollback');
  }

  private function getItems($query) {
    $result = pg_query($this->con, $query);
    $items = array();

    while ($data = pg_fetch_object($result)) {
      $items[] = $data;
    }

    pg_free_result($result);

    return $items; 
  }

  private function getItemCount($query) {
    $result = pg_query($this->con, $query);

    $count = 0;

    if ($data = pg_fetch_array($result, null, PGSQL_NUM)) {
      $count = $data[0];
      pg_free_result($result);
    }

    return $count;
  }

  private function expandAlbumFilters($filters) {
    $result = "";

    $filterExpansion = array('artist_id' => 'and a2.artist_id',
                             'genre_id' => 'and a.album_genre_id',
                             'location_id' => 'and a.album_location_id ',
                             'year' => 'and a.album_year',
                             'original' => 'and a.album_original',
                             'song' => 'and s.song_title like ');

    foreach ($filterExpansion as $param => $clause) {
      if (array_key_exists($param, $filters))

        if ($param === 'song' && strlen($filters[$param]) > 0) {
          $result .= $clause . pg_escape_literal('%' . $filters[$param] . '%');
        } else if ((int) $filters[$param] >= 0) {
          $result .= $clause . ' = ' . (int) $filters[$param] . ' ';
        } else if ($filters[$param] === -1) { // -1 represents 'null'
          $result .= $clause . ' is null ';
        }
    }
    
    return $result;
  }

  private function getSongFilter($filters) {
    $songFilter = '';

    if (array_key_exists('song', $filters)) {
      $songFilter = 'inner join tbl_songs as s ' .
        'on s.album_id = a.album_id ';
    }

    return $songFilter;
  }

  public function getAlbums($offset, $limit, $filters) {

    $query = "select distinct a.album_id, artist_name, " .
      "album_title, album_year, location_desc " .
      "from tbl_albums as a " .
      "inner join tbl_album_artist as aa " .
      "on a.album_id = aa.album_id " .
      "inner join tbl_artists as a2 " .
      "on aa.artist_id = a2.artist_id " .
      "inner join tbl_locations as l " .
      "on a.album_location_id = l.location_id " .
      $this->getSongFilter($filters) .
      "where true ";

    $query .= $this->expandAlbumFilters($filters);

    $query .= "order by artist_name, album_year, album_title " .
      "offset " . (int) $offset . " " .
      "limit " . (int) $limit;
    
    return $this->getItems($query);
  }

  public function getAlbumCount($filters) {
    $query = "select count(distinct(a.album_id)) as item_count " .
      "from tbl_albums as a " .
      "inner join tbl_album_artist as aa " .
      "on a.album_id = aa.album_id " .
      "inner join tbl_artists as a2 " .
      "on aa.artist_id = a2.artist_id " .
      "inner join tbl_locations as l " .
      "on a.album_location_id = l.location_id " .
      $this->getSongFilter($filters) .
      "where true ";

    $query .= $this->expandAlbumFilters($filters);

    return $this->getItemCount($query);
  }

  public function getAlbumSongs($albumId, $offset, $limit) {
    return $this->getItems("select track_number, song_title " . 
                           "from tbl_songs " . 
                           "where album_id = " . (int) $albumId . " " .
                           "order by track_number " .
                           "offset " . (int) $offset . " " .
                           "limit " . (int) $limit);
  }

  public function getAlbumSongCount($albumId) {
    return $this->getItemCount("select count(1) as item_count " . 
                               "from tbl_songs " .
                               "where album_id = " . (int) $albumId);
  }

  public function getGenres() {
    return $this->getItems("select genre_id, genre " . 
                           "from tbl_genres " .
                           "order by genre");
  }

  public function getGenreCount() {
    return $this->getItemCount("select count(1) as item_count " . 
                               "from tbl_genres");
  }

  public function getArtists() {
    return $this->getItems("select artist_id, artist_name " . 
                           "from tbl_artists " .
                           "order by artist_name");
  }

  public function getArtistCount() {
    return $this->getItemCount("select count(1) as item_count " . 
                               "from tbl_artists");
  }

  public function getLocations() {
    return $this->getItems("select location_id, location_desc " . 
                           "from tbl_locations " .
                           "order by location_desc");
  }

  public function getLocationCount() {
    return $this->getItemCount("select count(1) as item_count " . 
                               "from tbl_locations");
  }

  public function getYears() {
    return $this->getItems("select distinct album_year " . 
                           "from tbl_albums " .
                           "order by album_year");
  }

  public function getYearCount() {
    /*
    return $this->getItemCount("select count (distinct(album_year)) " .
                               "from tbl_albums");
    */

    return $this->getItemCount("select count(1) from " .
                               "(select distinct(a.album_year) " .
                               "from tbl_albums as a) as years;");
  }

  public function addArtist($artist) {
    $duplicates = 0 ;
    $result = pg_prepare($this->con, "checkArtist", 
                         "select count(1) as duplicate_count " .
                         "from tbl_artists " .
                         "where artist_name = $1");
                         
    if ($result) {
      $result = pg_execute($this->con, "checkArtist", array($artist));

      if ($result) {

        if ($data = pg_fetch_object($result)) {
          $duplicates = (int) $data->duplicate_count;
        }

        pg_free_result($result);

        if ($duplicates === 0) {
          $result = pg_prepare($this->con, "addArtist", 
                               "insert into tbl_artists (artist_name) " .
                               "values ($1)");
          if ($result) {
            $result = pg_execute($this->con, "addArtist", array($artist));

            if ($result) {
              pg_free_result($result);
              return true;
            }
          }
        }
      }
    }

    return false;
  }

  public function addGenre($genre) {
    $duplicates = 0 ;
    $result = pg_prepare($this->con, "checkGenre", 
                         "select count(1) as duplicate_count " .
                         "from tbl_genres " .
                         "where genre = $1");
                         
    if ($result) {
      $result = pg_execute($this->con, "checkGenre", array($genre));

      if ($result) {

        if ($data = pg_fetch_object($result)) {
          $duplicates = (int) $data->duplicate_count;
        }

        pg_free_result($result);

        if ($duplicates === 0) {
          $result = pg_prepare($this->con, "addGenre", 
                               "insert into tbl_genres (genre) " .
                               "values ($1)");
          if ($result) {
            $result = pg_execute($this->con, "addGenre", array($genre));

            if ($result) {
              pg_free_result($result);
              return true;
            }
          }
        }
      }
    }

    return false;
  }

  public function addLocation($location) {
    $duplicates = 0 ;
    $result = pg_prepare($this->con, "checkLocation", 
                         "select count(1) as duplicate_count " .
                         "from tbl_locations " .
                         "where location_desc = $1");
                         
    if ($result) {
      $result = pg_execute($this->con, "checkLocation", array($location));

      if ($result) {

        if ($data = pg_fetch_object($result)) {
          $duplicates = (int) $data->duplicate_count;
        }

        pg_free_result($result);

        if ($duplicates === 0) {
          $result = pg_prepare($this->con, "addLocation", 
                               "insert into tbl_locations (location_desc) " .
                               "values ($1)");
          if ($result) {
            $result = pg_execute($this->con, "addLocation", array($location));

            if ($result) {
              pg_free_result($result);
              return true;
            }
          }
        }
      }
    }

    return false;
  }

  private function addAlbumSongs($songs, $albumId) {
    if (is_array($songs) && sizeof($songs) > 0) {

      if (pg_prepare($this->con, "addSong", 
                     "insert into tbl_songs " .
                     "(track_number, song_title, album_id) " .
                     "values ($1, $2, $3)")) {

        foreach ($songs as $song) {

          if (is_array($song) && sizeof($song) >= 2 && 
              $r = pg_execute($this->con, "addSong",
                              array((int) $song[0], $song[1], $albumId))) {
                              
            pg_free_result($r);
          } else {
            return false;
          }
        }

        return true;
      }
    } else if (is_array($songs) && sizeof($songs) === 0) {
      return true;
    }
    
    return false;
  }

  public function addAlbum($album) {
    if ($this->beginTransaction()) {

      if (pg_prepare($this->con, "addAlbum", 
                     "insert into tbl_albums " .
                     "(album_title, album_year, album_original, " .
                     "album_location_id, album_genre_id) " .
                     "values ($1, $2, $3, $4, $5)")) {

        if ($r = pg_execute($this->con, "addAlbum", 
                       array($album->title,
                             $album->year,
                             $album->original ? 1 : 0,
                             (int) $album->location_id, 
                             (int) $album->genre_id))) {

          pg_free_result($r);

          if (pg_prepare($this->con, "getAlbumId", 
                         "select lastval() as album_id")) {

            if ($r = pg_execute($this->con, "getAlbumId", array())) {

              $albumId = -1;

              if ($data = pg_fetch_object($r)) {
                $albumId = (int) $data->album_id;
              }

              pg_free_result($r);

              if ($albumId > 0 && pg_prepare($this->con, "updateAlbumNumber", 
                                             "update tbl_albums " .
                                             "set album_number = $1" .
                                             "where album_id = $2")) {

                if ($r = pg_execute($this->con, "updateAlbumNumber", 
                                    array($albumId, $albumId))) {

                  pg_free_result($r);
                  
                  if (pg_prepare($this->con, "addAlbumArtist",
                                 "insert into tbl_album_artist " .
                                 "(artist_id, album_id) " .
                                 "values ($1, $2)")) {

                    if ($r = pg_execute($this->con, "addAlbumArtist", 
                                        array((int) $album->artist_id,
                                              $albumId))) {

                      pg_free_result($r);
                      
                      if ($this->addAlbumSongs($album->songs, $albumId)) {
                      
                        if ($this->commitTransaction()) {
                          return true;
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    $this->rollbackTransaction();
    return false;
  }

  private function executeStatement($name, $query, $params) {
    $result = pg_prepare($this->con, $name, $query);

    if ($result) {
      $result = pg_execute($this->con, $name, $params);

      if ($result) {
        pg_free_result($result);
        return true;
      }
    }

    return false;
  }

  private function deleteAlbumData($albumId) {
    return $this->executeStatement("deleteAlbum", 
                                   "delete from tbl_albums where album_id = $1",
                                   array((int) $albumId));
  }

  private function deleteAlbumSongs($albumId) {
    return $this->executeStatement("deleteAlbumSongs", 
                                   "delete from tbl_songs where album_id = $1",
                                   array((int) $albumId));
  }

  private function deleteAlbumArtist($albumId) {
    return $this->executeStatement("deleteAlbumArtist", 
                                   "delete from tbl_album_artist " .
                                   "where album_id = $1",
                                   array((int) $albumId));
  }

  public function deleteAlbum($albumId) {
    if ($this->beginTransaction()) {
      $result = $this->deleteAlbumSongs($albumId);

      if ($result) {
        $result = $this->deleteAlbumArtist($albumId);

        if ($result) {
          $result = $this->deleteAlbumData($albumId);

          if ($result) {
            return $this->commitTransaction();
          }
        }
      }

      $this->rollbackTransaction();
    }
    
    return false;
  }

  private function updateAlbumSongs($songs, $albumId) {
    if ($this->deleteAlbumSongs($albumId)) {
      return $this->addAlbumSongs($songs, $albumId);
    } else {
      return false;
    }
  }

  public function updateAlbum($album, $albumId) {
    if ($this->beginTransaction()) {
      if ($this->executeStatement("updateAlbum",
                                  "update tbl_albums " .
                                  "set album_title = $1, " .
                                  "album_year = $2, " .
                                  "album_original = $3, " .
                                  "album_location_id = $4, " .
                                  "album_genre_id = $5 " .
                                  "where album_id = $6",
                                  array($album->title,
                                        $album->year,
                                        $album->original ? 1 : 0,
                                        (int) $album->location_id,
                                        (int) $album->genre_id,
                                        (int) $albumId))) {
        
        if ($this->executeStatement("updateAlbumArtist",
                                    "update tbl_album_artist " .
                                    "set artist_id = $1 " .
                                    "where album_id = $2",
                                    array((int) $album->artist_id, 
                                          (int) $albumId))) {
          
          if ($this->updateAlbumSongs($album->songs, $albumId)) {
            if ($this->commitTransaction()) {
              return true;
            }
          }
        }
      }
    }
    
    $this->rollbackTransaction();
    return false;
  }

  public function getAlbum($albumId) {
    if ($this->beginTransaction()) {

      $query = "select a.album_id, artist_id, " .
        "album_title, album_year, location_id, " .
        "genre_id, album_original " .
        "from tbl_albums as a " .
        "inner join tbl_album_artist as aa " .
        "on a.album_id = aa.album_id " .
        "inner join tbl_locations as l " .
        "on a.album_location_id = l.location_id " .
        "inner join tbl_genres as g " .
        "on a.album_genre_id = g.genre_id " .
        "where a.album_id = $1";

      $result = pg_prepare($this->con, "getAlbum", $query);

      if ($result) {
        
        $result = pg_execute($this->con, "getAlbum", array((int) $albumId));

        if ($result) {
          $album = pg_fetch_object($result);
          pg_free_result($result);

          $songs = $this->getAlbumSongs($albumId, 0, 1000); // First 1000 songs
          $this->commitTransaction();

          if ($album) {
            $album->songs = $songs;
            return $album;
          } else {
            return false;
          }         
        }
      }

      $this->rollBackTransaction();
    }
    
    return false;
  }
}

?>
