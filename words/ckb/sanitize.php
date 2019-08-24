<?php
require('../library.php');
const _input = 'ckb.txt';
const _output = 'ckb.txt';

$words = explode("\n", file_get_contents(_input));
$new_words = [];

sort($words);

$string = implode("\n", $words);

file_put_contents(_output, $string);
?>
