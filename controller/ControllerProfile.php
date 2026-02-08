<?php

require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'model/User.php';

class ControllerProfile extends Controller
{
    public function index(): void
    {
        $current_user = $this->get_user_or_false();
        
        if (!$current_user) {
            $this->redirect("login");
            return;
        }

        $current_user_id = $current_user->get_Id();

        (new View("profile"))->show([
            'current_user_id' => $current_user_id,
            'current_user' => $current_user, // Utilise snake_case ici pour la vue
            'header_title' => 'Profile',
            'header_icon' => 'bi-cart-fill'
        ]);
    }

    public function logout(): void
    {
        $current_user = $this->get_user_or_false();
        if (!$current_user) {
            $this->redirect("login");
            return;
        }

        session_destroy();
        $this->redirect("login");
    }
}

class ModelProfile extends \Model
{
    public static function get_user_data(int $user_id): ?array
    {
        $query = self::execute("SELECT * FROM users WHERE id = :id", ['id' => $user_id]);
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