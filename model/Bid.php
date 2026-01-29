<?php
require_once "framework/Model.php";
require_once "utils/AppTime.php";
class Bid extends Model {
    
    private ?int $id;
    private int $item_id;
    private int $owner_id;
    private float $amount;
    private string $created_at;
    
    public function __construct(
        int $item_id,
        int $owner_id,
        float $amount,
        ?int $id = null,
        ?string $created_at = null
    ) {
        $this->id = $id;
        $this->item_id = $item_id;
        $this->owner_id = $owner_id;
        $this->amount = $amount;
        $this->created_at = $created_at ?? AppTime::get_current_datetime();
    }
    

    public function get_id(): ?int { return $this->id; }
    public function get_item_id(): int { return $this->item_id; }
    public function get_owner_id(): int { return $this->owner_id; }
    public function get_amount(): float { return $this->amount; }
    public function get_created_at(): string { return $this->created_at; }
    

    public function validate(): array {
        require_once "model/Item.php";
        $errors = [];
        $now = AppTime::get_current_datetime();
        $nowDateTime = new DateTime($now);
        
    
        $item = Item::get_by_id($this->item_id);
        if ($item === false) {
            $errors[] = "Item introuvable";
            return $errors;
        }
        

        if ($item->get_owner() == $this->owner_id) {
            $errors[] = "Vous ne pouvez pas enchérir sur votre propre annonce";
        }
        

        if (!$item->is_open()) {
            $errors[] = "Cette annonce est fermée";
        }
        

        $minBid = $item->get_min_bid_amount();
        if ($this->amount < $minBid) {
            $errors[] = "Le montant minimum est " . number_format($minBid, 2);
        }
        
        return $errors;
    }
    

    public function persist(): array {
        $errors = $this->validate();
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
    
 
    public static function get_by_item(int $itemId): array {
        $now = AppTime::get_current_datetime();
        $query = self::execute(
            "SELECT b.*, u.pseudo, u.picture_path
             FROM bids b
             JOIN users u ON b.owner = u.id
             WHERE b.item = :item_id AND b.created_at <= :now
             ORDER BY b.amount DESC, b.created_at DESC",
            ['item_id' => $itemId, 'now' => $now]
        );
        return $query->fetchAll();
    }
    
    public static function user_has_bid(int $userId, int $itemId): bool {
        $now = AppTime::get_current_datetime();
        $query = self::execute(
            "SELECT COUNT(*) FROM bids
             WHERE owner = :user_id AND item = :item_id AND created_at <= :now",
            ['user_id' => $userId, 'item_id' => $itemId, 'now' => $now]
        );
        return $query->fetchColumn() > 0;
    }
    
    public static function is_user_highest(int $userId, int $itemId): bool {
        $now = AppTime::get_current_datetime();
        $query = self::execute(
            "SELECT owner FROM bids
             WHERE item = :item_id AND created_at <= :now
             ORDER BY amount DESC, created_at DESC LIMIT 1",
            ['item_id' => $itemId, 'now' => $now]
        );
        $highestId = $query->fetchColumn();
        return $highestId !== false && (int)$highestId === $userId;
    }
}