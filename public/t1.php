<?php

$arr = array('', 'ru', 'rt', 'g', 'en', 'dfdf');
for ($i=0; $i<count($arr); $i++) {
    $value = preg_match('/^ru|en|^$$/', $arr[$i], $val);
    //$value = preg_match('/^[^a-z]*$/', $arr[$i], $val);
    var_dump($val);
}

$arr = array('', 'word1', 'word2', 'a', 'abcd');
foreach ($arr as $string) {
    preg_match('/^word1|word2|^$$/', $string, $matches);
    var_dump($matches);
}
//if (is_array($var))
/*var_dump($val);
$arr = array('a', 'b', 'c');
foreach ($arr as $t) {
    var_dump($t);
    //continue;
    var_dump($t);
}*/