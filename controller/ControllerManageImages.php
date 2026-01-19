<?php

require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'model/ModelItems.php';
require_once 'model/ItemPicture.php';

class ControllerManageImages extends Controller
{
    // Affiche la page de gestion des images
    public function index(): void
    {
        $itemId = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;

        if ($itemId <= 0) {
            $this->redirect("browser");
            return;
        }

        // Récupérer l'item
        $item = ModelItems::get_Item_By_Id($itemId);
        if (!$item) {
            $this->redirect("browser");
            return;
        }

        // Récupérer toutes les images de l'item
        $pictures = ItemPicture::get_all_by_item($itemId);
        $pictureCount = count($pictures);

        (new View("manage_images"))->show([
            'item' => $item,
            'pictures' => $pictures,
            'picture_count' => $pictureCount
        ]);
    }

    // Upload de nouvelles images
    public function upload(): void
    {
        $itemId = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;

        if ($itemId <= 0 || !isset($_FILES['images'])) {
            $this->redirect("manage_images", "index", (string) $itemId);
            return;
        }

        $uploadDir = "uploads/items/$itemId/";

        // Créer le répertoire s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $files = $_FILES['images'];
        $fileCount = is_array($files['name']) ? count($files['name']) : 1;

        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = is_array($files['name']) ? $files['name'][$i] : $files['name'];
            $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
            $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];

            if ($error === UPLOAD_ERR_OK) {
                // Générer un nom unique
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $extension;
                $filePath = $uploadDir . $newFileName;

                if (move_uploaded_file($tmpName, $filePath)) {
                    // Ajouter à la base de données
                    ItemPicture::add($itemId, $filePath);
                }
            }
        }

        $this->redirect("manage_images", "index", (string) $itemId);
    }

    // Supprime une image
    public function delete(): void
    {
        $itemId = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;
        $priority = isset($_GET['param2']) ? (int) $_GET['param2'] : 0;

        if ($itemId > 0 && $priority > 0) {
            ItemPicture::delete($itemId, $priority);
        }
        $this->redirect("manage_images", "index", (string) $itemId);
    }

    // Déplace l'image vers la gauche
    public function move_left(): void
    {
        $itemId = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;
        $priority = isset($_GET['param2']) ? (int) $_GET['param2'] : 0;

        if ($itemId > 0 && $priority > 1) {
            ItemPicture::move_left($itemId, $priority);
        }
        $this->redirect("manage_images", "index", (string) $itemId);
    }

    // Déplace l'image vers la droite
    public function move_right(): void
    {
        $itemId = isset($_GET['param1']) ? (int) $_GET['param1'] : 0;
        $priority = isset($_GET['param2']) ? (int) $_GET['param2'] : 0;

        if ($itemId > 0 && $priority > 0) {
            ItemPicture::move_right($itemId, $priority);
        }
        $this->redirect("manage_images", "index", (string) $itemId);
    }
}
