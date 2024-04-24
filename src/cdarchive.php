<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2015, 2016, 2017 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

class CDArchive {

  private $con;

  /**
   * Constructs a new object and initializes the database connection
   * specified by the given connection string.
   *
   * @param connectionString a PostgreSQL connection string
   */
  public function __construct($connectionString) {
    $this->con = pg_connect($connectionString);

    if (!$this->con) {
      die("Could not connect to database!");
    }
  }

  /**
   * Closes the database connection and destroys the object.
   */
  public function __destruct() {
    if ($this->con) pg_close($this->con);
  }

  /**
   * Executes a database query which does not give a result set.
   *
   * @param query the database query
   * @return true on success, false otherwise
   */
  private function executeCommand($query) {
    $r = pg_query($this->con, $query);

    if ($r) {
      pg_free_result($r);
      return true;
    } else {
      return false;
    }
  }

  /**
   * Starts a new database transaction.
   *
   * @return true on success, false otherwise
   */
  public function beginTransaction() {
    return $this->executeCommand('begin');
  }

  /**
   * Commits a database transaction.
   *
   * @return true on success, false otherwise
   */
  public function commitTransaction() {
    return $this->executeCommand('commit');
  }
  
  /**
   * Cancels a database transaction.
   *
   * @return true on success, false otherwise
   */
  public function rollbackTransaction() {
    return $this->executeCommand('rollback');
  }

  /**
   * Executes a database query which does return a result set.
   *
   * @param query the query
   * @return an array of result objects
   */
  private function getItems($query) {
    $result = pg_query($this->con, $query);
    $items = array();

    while ($data = pg_fetch_object($result)) {
      $items[] = $data;
    }

    pg_free_result($result);

    return $items; 
  }

  /**
   * Executes a database query which returns a single number (e.g. a count).
   *
   * @param query the query
   * @return the number
   */
  private function getItemCount($query) {
    $result = pg_query($this->con, $query);

    $count = 0;

    if ($data = pg_fetch_array($result, null, PGSQL_NUM)) {
      $count = $data[0];
      pg_free_result($result);
    }

    return $count;
  }

  /**
   * Constructs an appropiate filter clause for a database query. All filters
   * are connected by "and" operators.
   *
   * @param filters an array with filter conditions
   * @return the filter clause
   */
  private function expandAlbumFilters($filters) {
    $result = "";

    $filterExpansion = array('artist_id' => 'and a2.artist_id',
                             'genre_id' => 'and a.album_genre_id',
                             'location_id' => 'and a.album_location_id ',
                             'year' => 'and a.album_year',
                             'original' => 'and a.album_original',
                             'song' => 'and lower(s.song_title) like ');

    foreach ($filterExpansion as $param => $clause) {
      if (array_key_exists($param, $filters))

        if ($param === 'song' && strlen($filters[$param]) > 0) {
          $result .= $clause . 'lower(\'%' .
          pg_escape_string($this->con, $filters[$param]) . '%\')';
        } else if ((int) $filters[$param] >= 0) {
          $result .= $clause . ' = ' . (int) $filters[$param] . ' ';
        } else if ($filters[$param] === -1) { // -1 represents 'null'
          $result .= $clause . ' is null ';
        }
    }
    
    return $result;
  }

  /**
   * Constructs a join clause for the songs table if appropiate for the 
   * given filters.
   *
   * @param filters an array with filter conditions
   * @return the join clause if required
   */
  private function getSongFilter($filters) {
    $songFilter = '';

    if (array_key_exists('song', $filters)) {
      $songFilter = 'inner join tbl_songs as s ' .
        'on s.album_id = a.album_id ';
    }

    return $songFilter;
  }

  /**
   * Returns an array of album objects.
   *
   * @param offset the offset of the first album to return
   * @param limit the count of albums to return
   * @param filters an array of filters to apply
   * @param random wether to sort randomly
   * @return an array of album objects
   */
  public function getAlbums($offset, $limit, $filters, $random = false) {

    $query = "select a.album_id, artist_name, " .
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

    if (!$random) {
        $query .= "order by artist_name, album_year, album_title ";
    } else {
        $query .= "order by random() ";
    }

    $query .= "offset " . (int) $offset . " limit " . (int) $limit;
    
    return $this->getItems($query);
  }

  /**
   * Returns the album count for the specified filters.
   *
   * @param filters an array with filters to apply
   * @return the count of the filtered albums
   */
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

  /**
   * Returns an array of objects containing the songs of an individual album.
   *
   * @param albumId the ID of the album
   * @param offset the offset of the song to start with
   * @param limit the count of songs to return
   * @return an array of objects with the songs of the album
   */
  public function getAlbumSongs($albumId, $offset, $limit) {
    return $this->getItems("select track_number, song_title " . 
                           "from tbl_songs " . 
                           "where album_id = " . (int) $albumId . " " .
                           "order by track_number " .
                           "offset " . (int) $offset . " " .
                           "limit " . (int) $limit);
  }

  /**
   * Returns the count of songs for the specified album.
   *
   * @param albumId the ID of the album
   * @return the count of songs for the album specified
   */
  public function getAlbumSongCount($albumId) {
    return $this->getItemCount("select count(1) as item_count " . 
                               "from tbl_songs " .
                               "where album_id = " . (int) $albumId);
  }

  /**
   * Returns an array of objects containing all available genres.
   *
   * @return an array with objects containing all available genres
   */
  public function getGenres() {
    return $this->getItems("select genre_id, genre " . 
                           "from tbl_genres " .
                           "order by genre");
  }

  /**
   * Returns the count of all available genres.
   *
   * @return the count of all genres
   */
  public function getGenreCount() {
    return $this->getItemCount("select count(1) as item_count " . 
                               "from tbl_genres");
  }

  /**
   * Return an array of objects containing all available artists.
   *
   * @return an array of objects containing all available artists
   */
  public function getArtists() {
    return $this->getItems("select artist_id, artist_name " . 
                           "from tbl_artists " .
                           "order by artist_name");
  }

  /**
   * Returns the count of all artists available.
   *
   * @return the count of all artists available
   */
  public function getArtistCount() {
    return $this->getItemCount("select count(1) as item_count " . 
                               "from tbl_artists");
  }

  /**
   * Returns an array of objects containing all locations available.
   *
   * @return an array of objects containing all locations available
   */
  public function getLocations() {
    return $this->getItems("select location_id, location_desc " . 
                           "from tbl_locations " .
                           "order by location_desc");
  }

  /**
   * Returns the count of all locations available.
   *
   * @return the count of all locations
   */
  public function getLocationCount() {
    return $this->getItemCount("select count(1) as item_count " . 
                               "from tbl_locations");
  }

  /**
   * Returns all distinct years for which albums exist. A null value may be
   * included for albums with unspecified year.
   *
   * @return an array of objects containing all distinct years
   */
  public function getYears() {
    return $this->getItems("select distinct album_year " . 
                           "from tbl_albums " .
                           "order by album_year");
  }

  /**
   * Returns the count of all distinct years.
   *
   * @return the count of all distinct years
   */
  public function getYearCount() {
    /*
    return $this->getItemCount("select count (distinct(album_year)) " .
                               "from tbl_albums");
    */

    return $this->getItemCount("select count(1) from " .
                               "(select distinct(a.album_year) " .
                               "from tbl_albums as a) as years;");
  }

  /**
   * Adds a new artist if he does not already exist.
   *
   * @param artist the name of the artist to add
   * @return true if the artist has been added false otherwise
   */
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

  /**
   * Adds a new genre if it does not already exist.
   *
   * @param genre the name of the genre to add
   * @return true if the genre has been added false otherwise
   */
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

  /**
   * Adds a new location if it does not already exist.
   * 
   * @param location the name of the location to add
   * @return true if the location has been added false otherwise
   */
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

  /**
   * Adds songs to an album. An empty array of songs is accepted too.
   *
   * @param songs a two dimensional array containing the song information
   * @param albumId the ID of the album to which the songs belong
   * @return true if the songs have been added successfully false otherwise
   */
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

  /**
   * Adds a complete album. 
   *
   * @param album an object describing the album and its songs.
   * @return true if the album has been added succesfully false otherwise
   */
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
                                             "set album_number = $1 " .
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

  /**
   * Executes a query as prepared statement which does not return a result.
   *
   * @param name the name of the statement (should be unique)
   * @param query the query
   * @param params an array with the parameters for the query
   * @return true if successful false otherwise
   */
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

  /**
   * Deletes the album metadata.
   *
   * @param albumId the ID of the album to delete
   * @return true if successful false otherwise
   */
  private function deleteAlbumData($albumId) {
    return $this->executeStatement("deleteAlbum", 
                                   "delete from tbl_albums where album_id = $1",
                                   array((int) $albumId));
  }

  /**
   * Deletes the songs belonging to an album.
   *
   * @param albumId the ID of the album to which the songs belong
   * @return true if successful false otherwise
   */
  private function deleteAlbumSongs($albumId) {
    return $this->executeStatement("deleteAlbumSongs", 
                                   "delete from tbl_songs where album_id = $1",
                                   array((int) $albumId));
  }

  /**
   * Deletes the (primary) relation of an artist to an album.
   *
   * @param albumId the ID of the album to which the artists had been assigned
   * @return true if successful false otherwise
   */
  private function deleteAlbumArtist($albumId) {
    return $this->executeStatement("deleteAlbumArtist", 
                                   "delete from tbl_album_artist " .
                                   "where album_id = $1",
                                   array((int) $albumId));
  }

  /**
   * Deletes an album and all data depending on it (songs, artist relation).
   *
   * @param albumId the ID of the album
   * @return true if successful false otherwise
   */
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

  /**
   * Updates the songs belonging to an album.
   *
   * @param songs a two dimensional array describing the songs
   * @param albumId the ID of the album to which the songs belong
   * @return true if successful false otherwise
   */
  private function updateAlbumSongs($songs, $albumId) {
    if ($this->deleteAlbumSongs($albumId)) {
      return $this->addAlbumSongs($songs, $albumId);
    } else {
      return false;
    }
  }

  /**
   * Updates an album and its related data (songs, artist).
   *
   * @param album an object describing the album
   * @param albumId the ID of the album to update
   * @return true if successful false otherwise
   */
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

  /**
   * Returns an object describing an album including its songs.
   *
   * @param albumId the ID of the album
   * @return an object describing the album
   */
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

  /**
   * Returns the distribution of the different genres.
   *
   * @return an array of result objects with the genre name including percentage
   * and the absolute count of albums for the genre
   */ 
  public function getGenreStatistic() {
    $query = "select concat(stat.genre, ' (', " .
              "round((stat.genre_count * 100.0 / total.total_count), 2), " .
              "' %)') as genre, stat.genre_count " .
              "from ( " .
              "select count(a.album_id) as genre_count, g.genre " .
              "from tbl_albums as a inner join tbl_genres as g " .
              "on a.album_genre_id = g.genre_id " .
              "group by g.genre " .
              "order by genre_count desc " .
              ") as stat " .
              "inner join ( " .
              "select count(a2.album_id) as total_count " .
              "from tbl_albums a2 " .
              ") as total on true";
    
    return $this->getItems($query);
  }

  /**
   * Return the count of albums over the different years.
   *
   * @return an array of result objects with the year and the count of albums
   * for this year
   */
  public function getAlbumTimeline() {
    $query = "select album_year, count(album_id) as count " .
             "from tbl_albums " .
             "where album_year is not null " .
             "group by album_year " .
             "order by album_year";

    return $this->getItems($query);
  }

  /**
   * Return the top ten artists with the most albums.
   *
   * @return an array of result objects with the top ten artists and their
   * respective album count
   */
  public function getTopTenArtists() {
    $query = "select artist_name, count from (" .
             "select a.artist_name, count(rel.album_id) " .
             "from tbl_artists as a " .
             "inner join tbl_album_artist rel " .
             "on a.artist_id = rel.artist_id " .
             "group by a.artist_name " .
             "order by count desc ".
             "limit 10 " .
             ") as tmp " .
             "order by count asc";

    return $this->getItems($query);
  }
}
