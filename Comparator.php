<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <title>File Comparison</title>
</head>
<body>

<?php
session_start();

function print_files($mirrorfoldername){
    $mirror_list = array();
    $live_list = array();
    if (is_dir($mirrorfoldername)){
        if ($dh = opendir($mirrorfoldername)) {
            while (($file = readdir($dh)) !== false) {
                if($file != "." && $file != "..") {
                    $filepath = $mirrorfoldername."/".$file;
                    echo "<p>".$file." ".date("F d Y H:i:s.", filemtime($filepath))."</p>";
                    array_push($mirror_list, $file);
                }
            }
            closedir($dh);
        }
    }
}

function find_difference($mirrorfoldername, $livefoldername){
    $mirror_list = array();
    $live_list = array();
    if (is_dir($mirrorfoldername)){
        if ($dh = opendir($mirrorfoldername)) {
            while (($file = readdir($dh)) !== false) {
                if($file != "." && $file != "..") {
                    $filepath = $mirrorfoldername."/".$file;
                    array_push($mirror_list, $file);
                }
            }
            closedir($dh);
        }
    }
    foreach ($mirror_list as $file){
        $filepath = $livefoldername."/".$file;
        if(!file_exists($filepath)) {
            echo "<div style='margin-bottom: 0.6em'>$file&emsp;NEW&emsp;<button onclick=\"transferDoc("."'$file'".")\" class='btn btn-primary btn-sm'>Transfer</button></div>";
        }elseif (filemtime($filepath)!=filemtime($mirrorfoldername."/".$file)){
            echo "<div style='margin-bottom: 0.6em'>$file&emsp;UPDATED&emsp;<button onclick=\"transferDoc("."'$file'".")\" class=\"btn btn-primary btn-sm\">Transfer</button>&emsp;<button onclick=\"displayDoc("."'$file'".")\" class=\"btn btn-primary btn-sm\">Compare</button></div>"; # TODO: Add in displayDoc() parameter.
        }
    }
}
?>

<div style="background-color: lightgray; width: 65%; margin: auto; margin-bottom: 1.6em; margin-top: 2em;height: 3em" class="row rounded shadow">
    <div class="col" style="text-align: center; font-size: 1.5em; vertical-align: center">Mirror</div>
    <div class="col" style="text-align: center; font-size: 1.5em">Live</div>
</div>

<div id="documents" class="row shadow p-3 mb-5 bg-white rounded" style="text-align: left; margin-bottom:1.5em;width: 65%; margin: auto">
        <div class="col" name="mirror" style="border-style: solid; border-color: lightgray">
        <?php
            find_difference("New","Old");
        ?>
        </div>
        <div class="col" name="live" style="border-style: solid; border-color: lightgray">
            <?php
                print_files("Old");
            ?>
        </div>
</div>

<div id="display" class="row shadow p-3 mb-5 bg-white rounded" style="margin: auto;width: 70%; display: none">
    <div class="col" id="mirrorfile" style="overflow-y: scroll; height: 10em;"></div>
    <div class="col" id="livefile" style="overflow-y: scroll; height: 10em"></div>
</div>

<script type="text/javascript">
    function transferDoc(file) {
            var mirror_path = "./New/"+file, live_path = "./Old/"+file, backup_path = "./backup/"+file;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    window.location.reload(true);
                }
            };
            xmlhttp.open("GET", "transferDoc.php?mirror_path=" + mirror_path + "&live_path=" + live_path + "&backup_path=" + backup_path, true);
            xmlhttp.send();
        }

    function displayDoc(file){
        var mirror_path = "./New/"+file, live_path = "./Old/"+file;
        document.getElementById("display").style.display = ""; // TODO: Add display document function.
        $("#livefile").load(mirror_path);
        $("#mirrorfile").load(live_path);
    }

</script>
</body>
</html>