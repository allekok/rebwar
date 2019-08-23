<?php
/* Spellcheck a string of chars.
 * Input: $_REQUEST[q, wordlists, output]
 * output: JSON or Text */
require('library.php');

$q = match_words(get_from_user(@$_REQUEST['q']));
$wordlists = explode(',' , get_from_user(@$_REQUEST['wordlists']));
$output_type = get_from_user(@$_REQUEST['output']);

$t0 = microtime(true);
$results = spellcheck($q, $wordlists);
$t1 = microtime(true);

$dt = kurdish_numbers(number_format($t1-$t0, 3));

if($output_type == 'json')
{
    $results['time'] = $dt;
    
    header('Content-type:application/json; charset=utf-8');
    echo json_encode($results);
}
else
{
    /* Text
     * Just print incorrect words. */
    if( $results )
    {
	$toprint = "گەڕان {$dt}چرکەی خایاند.\n";
	$toprint .= "ڕێنووسی ئەم وشانە دروست نییە: \n";
	foreach($results as $w => $res)
	{
	    $toprint .= "$w\n";
	}
	
	header('Content-type:text/plain; charset=utf-8');
	echo trim($toprint);
    }
}
?>
