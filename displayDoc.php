<?php
//echo "<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" charset=\"utf-8\"/>";
$newFile = $_GET['MIRROR'];
$liveFile = $_GET['LIVE'];
require_once dirname(__FILE__).'../php-diff-master/lib/Diff.php';

// Include two sample files for comparison
$a = explode("\n", file_get_contents($newFile));
$b = explode("\n", file_get_contents($liveFile));

// Options for generating the diff
$options = array(
    //'ignoreWhitespace' => true,
    //'ignoreCase' => true,
);

// Initialize the diff class
$diff = new Diff($a, $b, $options);

// Generate a side by side diff
require_once dirname(__FILE__).'./php-diff-master/lib/Diff/Renderer/Html/SideBySide.php';
$renderer = new Diff_Renderer_Html_SideBySide;
echo $diff->Render($renderer);
