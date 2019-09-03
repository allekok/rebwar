<?php
require('../library.php');

const _output = 'ckb.txt';
$_texts = [
    'wordlist/Kurdish wordlist/Wordlist allekok+barham-a+wikipedia.txt',
    'wordlist/Kurdish wordlist/bot-ckb.wikipedia.org.txt',
    'wordlist/Kurdish wordlist/KurdishWordList-Barham-A.-Ahmad.txt',
    'wordlist/WP strings/wp-dev-admin-ckb.txt',
    'wordlist/WP strings/wp-dev-admin-network-ckb.txt',
    'wordlist/WP strings/wp-dev-ckb.txt',
];
$_zips = [
    'chawg' => 'wordlist/Corpus/chawg.zip',
    'dengiamerika' => 'wordlist/Corpus/dengiamerika.zip',
    'kurdiu' => 'wordlist/Corpus/kurdiu.zip',
    'speemedia' => 'wordlist/Corpus/speemedia.zip',
    'wishe' => 'wordlist/Corpus/wishe.zip',
];
$dicts_name = ['henbane-borine', 'xal'];
$dicts = [];
dicts($dicts_name);
$words = [];

// allekok-poems
process_dir('./allekok-poems/شێعرەکان');

// texts
foreach($_texts as $o)
{
    process_file($o);
}

// zips
foreach($_zips as $zip => $path)
{
    exec("unzip '$path'");
    $files = array_diff(scandir($zip),['.','..']);
    foreach($files as $f)
    {
	process_file("$zip/$f");
    }
    exec("rm -r '$zip'");
}

// save
$string = '';
$string_not_sure = '';
foreach($words as $w => $n)
{
    if($n > 1 or lookup($w))
	$string .= "$w\n";
    else
	$string_not_sure .= "$w\n";
}
$string = trim($string);
$string_not_sure = trim($string_not_sure);

file_put_contents(_output, $string);
file_put_contents(_output . '-not-sure', $string_not_sure);

// process
function process_file ($input)
{
    global $words;
    $f = fopen($input, 'r');
    while(! feof($f))
    {
	$line = fgets($f);
	$line = sanitize_string($line, ' ');
	$line = preg_replace('/\s+/u', ' ', $line);
	$line = explode(' ', $line);

	foreach($line as $w)
	{
	    if($word = is_word_valid($w))
	    {
		@$words[$word]++;
	    }
	}
    }
    fclose($f);

    echo "`$input' Done.\n";
}

function process_dir ($path)
{
    $not = ['.','..','.git'];
    $d = opendir($path);
    while(false !== ($o = readdir($d)))
    {
	if(in_array($o, $not))
	    continue;
	
	if(is_dir("$path/$o"))
	    process_dir("$path/$o");
	else
	    process_file("$path/$o");
    }
    closedir($d);
}

function dicts($dicts_name)
{
    global $dicts;
    foreach($dicts_name as $dict_name)
    {
	$dict_path = "../../../tewar-2/dict/$dict_name/$dict_name.txt";
	$f = fopen($dict_path, 'r');
	while(! feof($f))
	{
	    $line = explode("\t", trim(fgets($f)));
	    if(@$line[1])
	    {
		$dicts[$dict_name][$line[0]] = true;
	    }
	}
	fclose($f);
    }
}

function lookup ($w)
{
    global $dicts;
    
    foreach($dicts as $dict)
    {
	if(@$dict[$w]) return true;
    }
    
    return false;
}
?>
