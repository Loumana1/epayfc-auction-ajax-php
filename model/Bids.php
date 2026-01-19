<?php
require_once "framework/Model.php";
class Bid{
    public $owner;
    public $item;
    public $created_at;
    public $amount;

    public function __construct(int $owner, int $item , string $created_at , float $amount)
    {
        $this->owner = $owner;
        $this->item = $item;
        $this->created_at = $created_at;
        $this->amount = $amount;
    }
}?>