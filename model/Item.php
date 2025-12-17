<?php
class Item {

    public $id;
    public $title;
    public $description;
    public $is_owner;
    public $created_at;
    public $buy_now_price;
    public $duration_days;
    public $starting_bid;

    //Proprité de v_items pour extra pour vu view_ (peut faciliter ?)
    public $end_at;
    public $bid_count;
    public $max_bid;
    public $is_direct_sale;
    public $is_auction;
    public $has_buy_now;
    public $has_bids;
    public $buy_now_reached;
    public $not_purchased_direct_sale;


    public function __construct(
        int $id,
        string $title,
        ?string $description,
        int $is_owner,
        string $created_at,
        ?float $buy_now_price,
        int $duration_days,
        float $starting_bid,

        //v_items_status
        ?string $end_at = null,
        int $bid_count = 0,
        ?float $max_bid = null,
        bool $is_direct_sale = false,
        bool $is_auction = false,
        bool $has_buy_now = false,
        bool $has_bids = false,
        bool $buy_now_reached = false,
        bool $not_purchased_direct_sale = false 
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->is_owner = $is_owner;
        $this->created_at = $created_at;
        $this->buy_now_price = $buy_now_price;
        $this->duration_days = $duration_days;
        $this->starting_bid = $starting_bid;
        
        // proprietes pour view
        $this->end_at = $end_at;
        $this->bid_count = $bid_count;
        $this->max_bid = $max_bid;
        $this->is_direct_sale = $is_direct_sale;
        $this->is_auction = $is_auction;
        $this->has_buy_now = $has_buy_now;
        $this->has_bids = $has_bids;
        $this->buy_now_reached = $buy_now_reached;
        $this->not_purchased_direct_sale = $not_purchased_direct_sale; 
    }

        
    public function get_Id(): int {
        return $this->id;

    }

    public function get_Title(): string {
    return $this-> title ;
    }

    public function get_Description(): ?string {
    return $this -> description;

    }
    public function get_is_Owner(): int {
        return $this->is_owner;
    }
public function get_Created_At() : string {  
    return $this->created_at;
}
public function get_Buy_Now_Price() :?float{
    return $this->buy_now_price;
}


public function get_Max_Bid(): ?float {
    return $this ->max_bid;
}

public function get_Starting_Bid(): float {
 return $this -> starting_bid; 
}

    public function get_End_At(): ?string { 
        return $this->end_at;
    }

    public function get_Is_Direct_Sale(): bool {
        return $this->is_direct_sale;
    }

    public function get_Is_Auction(): bool {
        return $this->is_auction;
    }
    public function get_Not_Purchased_Direct_Sale(): bool {
        return $this->not_purchased_direct_sale;
    }
//-------------------------------------// 

    public function is_Open(): bool {
        //checker si l'item est toujours ouvert 
    //analyser cas different
    return true;

    }

}