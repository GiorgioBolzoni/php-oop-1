<?php

include __DIR__ . "/Genre.php";
include __DIR__ . "/Product.php";

class Movie extends Product
{
    private int $id;
    private string $title;
    private string $overview;
    private float $vote_average;
    private string $poster_path;
    private string $original_language;
    private array $genres;

    public function __construct($id, $title, $overview, $vote_average, $poster_path, $original_language, $genres, $quantity, $price)
    {
        parent::__construct($price, $quantity);
        $this->id = $id;
        $this->title = $title;
        $this->overview = $overview;
        $this->vote_average = $vote_average;
        $this->poster_path = $poster_path;
        $this->original_language = $original_language;
        $this->genres = $genres;
    }

    private function getVote()
    {
        $vote = ceil($this->vote_average / 2);
        $template = "<p>";
        for ($n = 1; $n <= 5; $n++) {
            $template .= $n <= $vote ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-solid fa-star"></i>';
        }
        $template .= "</p>";
        return $template;
    }

    private function formatGenres()
    {
        $template = "<p>";
        foreach ($this->genres as $genre) {
            $template .= $genre->name;
        }
        $template .= "</p>";
        return $template;
    }

    public function printCard()
    {
        $sconto = $this->setDiscount($this->title);
        $image = $this->poster_path;
        $title = strlen($this->title) > 28 ? substr($this->title, 0, 28) . '...' : $this->title;
        $content = substr($this->overview, 0, 100) . '...';
        $custom = $this->getVote();
        $genre = $this->formatGenres();
        $price = $this->price;
        $quantity = $this->quantity;
        include __DIR__ . '/../Views/card.php';
    }

    public static function fetchAll()
    {
        $movieString = file_get_contents(__DIR__ . "/movie_db.json");
        $movieString = json_decode($movieString, true);

        $genres = Genre::fetchAll();
        $movies = [];

        foreach ($movieString as $item) {
            $movieGenres = [];
            foreach ($item["genre_ids"] as $genreId) {
                foreach ($genres as $genre) {
                    if ($genre->name === $genreId) {
                        $movieGenres[] = $genre;
                        break;
                    }
                }
            }
            $movies[] = new Movie(
                $item['id'],
                $item['title'],
                $item['overview'],
                $item['vote_average'],
                $item['poster_path'],
                $item['original_language'],
                $movieGenres,
                $item['quantity'],
                $item['price']
            );
        }

        return $movies;
    }
}
?>