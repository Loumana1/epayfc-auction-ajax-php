<?php
require_once 'Model/User.php';
require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'framework/Model.php';

class ControllerEditProfile extends Controller
{
    public function index(): void
    {
        $current_user = $this->get_user_or_false();
        if (!$current_user) {
            $this->redirect("profile");
            return;
        }

        $current_user_id = $current_user->get_Id();

        (new View("edit_profile"))->show([
            'current_user_id' => $current_user_id,
            'currentUser' => $current_user,
            'user' => [
                'full_name' => $current_user->full_name,
                'pseudo' => $current_user->pseudo,
                'email' => $current_user->get_email(),
                'iban' => $current_user->iban ?? ''
            ],
            'header_title' => 'Edit Profile',
            'header_icon' => 'bi-person-fill',
            'errors' => [],
            'success' => false
        ]);
    }

    public function save(): void
    {
        $current_user = $this->get_user_or_false();
        if (!$current_user) {
            $this->redirect("profile");
            return;
        }

        $user_id = $current_user->get_Id();
        $full_name = $_POST['full_name'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $email = $_POST['email'] ?? '';
        $iban = $_POST['iban'] ?? '';

        $errors = [];

        if (empty(trim($full_name))) {
            $errors[] = "Full name is required";
        }
        if (empty(trim($pseudo))) {
            $errors[] = "Username is required";
        }
        if (empty(trim($email))) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (empty($errors)) {
            ModelEditProfile::update_user($user_id, $full_name, $pseudo, $email, $iban);
            $this->redirect("profile");
            return;
        }

        (new View("edit_profile"))->show([
            'user' => [
                'id' => $user_id,
                'full_name' => $full_name,
                'pseudo' => $pseudo,
                'email' => $email,
                'iban' => $iban
            ],
            'errors' => $errors,
            'success' => false,
            'header_title' => 'Edit Profile',
            'header_icon' => 'bi-person-fill'
        ]);
    }
}

class ModelEditProfile extends \Model
{
    public static function get_user_data(int $user_id): ?array
    {
        $query = self::execute("SELECT * FROM users WHERE id = :id", ['id' => $user_id]);
        $data = $query->fetch();
        return $data ?: null;
    }

    public static function update_user(int $user_id, string $full_name, string $pseudo, string $email, string $iban): bool
    {
        $query = self::execute(
            "UPDATE users SET full_name = :full_name, pseudo = :pseudo, email = :email, iban = :iban WHERE id = :id",
            [
                'id' => $user_id,
                'full_name' => $full_name,
                'pseudo' => $pseudo,
                'email' => $email,
                'iban' => $iban ?: null
            ]
        );
        return $query !== false;
    }
}