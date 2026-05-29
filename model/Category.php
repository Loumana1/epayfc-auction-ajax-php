<?php

require_once "framework/Model.php";

class Category extends Model {

    public int $id;
    public string $name;
    public int $priority;
    public int $item_count = 0;

    public function __construct(int $id, string $name, int $priority = 0) {
        $this->id = $id;
        $this->name = $name;
        $this->priority = $priority;
    }

    public static function get_all(): array {
        $query = self::execute("SELECT * FROM categories ORDER BY priority ASC", []);
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Category($row['id'], $row['name'], $row['priority']);
        }
        return $result;
    }

    public static function get_all_with_counts(): array {
        $query = self::execute(
            "SELECT c.id, c.name, c.priority, COUNT(ic.item) as item_count
             FROM categories c
             LEFT JOIN item_categories ic ON c.id = ic.category
             GROUP BY c.id, c.name, c.priority
             ORDER BY c.priority ASC",
            []
        );
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $cat = new Category($row['id'], $row['name'], $row['priority']);
            $cat->item_count = (int)$row['item_count'];
            $result[] = $cat;
        }
        return $result;
    }

    public static function get_by_id(int $id): ?Category {
        $query = self::execute("SELECT * FROM categories WHERE id = :id", ['id' => $id]);
        $row = $query->fetch();
        if (!$row) return null;
        return new Category($row['id'], $row['name'], $row['priority']);
    }

    public static function name_exists(string $name): bool {
        $query = self::execute(
            "SELECT COUNT(*) as cnt FROM categories WHERE name = :name",
            ['name' => $name]
        );
        return (int)$query->fetch()['cnt'] > 0;
    }

    public static function has_items(int $id): bool {
        $query = self::execute(
            "SELECT COUNT(*) as cnt FROM item_categories WHERE category = :id",
            ['id' => $id]
        );
        return (int)$query->fetch()['cnt'] > 0;
    }

    public static function validate_name(string $name): array {
        $errors = [];
        $len = mb_strlen($name);
        if ($len < 3) $errors[] = "Name must be at least 3 characters.";
        if ($len > 25) $errors[] = "Name must be at most 25 characters.";
        return $errors;
    }

    public static function create(string $name): Category {
        $query = self::execute("SELECT COALESCE(MAX(priority), 0) + 1 as next_p FROM categories", []);
        $next = (int)$query->fetch()['next_p'];
        self::execute(
            "INSERT INTO categories (name, priority) VALUES (:name, :priority)",
            ['name' => $name, 'priority' => $next]
        );
        $cat = new Category(self::lastInsertId(), $name, $next);
        $cat->item_count = 0;
        return $cat;
    }

    public static function update_name(int $id, string $name): void {
        self::execute("UPDATE categories SET name = :name WHERE id = :id", ['name' => $name, 'id' => $id]);
    }

    public static function delete_by_id(int $id): void {
        self::execute("DELETE FROM categories WHERE id = :id", ['id' => $id]);
    }

    public static function move_up(int $id): void {
        $cat = self::get_by_id($id);
        if (!$cat) return;
        $query = self::execute(
            "SELECT * FROM categories WHERE priority < :p ORDER BY priority DESC LIMIT 1",
            ['p' => $cat->priority]
        );
        $row = $query->fetch();
        if (!$row) return;
        self::execute("UPDATE categories SET priority = -1 WHERE id = :id", ['id' => $id]);
        self::execute("UPDATE categories SET priority = :p WHERE id = :id", ['p' => $cat->priority, 'id' => $row['id']]);
        self::execute("UPDATE categories SET priority = :p WHERE id = :id", ['p' => $row['priority'], 'id' => $id]);
    }

    public static function move_down(int $id): void {
        $cat = self::get_by_id($id);
        if (!$cat) return;
        $query = self::execute(
            "SELECT * FROM categories WHERE priority > :p ORDER BY priority ASC LIMIT 1",
            ['p' => $cat->priority]
        );
        $row = $query->fetch();
        if (!$row) return;
        self::execute("UPDATE categories SET priority = -1 WHERE id = :id", ['id' => $id]);
        self::execute("UPDATE categories SET priority = :p WHERE id = :id", ['p' => $cat->priority, 'id' => $row['id']]);
        self::execute("UPDATE categories SET priority = :p WHERE id = :id", ['p' => $row['priority'], 'id' => $id]);
    }

    public static function update_priorities(array $ordered_ids): void {
        foreach ($ordered_ids as $i => $id) {
            self::execute("UPDATE categories SET priority = :p WHERE id = :id", ['p' => -($i + 1) * 1000, 'id' => (int)$id]);
        }
        foreach ($ordered_ids as $i => $id) {
            self::execute("UPDATE categories SET priority = :p WHERE id = :id", ['p' => $i + 1, 'id' => (int)$id]);
        }
    }

    public static function get_by_item(int $item_id): array {
        $query = self::execute(
            "SELECT c.* FROM categories c
            JOIN item_categories ic ON c.id = ic.category
            WHERE ic.item = :item_id
            ORDER BY c.name ASC",
            ['item_id' => $item_id]
        );
        $data = $query->fetchAll();

        $categories = [];
        foreach ($data as $row) {
            $categories[] = new Category($row['id'], $row['name'], $row['priority'] ?? 0);
        }
        return $categories;
    }
}
