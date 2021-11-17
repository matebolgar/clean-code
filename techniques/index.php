<?php

// 1. Leképezés (map)

$content = file_get_contents("https://jsonplaceholder.typicode.com/posts");
$posts = json_decode($content, true);

// Imperatív
$kimenet = [];
foreach($posts as $post) {
    $kimenet[] = strlen($post['title']);
}

// Deklaratív
$kimenet2 = array_map(fn ($post) => strlen($post['title']), $posts);

echo "<pre>";
var_dump($kimenet2);
