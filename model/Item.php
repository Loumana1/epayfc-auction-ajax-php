<?php
require_once "framework/Model.php";
require_once "utils/AppTime.php";
class Item extends Model{

    public $id;
    public $title;
    public $description;
    public $owner;
    public $created_at;
    public $buy_now_price;
    public $duration_days;
    public $starting_bid;

    
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
        int $owner,
        string $created_at,
        ?float $buy_now_price,
        int $duration_days,
        float $starting_bid,

        //v_items_status
        ?string $end_at = null,
        int $bid_count = 0,
        float $max_bid= null,
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
        $this->owner = $owner;
        $this->created_at = $created_at;
        $this->buy_now_price = $buy_now_price;
        $this->duration_days = $duration_days;
        $this->starting_bid = $starting_bid;
        

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

        public static function get_by_id(int $id): Item|false {
        $query = self::execute(
            "SELECT * FROM v_items_status WHERE id = :id", 
            ['id' => $id]
        );
        $data = $query->fetch();
        if ($data === false) return false;
        
        return new Item(
            $data["id"],
            $data["title"],
            $data["description"],
            $data["owner"],
            $data["created_at"],
            $data["buy_now_price"],
            $data["duration_days"],
            $data["starting_bid"],
            $data["end_at"] ?? null,
            $data["bid_count"] ?? 0,
            $data["max_bid"] ?? null,
            (bool)($data["is_direct_sale"] ?? false),
            (bool)($data["is_auction"] ?? false),
            (bool)($data["has_buy_now"] ?? false),
            (bool)($data["has_bids"] ?? false),
            (bool)($data["buy_now_reached"] ?? false),
            (bool)($data["not_purchased_direct_sale"] ?? false)
        );
    }



    public function get_Title(): string {
    return $this-> title ;
    }

    public function get_Description(): ?string {
    return $this -> description;

    }
    public function get_owner(): int {
        return $this->owner;
    }
public function get_Created_At() : string {  
    return $this->created_at;
}
public function get_Buy_Now_Price() :?float{
    return $this->buy_now_price;
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
    public function get_Has_buy_now_price(): bool{
        if( $this->buy_now_price!= null ) 
            return true;

         return false;

    }




public function is_open(): bool {
        $now = AppTime::get_current_datetime();
        $nowDateTime = new DateTime($now);
        
       //l'item a commencé 
       $createdAt = new DateTime($this->created_at);
       if ($createdAt > $nowDateTime) {
           return false; }
            
        //Direct Sale
            if ($this->is_direct_sale && !$this->is_auction) {
                        return !$this->has_bids_time(); // Pas encore acheté
            }
            
            //  Auction
            if ($this->end_at) {
                $endAtDateTime = new DateTime($this->end_at);
                $isBeforeEnd = $endAtDateTime > $nowDateTime;
                
                // pour tenir compte de AppTime
                $buyNowReachedTime = $this->has_buy_now_reached_time();
                
                return $isBeforeEnd && !$buyNowReachedTime;
            }
        return false;
    }



   

       
    
    public function get_bids(): array {
        require_once "model/Bid.php";
        return Bid::get_by_item($this->id);
    }
    
    public function get_pictures(): array {
        $query = self::execute(
            "SELECT * FROM item_pictures WHERE item = :id ORDER BY priority ASC",
            ['id' => $this->id]
        );
        return $query->fetchAll();
    }
    
    public function get_seller(): User {
        require_once "model/User.php";
        return User::get_User_By_Id($this->owner);
    }
    
    public function has_bids_time(): bool {
        return count($this->get_bids()) > 0;
    }
    
    public function get_max_bid_time(): ?float {
        $bids = $this->get_bids();
        if (empty($bids)) return null;
        return (float)max(array_column($bids, 'amount'));
    }
    
    public function get_highest_bidder_pseudo(): ?string {
        $bids = $this->get_bids();
        return !empty($bids) ? $bids[0]['pseudo'] : null;
    }
    
    public function get_min_bid_amount(): float {
        $maxBid = $this->get_max_bid_time();
        return $maxBid ? $maxBid + 0.01 : $this->starting_bid;
    }




    public function has_buy_now_reached_time(): bool {
     
        if (!$this->buy_now_price) {
            return false;
        }
        $maxBid = $this->get_max_bid_time();
        return $maxBid !== null && $maxBid >= $this->buy_now_price;
    }






    public static function delete_pictures(int $itemId): void {

    //supp dans bd 
    $query = self::execute(
        "SELECT picture_path FROM item_pictures WHERE item = :id",
        ['id' => $itemId]
    );
    $pictures = $query->fetchAll();
    
    foreach ($pictures as $pic) {
        $path = $pic['picture_path'];
        $thumbPath = str_replace('.jpg', '_thumbnail.jpg', $path);
        if (file_exists($path)) unlink($path);
        if (file_exists($thumbPath)) unlink($thumbPath);
    }
    //supp dans les ficheirb
    
    self::execute(
        "DELETE FROM item_pictures WHERE item = :id",
        ['id' => $itemId]
    );
}
}