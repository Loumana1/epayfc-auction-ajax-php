<?php
require_once "framework/Model.php";
require_once "utils/AppTime.php";
require_once "model/Bid.php";
require_once "model/User.php";
require_once "framework/Configuration.php";
require_once "model/ItemPicture.php";


class Item extends Model{

    private $id;
    private  $title;
    private  $description;
    private  $owner;
    private $created_at;
    private  $buy_now_price;
    private $duration_days;
    private ?float $starting_bid;

    private  ?array $_cached_bids = null;    public $end_at;
    private  $bid_count;
    private  $max_bid;
    private $is_direct_sale;
    private  $is_auction;
    private $has_buy_now;
    private  $has_bids;
    private $buy_now_reached;
    private  $not_purchased_direct_sale;


    public function __construct(
        ?int $id,
        string $title,
        ?string $description,
        int $owner,
        string $created_at,
        ?float $buy_now_price,
        int $duration_days,
        ?float $starting_bid,

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

    /* Centralise la logique de mapping pour éviter la duplication
    */
    private static function rowToItem(array $row): Item {
        return new Item(
            $row["id"], 
            $row["title"], 
            $row["description"], 
            $row["owner"], 
            $row["created_at"], 
            $row["buy_now_price"] ? (float)$row["buy_now_price"] : null, 
            $row["duration_days"], 
            $row["starting_bid"], 
            $row["end_at"] ?? null,
            $row["bid_count"] ?? 0,
            $row["max_bid"] ? (float)$row["max_bid"] : null,
            (bool)($row["is_direct_sale"] ?? false),
            (bool)($row["is_auction"] ?? false),
            (bool)($row["has_buy_now"] ?? false),
            (bool)($row["has_bids"] ?? false),
            (bool)($row["buy_now_reached"] ?? false),
            (bool)($row["not_purchased_direct_sale"] ?? false)
        );
    }
        

    private static function queryToItems(string $query, array $params): array {
        $result = self::execute($query, $params)->fetchAll();
        return array_map([self::class, 'rowToItem'], $result);
    }
    public function get_Id(): int {
        return $this->id;

    }
    public static function get_by_id(int $id): Item|false {
        $query = self::execute("SELECT * FROM v_items_status WHERE id = :id", ['id' => $id]);
        $data = $query->fetch();
        return $data === false ? false : self::rowToItem($data);
    }

      public static function get_by_id_for_edit(int $id): ?Item {
        $q = self::execute("SELECT * FROM items WHERE id = :id", ["id" => $id]);
        $r = $q->fetch();
        if (!$r) return null;

        return new Item(
            (int)$r["id"],
            $r["title"],
            $r["description"],
            (int)$r["owner"],
            $r["created_at"],
            $r["buy_now_price"] !== null ? (float)$r["buy_now_price"] : null,
            (int)$r["duration_days"],
            $r["starting_bid"] !== null ? (float)$r["starting_bid"] : null
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

            return $this->buy_now_price !== null;
        

    }
  
    public function get_duration_days(): int { return $this->duration_days; }

    

public function is_open(): bool {
    $now_dt = new DateTime(AppTime::get_current_datetime());

    if (!$this->has_started($now_dt)) {
        return false;
    }
    if ($this->is_direct_sale && !$this->is_auction) {
        return $this->is_direct_sale_open($now_dt);
    }
    if ($this->end_at !== null && $this->end_at !== '') {
        return $this->is_auction_open($now_dt);
    }
    return false;
    }

    private function has_started(DateTime $now_dt): bool {
        return new DateTime($this->created_at) <= $now_dt;
    }
    
    private function is_direct_sale_open(DateTime $now_dt): bool {
    if ($this->end_at !== null && $this->end_at !== '') {
        if (new DateTime($this->end_at) <= $now_dt) {
            return false;
        }
    }
        return !$this->has_bids_time();
    }
    
    private function is_auction_open(DateTime $now_dt): bool {
        return new DateTime($this->end_at) > $now_dt && !$this->has_buy_now_reached_time();
    }

   

       
    
    public function get_bids(): array {
        if ($this->_cached_bids === null) {
            $this->_cached_bids = Bid::get_by_item($this->id);
        }
        return $this->_cached_bids;
    }
    
    
    public function get_seller(): User {
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


public static function delete_by_id(int $itemId): void {
    self::execute(
        "DELETE FROM items WHERE id = :id",
        ['id' => $itemId]
    );
}

public function delete(): void {
    self::delete_by_id($this->id);
}
    public static function get_Item_Participating(int $userId, string $now): array {
        $query = "SELECT * FROM v_items_status 
        WHERE id IN (SELECT item FROM bids WHERE owner = :user_id)
        AND buy_now_reached = 0 AND end_at > :now 
        ORDER BY end_at DESC";
        return self::queryToItems($query, ['user_id' => $userId, 'now' => $now]);

    }
    
    public static function get_Item_Available(int $userId, string $now): array {
        $query = "SELECT * FROM v_items_status 
        WHERE id NOT IN (SELECT item FROM bids WHERE owner = :user_id)
        AND owner != :user_id
        AND buy_now_reached = 0 AND end_at > :now
        ORDER BY end_at DESC";
        return self::queryToItems($query, ['user_id' => $userId, 'now' => $now]);
    }

    public function get_main_picture(): ?ItemPicture {
        $query = self::execute(
            "SELECT * FROM item_pictures WHERE item = :id AND priority = 1",
            ["id" => $this->id]
        );
        $data = $query->fetch();
        if ($data) {
            return new ItemPicture($data['item'], $data['picture_path'], $data['priority']);
        }
        return null;
    }





    public static function get_sold_items_by_owner(int $userId, string $now): array {
        $query = "SELECT * FROM v_items_status 
                  WHERE owner = :user_id
                  AND has_bids = 1
                  AND (end_at <= :now OR buy_now_reached = 1)
                  ORDER BY end_at DESC";
        return self::queryToItems($query, ['user_id' => $userId, 'now' => $now]);
    }

    public static function get_sales_statistics(int $userId, string $now): array {
    $stats = self::get_sales_stats_core($userId, $now);
    $stats['loyal_bidder'] = self::get_loyal_bidder_for_seller($userId, $now);
    return $stats;
    }

        
    private static function get_sales_stats_core(int $userId, string $now): array {
    $query = "SELECT 
                COUNT(*) as sales_count,
                COALESCE(SUM(max_bid), 0) as total_revenue,
                COALESCE(AVG(max_bid), 0) as average_ticket
            FROM v_items_status 
            WHERE owner = :user_id
            AND has_bids = 1
            AND (end_at <= :now OR buy_now_reached = 1)";
    $row = self::execute($query, ['user_id' => $userId, 'now' => $now])->fetch();
    return [
        'sales_count' => (int)($row['sales_count'] ?? 0),
        'total_revenue' => (float)($row['total_revenue'] ?? 0),
        'average_ticket' => (float)($row['average_ticket'] ?? 0),
    ];
    }

    private static function get_loyal_bidder_for_seller(int $userId, string $now): ?string {
        $query = "SELECT u.pseudo, COUNT(*) as win_count
                FROM bids b
                JOIN v_items_status v ON b.item = v.id
                JOIN users u ON b.owner = u.id
                WHERE v.owner = :user_id
                AND v.has_bids = 1
                AND (v.end_at <= :now OR v.buy_now_reached = 1)
                AND b.amount = v.max_bid
                GROUP BY b.owner, u.pseudo
                ORDER BY win_count DESC
                LIMIT 1";
        $row = self::execute($query, ['user_id' => $userId, 'now' => $now])->fetch();
        return $row ? $row['pseudo'] : null;
    }

    public function get_sold_at(): ?string {
    if ($this->buy_now_reached || ($this->is_direct_sale && $this->has_bids_time())) {
        $bids = $this->get_bids();
        if (!empty($bids)) {
            return $bids[0]['created_at'];
        }
    }
    return $this->end_at;
}

    private static function title_exists_for_owner(string $title, int $owner, ?int $exclude_id): bool {
        $sql = "SELECT COUNT(*) FROM items WHERE title = :title AND owner = :owner";
        $params = ["title" => $title, "owner" => $owner];

        if ($exclude_id !== null) {
            $sql .= " AND id <> :id";
            $params["id"] = $exclude_id;
        }

        $q = self::execute($sql, $params);
        return (int)$q->fetchColumn() > 0;
    }

    public function validate(): array {
    $errors = [];

    
    $title = trim($this->title);
    if (strlen($title) < 3 || strlen($title) > 50) {
        $errors["title"] = "Title length must be between 3 and 50 characters.";
    } elseif (self::title_exists_for_owner($title, $this->owner, $this->id)) {
        $errors["title"] = "You already have an item with this title.";
    }

    
    if ($this->description !== null && trim($this->description) !== "" && strlen(trim($this->description)) < 3) {
        $errors["description"] = "Description must be at least 3 characters.";
    }

    
    if ($this->duration_days < 1 || $this->duration_days > 365) {
        $errors["duration_days"] = "Duration must be between 1 and 365 days.";
    }

    $sb = $this->starting_bid;
    $bn = $this->buy_now_price;

    
    if ($sb !== null && $sb > 0) {
        if ($bn !== null && $bn <= $sb) {
            $errors["buy_now_price"] = "Buy now price must be greater than the starting bid.";
        }
    }
    
    else {
        if ($bn === null || $bn <= 0) {
            $errors["buy_now_price"] = "Sale price is required for a direct sale.";
        }
    }

    return $errors;
}




    public function persist(): array {
        $errors = $this->validate();
        if (!empty($errors)) return $errors;

        if ($this->id === null) {
            self::execute(
                "INSERT INTO items(title, description, owner, created_at, buy_now_price, duration_days, starting_bid)
                 VALUES(:title, :description, :owner, :created_at, :buy_now_price, :duration_days, :starting_bid)",
                [
                    "title" => $this->title,
                    "description" => $this->description,
                    "owner" => $this->owner,
                    "created_at" => $this->created_at,
                    "buy_now_price" => $this->buy_now_price,
                    "duration_days" => $this->duration_days,
                    "starting_bid" => $this->starting_bid ?? 0.0
                ]
            );
            $this->id = self::lastInsertId();
        } else {
            self::execute(
                "UPDATE items
                 SET title=:title, description=:description, buy_now_price=:buy_now_price, duration_days=:duration_days, starting_bid=:starting_bid
                 WHERE id=:id",
                [
                    "title" => $this->title,
                    "description" => $this->description,
                    "buy_now_price" => $this->buy_now_price,
                    "duration_days" => $this->duration_days,
                    "starting_bid" => $this->starting_bid ?? 0.0,
                    "id" => $this->id
                ]
            );
        }

        return [];
    }

    public static function get_active_items_by_owner(int $userId, string $now): array {
        $query = "SELECT * FROM v_items_status
                    WHERE owner = :user_id
                    AND end_at > :now
                    AND buy_now_reached = 0
                    ORDER BY end_at DESC";
        return self::queryToItems($query, [
            'user_id' => $userId,
            'now' => $now
        ]);
    }

    public static function get_closed_unsold_items_by_owner(int $userId, string $now): array {
        $query = "SELECT * FROM v_items_status
                WHERE owner = :user_id
                AND end_at <= :now
                AND has_bids = 0
                AND buy_now_reached = 0
                ORDER BY end_at DESC";
        return self::queryToItems($query, [
            'user_id' => $userId,
            'now' => $now
        ]);
    }



    public static function get_items_by_owner(int $userId): array {
        $query = "
            SELECT * FROM v_items_status
            WHERE owner = :user_id
            ORDER BY end_at DESC
        ";

        return self::queryToItems($query, [
            "user_id" => $userId
        ]);
    }

   public static function get_purchased_items_by_user(int $userId, string $now): array {
        $query = "
            SELECT * FROM v_items_status
            WHERE id IN (
                SELECT item
                FROM bids
                WHERE owner = :user_id
                AND amount = max_bid
            )
            AND (end_at <= :now OR buy_now_reached = 1)
            ORDER BY end_at DESC
        ";

        return self::queryToItems($query, [
            "user_id" => $userId,
            "now" => $now
        ]);
    }


    public function is_user_highest_bidder(int $userId): bool
    {
        return Bid::is_user_highest($userId, $this->id);
    }

    public function user_has_bid(int $userId): bool
    {
        return Bid::user_has_bid($userId, $this->id);
    }

    public function get_picture_count(): int {
        $query = self::execute(
            "SELECT COUNT(*) FROM item_pictures WHERE item = :id",
            ["id" => $this->id]
        );
        return (int)$query->fetchColumn();
    }



}
