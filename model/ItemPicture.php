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

    // Récupère la photo principale d'un item
    public static function get_main_picture(int $itemId): ?ItemPicture
    {
        $query = self::execute("SELECT * FROM item_pictures WHERE item = :id AND priority = 1", ["id" => $itemId]);
        $data = $query->fetch();
        if ($data) {
            return new ItemPicture($data['item'], $data['picture_path'], $data['priority']);
        }
        return null;
    }

    // Récupère toutes les images d'un item ordonnées par priorité
    public static function get_all_by_item(int $itemId): array
    {
        $query = self::execute(
            "SELECT * FROM item_pictures WHERE item = :id ORDER BY priority ASC",
            ["id" => $itemId]
        );
        $pictures = [];
        while ($data = $query->fetch()) {
            $pictures[] = new ItemPicture($data['item'], $data['picture_path'], $data['priority']);
        }
        return $pictures;
    }

    // Récupère la prochaine priorité disponible pour un item
    public static function get_next_priority(int $itemId): int
    {
        $query = self::execute(
            "SELECT MAX(priority) as max_priority FROM item_pictures WHERE item = :id",
            ["id" => $itemId]
        );
        $data = $query->fetch();
        return ($data && $data['max_priority']) ? $data['max_priority'] + 1 : 1;
    }

    // Ajoute une nouvelle image avec la priorité suivante
    public static function add(int $itemId, string $picturePath): bool
    {
        $priority = self::get_next_priority($itemId);
        $query = self::execute(
            "INSERT INTO item_pictures (item, priority, picture_path) VALUES (:item, :priority, :path)",
            ["item" => $itemId, "priority" => $priority, "path" => $picturePath]
        );
        return $query !== false;
    }

    // Supprime une image et réorganise les priorités
    public static function delete(int $itemId, int $priority): bool
    {
        // Récupérer le chemin de l'image pour la supprimer du disque
        $query = self::execute(
            "SELECT picture_path FROM item_pictures WHERE item = :item AND priority = :priority",
            ["item" => $itemId, "priority" => $priority]
        );
        $data = $query->fetch();

        if (!$data)
            return false;

        // Supprimer de la base de données
        self::execute(
            "DELETE FROM item_pictures WHERE item = :item AND priority = :priority",
            ["item" => $itemId, "priority" => $priority]
        );

        // Réorganiser les priorités (décaler toutes les images après)
        self::execute(
            "UPDATE item_pictures SET priority = priority - 1 WHERE item = :item AND priority > :priority",
            ["item" => $itemId, "priority" => $priority]
        );

        return true;
    }

    // Déplace une image vers la gauche (échange avec celle de priorité - 1)
    public static function move_left(int $itemId, int $priority): bool
    {
        if ($priority <= 1)
            return false;
        return self::swap_priorities($itemId, $priority, $priority - 1);
    }

    // Déplace une image vers la droite (échange avec celle de priorité + 1)
    public static function move_right(int $itemId, int $priority): bool
    {
        $maxPriority = self::get_next_priority($itemId) - 1;
        if ($priority >= $maxPriority)
            return false;
        return self::swap_priorities($itemId, $priority, $priority + 1);
    }

    // Échange les priorités de deux images
    private static function swap_priorities(int $itemId, int $priority1, int $priority2): bool
    {
        // Utiliser une priorité temporaire pour éviter les conflits de clé primaire
        $tempPriority = 9999;

        // Image 1 -> temp
        self::execute(
            "UPDATE item_pictures SET priority = :temp WHERE item = :item AND priority = :p1",
            ["temp" => $tempPriority, "item" => $itemId, "p1" => $priority1]
        );

        // Image 2 -> priority1
        self::execute(
            "UPDATE item_pictures SET priority = :p1 WHERE item = :item AND priority = :p2",
            ["p1" => $priority1, "item" => $itemId, "p2" => $priority2]
        );

        // temp -> priority2
        self::execute(
            "UPDATE item_pictures SET priority = :p2 WHERE item = :item AND priority = :temp",
            ["p2" => $priority2, "item" => $itemId, "temp" => $tempPriority]
        );

        return true;
    }

}