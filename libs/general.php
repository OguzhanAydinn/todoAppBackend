<?php
function pre($str) {
    echo "\n
<pre>===================================================================================\n";
    print_r($str);
    echo "\n===================================================================================</pre>\n";
}
function pred($str) {
    pre($str);
    die();
}