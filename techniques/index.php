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

// echo "<pre>";
// var_dump($kimenet2);


// Map objektumon belül
class Test
{

    public function test()
    {
        $content = file_get_contents("https://jsonplaceholder.typicode.com/posts");
        $posts = json_decode($content, true);
        return array_map([$this, 'toNumberOfCharactersOfTitle'], $posts);
    }

    private function toNumberOfCharactersOfTitle($post)
    {
        // Tönkreteszi a pure műveletet
        var_dump($_GET);
        return strlen($post['title']);
    }
}

// var_dump((new Test)->test());


// Filter

// Imperatív
$ret = [];
foreach($posts as $post) {
    // Early continue
    if(strlen($post['title']) < 40) {
        continue; 
    }
    $ret[] = $post;
}


// Deklaratív
$filteredPosts = array_filter($posts, function ($post) {
    return strlen($post['title']) > 40;
});


// Reduce

// Imperatív
$karakterekSzama = 0;
foreach($posts as $post) {
    $karakterekSzama += strlen($post['title']);
}

// Deklaratív
$karakterekSzama2 = array_reduce(
    $posts, 
    fn($gyujto, $post) => $gyujto + strlen($post["title"]),
     0
);

var_dump($karakterekSzama);
var_dump($karakterekSzama2);








