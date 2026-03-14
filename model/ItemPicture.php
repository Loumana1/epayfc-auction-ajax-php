<?php
require_once 'framework/Model.php';

class ItemPicture extends Model
{
    public int $item;
    public string $picture_path;
    public int $priority;

    public function __construct(int $item, string $picture_path, int $priority)
    {
        $this->item = $item;
        $this->picture_path = $picture_path;
        $this->priority = $priority;
    }

    public static function get_main_picture(int $item_id): ?ItemPicture
    {
        $query = self::execute("SELECT * FROM item_pictures WHERE item = :id AND priority = 1", ["id" => $item_id]);
        $data = $query->fetch();
        if ($data) {
            return new ItemPicture($data['item'], $data['picture_path'], $data['priority']);
        }
        return null;
    }

    public static function get_all_by_item(int $item_id): array
    {
        $query = self::execute(
            "SELECT * FROM item_pictures WHERE item = :id ORDER BY priority ASC",
            ["id" => $item_id]
        );
        $pictures = [];
        while ($data = $query->fetch()) {
            $pictures[] = new ItemPicture($data['item'], $data['picture_path'], $data['priority']);
        }
        return $pictures;
    }

    public static function get_next_priority(int $item_id): int
    {
        $query = self::execute(
            "SELECT MAX(priority) as max_priority FROM item_pictures WHERE item = :id",
            ["id" => $item_id]
        );
        $data = $query->fetch();
        return ($data && $data['max_priority']) ? $data['max_priority'] + 1 : 1;
    }

    public static function add(int $item_id, string $picture_path): bool
    {
        $priority = self::get_next_priority($item_id);
        $query = self::execute(
            "INSERT INTO item_pictures (item, priority, picture_path) VALUES (:item, :priority, :path)",
            ["item" => $item_id, "priority" => $priority, "path" => $picture_path]
        );
        return $query !== false;
    }

    public static function delete(int $item_id, int $priority): bool
    {
        $query = self::execute(
            "SELECT picture_path FROM item_pictures WHERE item = :item AND priority = :priority",
            ["item" => $item_id, "priority" => $priority]
        );
        $data = $query->fetch();

        if (!$data)
            return false;

        self::execute(
            "DELETE FROM item_pictures WHERE item = :item AND priority = :priority",
            ["item" => $item_id, "priority" => $priority]
        );

        self::execute(
            "UPDATE item_pictures SET priority = priority - 1 WHERE item = :item AND priority > :priority",
            ["item" => $item_id, "priority" => $priority]
        );

        return true;
    }

    public static function move_left(int $item_id, int $priority): bool
    {
        if ($priority <= 1)
            return false;
        return self::swap_priorities($item_id, $priority, $priority - 1);
    }

    public static function move_right(int $item_id, int $priority): bool
    {
        $max_priority = self::get_next_priority($item_id) - 1;
        if ($priority >= $max_priority)
            return false;
        return self::swap_priorities($item_id, $priority, $priority + 1);
    }

    private static function swap_priorities(int $item_id, int $priority1, int $priority2): bool
    {
        $temp_priority = self::get_temp_priority();

        self::execute(
            "UPDATE item_pictures SET priority = :temp WHERE item = :item AND priority = :p1",
            ["temp" => $temp_priority, "item" => $item_id, "p1" => $priority1]
        );

        self::execute(
            "UPDATE item_pictures SET priority = :p1 WHERE item = :item AND priority = :p2",
            ["p1" => $priority1, "item" => $item_id, "p2" => $priority2]
        );

        self::execute(
            "UPDATE item_pictures SET priority = :p2 WHERE item = :item AND priority = :temp",
            ["p2" => $priority2, "item" => $item_id, "temp" => $temp_priority]
        );

        return true;
    }

    private static function get_temp_priority(): int
    {
        $config = parse_ini_file("config/dev.ini");
        return (int)($config['temp_priority'] ?? 9999);
    }
    
    //supprime toutes les images d'un item
    public static function delete_all_by_item(int $itemId): void {
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
    
        self::execute(
            "DELETE FROM item_pictures WHERE item = :id",
            ['id' => $itemId]
        );
    }
}