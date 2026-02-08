<?php

require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'model/ItemPicture.php';
require_once 'model/Item.php';

class ControllerManageImages extends Controller
{
    // Affiche la page de gestion des images
    public function index(): void {
        $current_user = $this->get_user_or_false();
        $current_user_id = $current_user ? $current_user->get_Id() : null; // apres corrige
        $item_id = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;

        if ($item_id <= 0) {
            $this->redirect("browser");
            return;
        }

        // Récupérer l'item
        $item = Item::get_by_id($item_id);
        if (!$item) {
            $this->redirect("browser");
            return;
        }

        // Récupérer toutes les images de l'item
        $pictures = ItemPicture::get_all_by_item($item_id);
        $picture_count = count($pictures);

        (new View("manage_images"))->show([
            'item' => $item,
            'pictures' => $pictures,
            'picture_count' => $picture_count,
            'current_user_id' => $current_user_id,
            'currentUser' => $current_user,
            'header_title' => 'Manage images',
            'header_icon' => 'bi-image-fill'
        ]);
    }

    // Upload de nouvelles images
    public function upload(): void
    {
        $item_id = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;

        if ($item_id <= 0 || !isset($_FILES['images'])) {
            $this->redirect("manage_images", "index", (string) $item_id);
            return;
        }

        $upload_dir = "uploads/items/$item_id/";

        // Créer le répertoire s'il n'existe pas
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $files = $_FILES['images'];
        $file_count = is_array($files['name']) ? count($files['name']) : 1;

        for ($i = 0; $i < $file_count; $i++) {
            $file_name = is_array($files['name']) ? $files['name'][$i] : $files['name'];
            $tmp_name = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
            $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];

            if ($error === UPLOAD_ERR_OK) {
                // Générer un nom unique
                $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_file_name = bin2hex(random_bytes(8)) . '_' . time() . '.' . $extension;
                $file_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($tmp_name, $file_path)) {
                    // Ajouter à la base de données
                    ItemPicture::add($item_id, $file_path);
                }
            }
        }

        $this->redirect("manage_images", "index", (string) $item_id);
    }

    // Supprime une image
    public function delete(): void
    {
        $item_id = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;
        $priority = isset($_GET['param2']) ? (int) $_GET['param2'] : 0;

        if ($item_id > 0 && $priority > 0) {
            ItemPicture::delete($item_id, $priority);
        }
        $this->redirect("manage_images", "index", (string) $item_id);
    }

    // Déplace l'image vers la gauche
    public function move_left(): void
    {
        $item_id = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;
        $priority = isset($_GET['param2']) ? (int) $_GET['param2'] : 0;

        if ($item_id > 0 && $priority > 1) {
            ItemPicture::move_left($item_id, $priority);
        }
        $this->redirect("manage_images", "index", (string) $item_id);
    }

    // Déplace l'image vers la droite
    public function move_right(): void
    {
        $item_id = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;
        $priority = isset($_GET['param2']) ? (int) $_GET['param2'] : 0;

        if ($item_id > 0 && $priority > 0) {
            ItemPicture::move_right($item_id, $priority);
        }
        $this->redirect("manage_images", "index", (string) $item_id);
    }
}
