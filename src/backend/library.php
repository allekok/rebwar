<?php
require('../../config.php');

function wordlist_path ($wordlist)
{
    return WRDS_PATH . "/$wordlist/$wordlist.txt";
}

function wordlist_list ()
{
    $list = [];
    $d = opendir(WRDS_PATH);
    while(false !== ($o = readdir($d)))
    {
	if(in_array($o, ['.','..']))
	    continue;
	if(is_dir(WRDS_PATH .'/'. $o))
	    $list[] = $o;
    }
    closedir($d);
    return $list;
}

function get_from_user ($request)
{
    return @ strtolower( trim( filter_var(
	$request, FILTER_SANITIZE_STRING)));
}

function is_number ($string)
{
    $nums = ['١','٢','٣','٤','٥','٦','٧','٨','٩','٠'];
    $i = 0;
    while($c = mb_substr($string, $i, 1))
    {
	if(! in_array($c, $nums))
	    return NULL;
	$i++;
    }
    return true;
}

function spellcheck ($q, $wordlists_name)
{
    if(! ($q and $wordlists_name) ) return NULL;

    $q1 = [];
    // Ignore Words that are Numbers.
    foreach($q as $w)
    {
	if(!is_number($w))
	    $q1[] = $w;
    }

    $results = [];
    $wordlist_list = wordlist_list();
    foreach($wordlists_name as $wordlist_name)
    {
	if(! in_array($wordlist_name, $wordlist_list))
	    continue;
	
	$wordlist = wordlist($wordlist_name);
	
	foreach($q1 as $w)
	{
	    $results[$w] = @$wordlist[$w];
	}
    }
    return array_diff($results, [true]);
}

function sanitize_string ($string)
{
    $to_remove = ['‌'];
    $string = str_replace($to_remove, '', $string);
    return $string;
}

function match_words ($string)
{
    $string = sanitize_string($string);
    preg_match_all('/\w+/ui',$string,$string);
    return $string[0];
}

function wordlist ($wordlist_name)
{
    $wordlist = [];
    
    $wordlist_path = wordlist_path($wordlist_name);
    $f = fopen($wordlist_path, 'r');
    while(! feof($f))
    {
	$w = trim(fgets($f));
	$wordlist[$w] = true;
    }
    fclose($f);

    return $wordlist;
}

function kurdish_numbers ($string)
{
    return str_replace(
	['1','2','3','4','5','6','7','8','9','0'],
	['١','٢','٣','٤','٥','٦','٧','٨','٩','٠'],
	$string);
}
?>
