<?php
const _kurdish_letters = [
	"\u{0626}","\u{0627}","\u{0628}","\u{067E}",
	"\u{062A}","\u{062C}","\u{0686}","\u{062D}",
	"\u{062E}","\u{062F}","\u{0631}","\u{0695}",
	"\u{0632}","\u{0698}","\u{0633}","\u{0634}",
	"\u{0639}","\u{063A}","\u{0641}","\u{06A4}",
	"\u{0642}","\u{06A9}","\u{06AF}","\u{0644}",
	"\u{06B5}","\u{0645}","\u{0646}","\u{0648}",
	"\u{06C6}","\u{0647}","\u{06D5}","\u{06CC}",
	"\u{06CE}","\u{0660}","\u{0661}","\u{0662}",
	"\u{0663}","\u{0664}","\u{0665}","\u{0666}",
	"\u{0667}","\u{0668}","\u{0669}","\u{200C}",
	"\u{061F}","\u{060C}"," ",
];
const _kurdish_vowels = [
	"ێ",
	"ە",
	"ۆ",
	"ا",
];

function is_word_valid($word) {
	$word = sanitize_string($word);
	
	/* First Letter */
	$c = mb_substr($word, 0, 1);
	
	/* Checking if the first letter is vowel. */
	if(in_array($c, _kurdish_vowels))
		return false;

	/* Checking if letters are according to Unicode standard. */
	$i = 0;
	while($c = mb_substr($word, $i, 1)) {
		/* Duplicate vowels */
		if(in_array($c, _kurdish_vowels)) {
			$c1 = @mb_substr($word, $i + 1, 1);
			if(in_array($c1, _kurdish_vowels))
				return false;
		}
		
		/* Non-Unicode */
		if(!in_array($c, _kurdish_letters))
			return false;
		
		$i++;
	}
	
	if(is_number($word))
		return false;
	
	return $word;
}
function wordlist_list() {
	require("../config.php");
	
	$list = [];
	$d = opendir(WRDS_PATH);
	while(false !== ($o = readdir($d)))
	{
		if(in_array($o, [".", ".."]))
			continue;
		if(is_dir(WRDS_PATH . "/" . $o))
			$list[] = $o;
	}
	closedir($d);
	return $list;
}
function is_number($string) {
	$nums = ["١","٢","٣","٤","٥","٦","٧","٨","٩","٠"];
	for($i = 0; $c = mb_substr($string, $i, 1); $i++)
		if(!in_array($c, $nums))
			return NULL;
	return true;
}
function sanitize_string($string, $to="") {
	/* Remove Punctuation Marks */
	$to_remove = [
		'0','!','@','#','$','%','^','&','*','(',')',
		'-','=','_','+','\\','|','[',']','{','}',
		'"',"'",';',':','/','?','.',',','<','>','‌',
		'،','؟','؛',
	];
	
	$string = str_replace(["ه‌"], ["ە"], $string);
	$string = str_replace($to_remove, $to, $string);
	$string = trim($string);
	
	return $string;
}
?>
