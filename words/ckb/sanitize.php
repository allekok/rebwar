<?php
require('../library.php');

const _input = 'wordlist/Kurdish wordlist/Wordlist allekok+barham-a+wikipedia.txt';
const _output = 'ckb.txt';

$words = [];

$f = fopen(_input, 'r');
while(! feof($f))
{
    $line = fgets($f);
    if($word = is_word_valid($line))
	$words[] = $word;
}

$words = implode("\n", $words);
file_put_contents(_output, $words);
?>
