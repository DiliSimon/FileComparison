<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <link rel="stylesheet" href="styles.css" type="text/css" charset="utf-8"/>
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
                    $date = date("F/d/Y/H:i:s", filemtime($filepath));
                    $size = filesize($filepath).' bytes';
                    echo "<div class='row'>";
                    echo "<div class='col-3' style='margin-bottom: 0.6em'>$file</div>
                    <div class='col-2'>$size</div>
                    <div class='col-2' style='text-align: left'>$date</div>
                    <div class=\"col - 5\" style='text-align: right'><button style='visibility: hidden' onclick=\"transferDoc("."'$file'".")\" class='btn btn-primary btn-sm' > Transfer</button >&emsp;<button style = 'visibility: hidden' onclick = \"displayDoc("."'$file'".")\" class=\"btn btn-primary btn-sm\" > Compare</button ></div >";
                    echo '</div>';
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

    $new_mirrorFile_list = array();

    foreach ($mirror_list as $file){
        $filepath = $livefoldername."/".$file;
        $mirrorpath = $mirrorfoldername.'/'.$file;
        // Files that are new
        if(!file_exists($filepath)) {
                try {
                    $date = date("F/d/Y/H:i:s", filemtime($mirrorpath));
                }catch (Exception $e){
                    $date = 'N/A';
                }
                try {
                    $size = filesize($mirrorpath).' bytes';
                }catch (Exception $e){
                    $size = 'N/A';
                }
                array_push($new_mirrorFile_list, $file);

            /* echo "<div class=\"row\">";
            echo "<div class=\"col-2\" style='margin-bottom: 0.6em'>$file&emsp;</div>
            <div class=\"col-2\">NEW</div>
            <div class=\"col-2\">$size</div>
            <div class=\"col-2\">$date</div>
            <div class=\"col-4\" style='text-align: right'><button onclick=\"transferDoc("."'$file'".")\" class='btn btn-primary btn-sm'>Transfer</button></div>";
            echo "</div>"; */
        }elseif (filemtime($filepath)!=filemtime($mirrorpath)){
            $if_warn = 'N';

            if(filemtime($filepath) > filemtime($mirrorpath)){
                $if_warn = 'Y';
            }

            try {
                $date = date("F/d/Y/H:i:s", filemtime($mirrorpath));
            }catch (Exception $e){
                $date = 'N/A';
            }
            try {
                $size = filesize($mirrorpath).' bytes';
            }catch (Exception $e){
                $size = 'N/A';
            }
            echo "<div class=\"row\">";
            echo "<div class=\"col-3\" style='margin-bottom: 0.6em; font-weight: bold'>$file</div>
            <div class=\"col-2\">$size</div>
            <div class=\"col-2\">$date</div>
            <div class=\"col-5\" style='text-align: right'><button onclick=\"transferDoc("."'$file'".",'$if_warn'".")\" class=\"btn btn-primary btn-sm\">Transfer</button>&emsp;<button onclick=\"displayDoc("."'$file'".")\" class=\"btn btn-primary btn-sm\">Compare</button></div>";
            echo "</div>";
        }elseif (filemtime($filepath)==filemtime($mirrorpath)){
            $if_warn = 'N';

            if(filemtime($filepath) > filemtime($mirrorpath)){
                $if_warn = 'Y';
            }

            try {
                $date = date("F/d/Y/H:i:s", filemtime($mirrorpath));
            }catch (Exception $e){
                $date = 'N/A';
            }
            try {
                $size = filesize($mirrorpath).' bytes';
            }catch (Exception $e){
                $size = 'N/A';
            }
            echo "<div class=\"row\">";
            echo "<div class=\"col-3\" style='margin-bottom: 0.6em; font-weight: bold'>$file</div>
            <div class=\"col-2\">$size</div>
            <div class=\"col-2\">$date</div>
            <div class=\"col-5\" style='text-align: right'><button onclick=\"transferDoc("."'$file'".",'$if_warn'".")\" class=\"btn btn-primary btn-sm\">Transfer</button>&emsp;<button onclick=\"displayDoc("."'$file'".")\" class=\"btn btn-primary btn-sm\">Compare</button></div>";
            echo "</div>";
        }
    }

    foreach ($new_mirrorFile_list as $file){
        $mirrorpath = $mirrorfoldername.'/'.$file;
        try {
            $date = date("F/d/Y/H:i:s", filemtime($mirrorpath));
        }catch (Exception $e){
            $date = 'N/A';
        }
        try {
            $size = filesize($mirrorpath).' bytes';
        }catch (Exception $e){
            $size = 'N/A';
        }

        echo "<div class=\"row\">";
        echo "<div class=\"col-3\" style='margin-bottom: 0.6em'>$file</div>
        <div class=\"col-2\">$size</div>
        <div class=\"col-2\">$date</div>
        <div class=\"col-5\" style='text-align: right'><button onclick=\"transferDoc("."'$file'".")\" class='btn btn-primary btn-sm'>Transfer</button>&emsp;<button style='visibility: hidden' onclick=\"displayDoc("."'$file'".")\" class=\"btn btn-primary btn-sm\">Compare</button></div>";
        echo "</div>";
    }
}
?>

<div style="background-color: lightgray; width: 90%; margin: auto; margin-bottom: 1.6em; margin-top: 2em;height: 3em" class="row rounded shadow">
    <div class="col" style="text-align: center; font-size: 1.5em; vertical-align: center">Mirror</div>
    <div class="col" style="text-align: center; font-size: 1.5em">Live</div>
</div>

<div id="documents" class="row shadow p-3 mb-5 bg-white rounded container-fluid" style="text-align: left; margin-bottom:1.5em;width: 96%; margin: auto">
        <div class="col-6" name="mirror" style="border-style: solid; border-color: lightgray">

            <?php
            //print_files("Old");
            ?>
            <?php
                find_difference("New","Old");
            ?>

        </div>
        <div class="col-6" name="live" style="border-style: solid; border-color: lightgray">
            <?php
                print_files("Old");
            ?>
        </div>
</div>

<div id="status" style="margin: auto;width: 90%; text-align: center; font-size: large">
</div>

<div id="table"></div>

<script type="text/javascript">
    function transferDoc(file,if_warn) {
            if(if_warn == 'Y'){
                var r = confirm("Live file is newer. Confirm to overwrite.");
                if(!r){
                    return;
                }
            }
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
        document.getElementById('status').innerHTML = 'Loading...';
        $("#table").empty();
        var mirror_path = "./New/"+file, live_path = "./Old/"+file;
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
            xmlhttp=new XMLHttpRequest();
        }
        else
        {
            // IE6, IE5 浏览器执行代码
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var table = xmlhttp.responseText;
                if(table == ''){
                    document.getElementById('status').innerHTML = 'No difference between mirror and live';
                }else {
                    document.getElementById('status').innerHTML = ' ';
                    $("#table").append(table);
                }
            }
        }
        xmlhttp.open("GET","displayDoc.php?LIVE=" + live_path + "&" + "MIRROR=" + mirror_path,true);
        xmlhttp.send();
    }

</script>
</body>
</html>