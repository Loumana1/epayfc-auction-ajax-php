<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerProfilePicture extends Controller {

    public function index(): void {
        $user = $this->get_user_or_redirect();

        // C'est ici que tu définis le titre et l'icône pour le header
        (new View("profile_picture"))->show([
            "currentUser" => $user,
            "header_title" => "Manage profile picture",
            "header_icon" => "bi-cart-fill" 
        ]);
    }

    public function upload(): void {
        $user = $this->get_user_or_redirect();

        if (!isset($_FILES["picture"]) || $_FILES["picture"]["error"] !== UPLOAD_ERR_OK) {
            $this->redirect("profile_picture");
        }

        $file = $_FILES["picture"];

        // Limite de taille (2MB)
        if ($file["size"] > 2 * 1024 * 1024) {
            $this->redirect("profile_picture");
        }

        // Validation du type MIME
        $allowed = ["image/jpeg", "image/png", "image/webp", "image/gif"];
        $mime = mime_content_type($file["tmp_name"]);

        if (!in_array($mime, $allowed)) {
            $this->redirect("profile_picture");
        }

        // Extension du fichier
        $ext = match ($mime) {
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/webp" => "webp",
            "image/gif"  => "gif",
        };

        // Création du dossier utilisateur si inexistant
        $userDir = "uploads/users/" . $user->get_id();
        if (!is_dir($userDir)) {
            mkdir($userDir, 0777, true);
        }

        $path = "$userDir/profile.$ext";
        $thumbPath = "$userDir/profile_thumbnail.$ext";

        // Suppression de l'ancienne image si elle existe
        if ($user->get_picture_path()) {
            @unlink($user->get_picture_path());
            @unlink(str_replace("profile.", "profile_thumbnail.", $user->get_picture_path()));
        }

        // Déplacement et copie
        move_uploaded_file($file["tmp_name"], $path);
        copy($path, $thumbPath);

        // Sauvegarde en base de données
        $user->set_picture_path($path);
        $user->save_picture();

        $this->redirect("profile_picture");
    }

    public function delete(): void {
        $user = $this->get_user_or_redirect();

        $path = $user->get_picture_path();

        if ($path) {
            @unlink($path);
            $thumb = str_replace("profile.", "profile_thumbnail.", $path);
            @unlink($thumb);
        }

        $user->set_picture_path(null);
        $user->save_picture();

        $this->redirect("profile_picture");
    }

}