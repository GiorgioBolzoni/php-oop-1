<?php

include __DIR__ . "/Genre.php";
// include __DIR__ . "/Product.php";

class Movie
{
    private int $id;
    private string $title;
    private string $overview;
    private float $vote_average;
    private string $poster_path;
    private string $original_language;
    private array $genres;
    private int $quantity;
    public array $sconto;
    private float $price;

    function __construct($id, $title, $overview, $vote, $image, $language, $genres, $quantity, $price)
    {
        $this->id = $id;
        $this->title = $title;
        $this->overview = $overview;
        $this->vote_average = $vote;
        $this->poster_path = $image;
        $this->original_language = $language;
        $this->genres = $genres;
        $this->quantity = $quantity;
        $this->price = $price;

    }

    private function getVote()
    {
        $vote = ceil($this->vote_average / 2);
        $template = "<p>";
        for ($n = 1; $n <= 5; $n++) {
            $template .= $n <= $vote ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
        }
        $template .= "</p>";
        return $template;
    }

    private function formatGenres()
    {
        $template = "<p>";
        foreach ($this->genres as $genre) {
            $template .= '<span>' . $genre->drawGenre() . ' - </span>';
        }
        $template .= "</p>";
        return $template;
    }

    public function printCard()
    {
        $image = $this->poster_path;
        $title = strlen($this->title) > 28 ? substr($this->title, 0, 28) . '...' : $this->title;
        $content = substr($this->overview, 0, 100) . '...';
        $custom = $this->getVote();
        $genre = $this->formatGenres();
        $quantity = $this->quantity;
        $price = $this->price;
        include __DIR__ . '/../Views/card.php';
    }
}
$movieString = file_get_contents(__DIR__ . "/movie_db.json");
$movieList = json_decode($movieString, true);

$movies = [];

foreach ($movieList as $item) {
    $movieGenres = [];
    $quantity = isset($item['quantity']) ? $item['quantity'] : 0;

    for ($i = 0; $i < count($item['genre_ids']); $i++) {
        // Assicurati di aver incluso o definito la classe Genre
        $index = rand(0, count($genres) - 1);
        $rand_genre = $genres[$index];
        $movieGenres[] = $rand_genre;
    }

    $movies[] = new Movie(
        $item['id'],
        $item['title'],
        $item['overview'],
        $item['vote_average'],
        $item['poster_path'],
        $item['original_language'],
        $movieGenres,
        $quantity,
        $item['price'] // Aggiunto il prezzo dal tuo file JSON
    );
}
?>