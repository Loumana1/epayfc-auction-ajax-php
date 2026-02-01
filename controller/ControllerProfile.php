<?php

require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'model/User.php';

class ControllerProfile extends Controller
{
    public function index(): void
    {
        $currentUser = $this->get_user_or_false();
        $currentUserId = $currentUser ? $currentUser->get_Id() : null;

        if (!$currentUser) {
            $this->redirect("browser");
            return;
        }

        (new View("profile"))->show([
            'current_user_id' => $currentUserId,
            'currentUser' => $currentUser,
            'header_title' => 'Profile',
            'header_icon' => 'bi-cart-fill'
        ]);
    }

    public function logout(): void
    {
        // Déconnexion
        $_SESSION = array();
        session_destroy();
        $this->redirect("browser");
    }
}

// Classe helper pour les requêtes profile
class ModelProfile extends \Model
{
    public static function get_user_data(int $userId): ?array
    {
        $query = self::execute("SELECT * FROM users WHERE id = :id", ['id' => $userId]);
        $data = $query->fetch();

        if ($data === false) {
            return null;
        }

        return [
            'id' => $data['id'],
            'full_name' => $data['full_name'],
            'pseudo' => $data['pseudo'],
            'email' => $data['email'],
            'role' => $data['role'],
            'picture_path' => $data['picture_path'] ?? null,
            'iban' => $data['iban'] ?? null
        ];
    }
}
