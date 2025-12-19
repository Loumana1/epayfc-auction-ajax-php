<?php
require_once 'framework/Model.php';

class ItemPicture extends Model {
    public int $item;
    public string $picture_path;
    public int $priority;

    public function __construct(int $item, string $picture_path, int $priority) {
        $this->item = $item;
        $this->picture_path = $picture_path;
        $this->priority = $priority;
    }

    // Récupère la photo principale d'un item
    public static function get_main_picture(int $itemId): ?ItemPicture {
        $query = self::execute("SELECT * FROM item_pictures WHERE item = :id AND priority = 1", ["id" => $itemId]);
        $data = $query->fetch();
        if ($data) {
            return new ItemPicture($data['item'], $data['picture_path'], $data['priority']);
        }
        return null;
    }

}