<?php
require_once "framework/Model.php";
require_once "utils/AppTime.php";
require_once "utils/format.php";
require_once "model/Item.php";
require_once "framework/Configuration.php";
class Bid extends Model {
    
    private ?int $id;
    private int $item_id;
    private int $owner_id;
    private float $amount;
    private string $created_at;
    private ?string $pseudo;
    private ?string $picture_path;

    public function __construct(
        int $item_id,
        int $owner_id,
        float $amount,
        ?int $id = null,
        ?string $created_at = null,
        ?string $pseudo = null,
        ?string $picture_path = null
    ) {
        $this->id = $id;
        $this->item_id = $item_id;
        $this->owner_id = $owner_id;
        $this->amount = $amount;
        $this->created_at = $created_at ?? AppTime::get_current_datetime();
        $this->pseudo = $pseudo;
        $this->picture_path = $picture_path;
    }

    public function get_amount(): float {
        return $this->amount;
    }

    public function get_created_at(): string {
        return $this->created_at;
    }

    public function get_owner_id(): int {
        return $this->owner_id;
    }

    public function get_pseudo(): ?string {
        return $this->pseudo;
    }

    public function get_picture_path(): ?string {
        return $this->picture_path;
    }
    


    public function validate(?Item $item): array {

        $errors = [];
        $now = AppTime::get_current_datetime();
        
      //  tous necessaire !! 
      //  quelqu'un peut envoyer un POST directement 
      // faut reverifier directement ici au cas ou 
    
    
  

        if ($item->get_owner() == $this->owner_id) {
            $errors[] = "You cannot bid on your own items ";
            return $errors;
        }
        

        if (!$item->is_open()) {
            $errors[] = "This listing is closed";
            return $errors;
        }

   

        $min_bid = $item->get_min_bid_amount();
        if ($this->amount < $min_bid) {
            $errors[] = "Minimum bid is  " . format_euro($min_bid);
            return $errors;

        }
        $max_bid = (float) Configuration::get("max_bid_amount");
        if ($this->amount > $max_bid) {
            $errors[] = "Maximum bid is " . format_euro($max_bid);
            return $errors;
        }
                
        return $errors;
    }
    

    public function persist(?Item $item = null): array {
        $errors = $this->validate($item);
        if (!empty($errors)) {
            return $errors;
        }
        
        $query = self::execute(
            "INSERT INTO bids (item, owner, created_at, amount) 
             VALUES (:item_id, :owner_id, :created_at, :amount)",
            [
                'item_id' => $this->item_id,
                'owner_id' => $this->owner_id,
                'created_at' => $this->created_at,
                'amount' => $this->amount
            ]
        );
        
        if ($query) {
            $this->id = (int)self::lastInsertId();
        }
        
        return [];
    }
    
 
    public static function get_by_item(int $item_id): array {
        $now = AppTime::get_current_datetime();
        $query = self::execute(
            "SELECT b.*, u.pseudo, u.picture_path
             FROM bids b
             JOIN users u ON b.owner = u.id
             WHERE b.item = :item_id AND b.created_at <= :now
             ORDER BY b.amount DESC, b.created_at DESC",
            ['item_id' => $item_id, 'now' => $now]
        );
        $bids = [];
        foreach ($query->fetchAll() as $row) {
            $bids[] = new Bid(
                (int)$row['item'],
                (int)$row['owner'],
                (float)$row['amount'],
                null,
                $row['created_at'],
                $row['pseudo'],
                $row['picture_path']
            );
        }
        return $bids;
    }
    
    public static function user_has_bid(int $user_id, int $item_id): bool {
        $now = AppTime::get_current_datetime();
        $query = self::execute(
            "SELECT COUNT(*) FROM bids
             WHERE owner = :user_id AND item = :item_id AND created_at <= :now",
            ['user_id' => $user_id, 'item_id' => $item_id, 'now' => $now]
        );
        return $query->fetchColumn() > 0;
    }
    
    public static function is_user_highest(int $user_id, int $item_id): bool {
        $now = AppTime::get_current_datetime();
        $query = self::execute(
            "SELECT owner FROM bids
             WHERE item = :item_id AND created_at <= :now
             ORDER BY amount DESC, created_at DESC LIMIT 1",
            ['item_id' => $item_id, 'now' => $now]
        );
        $highest_Id = $query->fetchColumn();
        return $highest_Id !== false && (int)$highest_Id === $user_id;
    }

    public static function exists_recent_duplicate(
        int $item_id, 
        int $user_id, 
        int $seconds
    ): bool {
        $now = AppTime::get_current_datetime();
        
        $query = self::execute(
            "SELECT COUNT(*) FROM bids 
             WHERE item = :item_id 
             AND owner = :user_id 
             AND created_at >= DATE_SUB(:now, INTERVAL {$seconds} SECOND)",
            [
                'item_id' => $item_id,
                'user_id' => $user_id,
                'now' => $now
            ]
        );
        
        return $query->fetchColumn() > 0;
    }

}