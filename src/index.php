<?php
/*
 * Open Source Music Collection Database (working title)
 *
 * (c) 2023 Markus Heinz
 * 
 * Licensed under the GPL v3.0
 */

require_once "config.php";
require_once "cdarchive.php";
require_once "htmlsanitizer.php";

$archive = new CDArchive(CONNECTION_STRING);
$albums = [];

if ($archive->beginTransaction()) {
    $albums = $archive->getAlbums(0, 10000, []); // get 10,000 albums
    HtmlSanitizer::sanitize($albums); // strip HTML tags
    $archive->commitTransaction();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Music Archive</title>
        <script type="text/javascript" src="js/jquery-3.7.0.js"/></script>
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    </head>

    <body>
        <div style="padding: 10px;">
            <table id="albums" class="table table-striped" style="width: 100%">
                <thead>
                    <tr>
                        <td>Artist</td>
                        <td>Title</td>
                        <td>Year</td>
                        <td>Location</td>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($albums as $album) {?>

                    <tr>
                        <td><?php echo $album->artist_name; ?></td>
                        <td><?php echo $album->album_title; ?></td>
                        <td><?php echo $album->album_year; ?></td>
                        <td><?php echo $album->location_desc; ?></td>
                    </tr>

                    <?php }?>

                </tbody>
            </table>
        </div>
        <script>new DataTable('#albums');</script>
    </body>
</html>
