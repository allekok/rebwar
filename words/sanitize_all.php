<?php
require('library.php');

foreach(wordlist_list() as $wordlist)
{
    exec("cd '$wordlist' && php sanitize.php");
    echo "`$wordlist` Done.\n";
}
?>
