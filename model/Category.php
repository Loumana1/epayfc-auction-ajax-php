<?php

class Category extends Model {
    
    public int $id;
    public string $name;
    public int $priority;

    public function __construct(int $id, string $name, int $priority = 0) {
 
        $this -> id = $id;
        $this -> name = $name;
        $this -> priority = $priority;
    }

    public static function get_by_item(int $item_id): array {
        
        $query = self::execute(
            "SELECT c.* FROM categories c
            JOIN item_categories ic ON c.id = ic.category
            WHERE ic.item = :item_id
            ORDER BY c.name ASC",
            ['item_id' => $item_id]
        );
        $data = $query -> fetchAll();

        $categories = [];
        foreach ($data as $row) {
            $categories[] = new Category(
                $row['id'],
                $row['name'],
                $row['priority'] 
                ?? 0);
        } 
        return $categories;
    }
}