<?php
if(isset($_REQUEST['playlist'])) {
    $playlist = $_REQUEST['playlist'];
    $playlist_file = file("songs/$playlist");
    $songs = array();
    foreach ($playlist_file as $song_name) {
        if(strpos($song_name, '#') !== false)
            continue;
        $song_name = str_replace("\r\n","", $song_name);;
        $songs[] = "songs/$song_name";
    }
} else {
    $songs = glob('songs/*.mp3');
    $playlists = glob('songs/*.m3u');
}

if(isset($_REQUEST['shuffle']) && $_REQUEST['shuffle'] === 'on') {
    shuffle($songs);
}

if(isset($_REQUEST['bysize']) && $_REQUEST['bysize'] === 'on') {
    usort($songs, "size_compare");
}

function size_compare(string $song_1, string $song_2): int {
    return filesize($song_2) - filesize($song_1);
}

function verbose_size($size): string {
    $sizes = array("b", "kb", "mb", "gb", "tb");
    $i=0;
    while($size>1024) {
        $size = round($size/1024, 2);
        $i++;
    }
    return "$size $sizes[$i]";
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Music Viewer</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="viewer.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div id="header">

    <h1>190M Music Playlist Viewer</h1>
    <h2>Search Through Your Playlists and Music</h2>
</div>


<div id="listarea">
    <ul id="musiclist">
        <?php if(isset($playlist)) { ?>
            <li>
                <a href="index.php">Go back</a>
            </li>
        <?php } ?>

        <?php foreach ($songs as $song) {
            $filename = basename($song);
            $filesize = verbose_size(filesize($song));?>
            <li class="mp3item">
                <a href="<?= $song?>" download><?= $filename ?></a>
                (<?= $filesize ?>)
            </li>
        <?php }?>

        <?php
        if (isset($playlists)) {
            foreach ($playlists as $playlist) {
                $filename = basename($playlist);?>
                <li class="playlistitem">
                    <a href="/?playlist=<?= $filename ?>"><?= $filename ?></a>
                </li>
            <?php }}?>
    </ul>
</div>
</body>
</html>
