<?php

class Product
{
    protected float $price;
    private int $sconto = 0;
    protected int $quantity;

    public function __construct($price, $quantity)
    {
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function setDiscount($title)
    {
        if ($title == 'Gunfight at Rio Bravo') {
            return $this->sconto = 20;
        } else {
            return $this->sconto;
        }
    }
}

?>