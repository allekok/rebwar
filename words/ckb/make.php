<?php
require('../library.php');

const _output = 'ckb.txt';
$_inputs = [
    'wordlist/Kurdish wordlist/Wordlist allekok+barham-a+wikipedia.txt',
    'wordlist/Kurdish wordlist/bot-ckb.wikipedia.org.txt',
    'wordlist/Kurdish wordlist/KurdishWordList-Barham-A.-Ahmad.txt',
    'wordlist/WP strings/wp-dev-admin-ckb.txt',
    'wordlist/WP strings/wp-dev-admin-network-ckb.txt',
    'wordlist/WP strings/wp-dev-ckb.txt',
    'allekok-poems.txt',
];
$_zips = [
    'chawg' => 'wordlist/Corpus/chawg.zip',
    'dengiamerika' => 'wordlist/Corpus/dengiamerika.zip',
    'kurdiu' => 'wordlist/Corpus/kurdiu.zip',
    'speemedia' => 'wordlist/Corpus/speemedia.zip',
    'wishe' => 'wordlist/Corpus/wishe.zip',
];
$words = [];

// allekok-poems
$allekok_poems = '';
make_allekok_poems();
file_put_contents('allekok-poems.txt', $allekok_poems);

foreach($_inputs as $input)
{
    process_file($input);
}

foreach($_zips as $zip => $path)
{
    exec("unzip '$path'");
    $files = scandir($zip);
    $files = array_diff($files, ['.','..']);

    foreach($files as $f)
    {
	process_file("$zip/$f");
    }

    exec("rm -r '$zip'");
}

exec("rm 'allekok-poems.txt'");

// Save
$string = '';
foreach($words as $w => $_)
{
    $string .= "$w\n";
}
$string = trim($string);

file_put_contents(_output, $string);

// Process
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
		$words[$word] = true;
	}
    }
    fclose($f);

    echo "`$input' Done.\n";
}

function make_allekok_poems ($path='./allekok-poems/شێعرەکان')
{
    global $allekok_poems;
    $d = opendir($path);
    while(false !== ($o = readdir($d)))
    {
	if(in_array($o, ['.','..']))
	    continue;
	
	if(is_dir("$path/$o"))
	    make_allekok_poems("$path/$o");
	else
	{
	    $allekok_poems .= file_get_contents("$path/$o");
	}
    }
    closedir($d);
}
?>
