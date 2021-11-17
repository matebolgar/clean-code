<?php

interface Lister {
    /*
    * @return Post[]
    */ 
    public function list(): array;
}

// value object
class Response {
    public int $count;
    /*
    * @var Post[]
    */
    public array $items;
    public int $characterCount;

    public function __construct(int $count, array $items, int $characterCount) {
        $this->count = $count;
        $this->items = $items;
        $this->characterCount = $characterCount;
    }
}

class Post {
    public $title;
    public $body;

    public  function __construct($title, $body) {
        $this->title = $title;
        $this->body = $body;
    }
}

class NetworkPostLister implements Lister {
    /**
    * @return Post[]
    */
    public function list(): array {
        $content = file_get_contents("https://jsonplaceholder.typicode.com/posts");
        $posts = json_decode($content, true);
        $res = [];
        foreach($posts as $post) {
            $res[] = new Post($post['title'], $post['body']);
        }
        return $res;
    }
}

class MockPostLister implements Lister {

    /**
    * @return Post[]
    */
    public function list(): array {
        return [
            new Post("Cím", "tartalom"),
            new Post("Cím1", "tartalom2"),
            new Post("Cím2", "tartalom3"),
            new Post("Cím3", "tartalom4"),
            new Post("Cím4", "tartalom5"),
            new Post("Cím5", "tartalom6"),
        ];
    }
}

class RandomPostLister implements Lister
{
    /**
     * @return Post[]
     */
    public function list(): array
    {
        return array_map(fn ($num) => new Post($num, $this->getRandomString(), $this->getRandomString()), range(0, 10));
    }

    private function getRandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

class ListController {

    private Lister $lister;

    public function __construct(Lister $lister) {
        $this->lister = $lister;
    }

    public function getPosts(int $limit): Response {
        $posts = $this->lister->list();
        $limitedPosts = array_slice($posts, 0, $limit);
        $total = 0;
        foreach($limitedPosts as $post) {
            $total += strlen($post->body);
        }
        return new Response(count($limitedPosts), $limitedPosts, $total);

    }
} 


$res = null;
switch ($_GET['strategy']){
    case 'random': 
        $res = (new ListController(new RandomPostLister))->getPosts(10);
        break;
    case 'mock':
        $res = (new ListController(new MockPostLister))->getPosts(10);
        break;
    case 'network':
        $res = (new ListController(new NetworkPostLister))->getPosts(10);
        break;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <div class="row m-5 border p-5">
            <div id="posts-container" class="w-100">
                <h3>Karakterek száma: <?= $res->characterCount ?></h3>
                <ul class="list-group">
                    <?php foreach($res->items as $post): ?>
                    <li class="list-group-item">
                        <?= $post->body ?>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>