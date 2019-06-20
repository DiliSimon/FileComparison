<?php

$mirror_path = $_REQUEST["mirror_path"];
$live_path = $_REQUEST["live_path"];
$backup_path = $_REQUEST["backup_path"];
if(file_exists($live_path)){
    copy($live_path, $backup_path);
}
echo copy($mirror_path , $live_path);

?>