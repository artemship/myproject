<?php

//$pattern = '/Меняем автора статьи ([0-9]+) c "(.+)" на "(.+)"./';
//$str = 'Меняем автора статьи 123 c "Иван" на "Пётр".';
//
//preg_match($pattern, $str, $matches);
//
//$articleId = $matches[1];
//$oldAuthor = $matches[2];
//$newAuthor = $matches[3];


//$pattern = '/Меняем автора статьи (?P<articleId>[0-9]+) c "(.+)" на "(.+)"./';
//$str = 'Меняем автора статьи 123 c "Иван" на "Пётр".';
//preg_match($pattern, $str, $matches);
//$articleId = $matches['articleId'];

$pattern = '/\/(?P<controller>[A-Z,a-z]+)\/(?P<id>[0-9]+)/';
$url = '/post/892';

preg_match($pattern, $url, $matches);

$controller = $matches['controller'];
$id = $matches['id'];


var_dump($matches);
var_dump($controller);
var_dump($id);