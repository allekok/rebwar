<?php
const _input = ['ckb.txt','ckb.txt-not-sure'];

foreach (_input as $inp)
{
    $words = explode("\n", file_get_contents($inp));
    sort($words);
    $string = implode("\n", $words);
    file_put_contents($inp, $string);
}
?>
