<?php

require_once "framework/Model.php";
require_once "framework/Configuration.php";

class Item extends Model {

    private ?int $id;
    private string $title;
    private ?string $description;
    private int $owner;
    private string $created_at;
    private ?float $buy_now_price;
    private int $duration_days;
    private ?float $starting_bid;

    public function __construct(
        ?int $id,
        string $title,
        ?string $description,
        int $owner,
        string $created_at,
        ?float $buy_now_price,
        int $duration_days,
        ?float $starting_bid
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->owner = $owner;
        $this->created_at = $created_at;
        $this->buy_now_price = $buy_now_price;
        $this->duration_days = $duration_days;
        $this->starting_bid = $starting_bid;
    }

    public function get_id(): ?int { return $this->id; }
    public function get_title(): string { return $this->title; }
    public function get_description(): ?string { return $this->description; }
    public function get_buy_now_price(): ?float { return $this->buy_now_price; }
    public function get_duration_days(): int { return $this->duration_days; }
    public function get_starting_bid(): ?float { return $this->starting_bid; }
    public function get_owner(): int { return $this->owner; }

    public static function get_by_id(int $id): ?Item {
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


    public function get_created_at(): string { 
        return $this->created_at; 
    }


    public function validate(): array {
        $errors = [];

        $title = trim($this->title);
        $min = (int)Configuration::get("title_min_length", 3);
        $max = (int)Configuration::get("title_max_length", 255);

        if (strlen($title) < $min || strlen($title) > $max) {
            $errors["title"] = "Title length must be between $min and $max characters.";
        } else if (self::title_exists_for_owner($title, $this->owner, $this->id)) {
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

        $sb_is_auction = ($sb !== null && $sb > 0);

        if ($sb !== null && $sb < 0) {
            $errors["starting_bid"] = "Starting bid must be greater than 0.";
        }

        if ($bn !== null && $bn <= 0) {
            $errors["buy_now_price"] = "Buy now price must be greater than 0.";
        }

        if ($sb_is_auction) {
            if ($bn !== null && $bn <= $sb) {
                $errors["buy_now_price"] = "Buy now price must be greater than the starting bid.";
            }
        } else {
            // vente directe : starting_bid null ou 0 => buy_now obligatoire
            if ($bn === null) {
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
}
