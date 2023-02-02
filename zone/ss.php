<?php
$url='Is Pete  "" \\\ ///r <smart> & funny?';
echo filter_var($url,FILTER_SANITIZE_SPECIAL_CHARS);
echo $url;
?>