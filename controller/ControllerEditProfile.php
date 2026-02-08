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
        $current_user_id = $current_user ? $current_user->get_Id() : null;

        if (!$current_user) {
            $this->redirect("profile");
            return;
        }

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

    $userId = $current_user->get_Id();

        $full_name = $_POST['full_name'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $email = $_POST['email'] ?? '';
        $iban = $_POST['iban'] ?? '';

        $errors = [];

        // Validation
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
            // Mettre à jour
            ModelEditProfile::update_user($userId, $full_name, $pseudo, $email, $iban);
            $this->redirect("profile");
            return;
        }

        // Afficher avec erreurs
        (new View("edit_profile"))->show([
            'user' => [
                'id' => $userId,
                'full_name' => $full_name,
                'pseudo' => $pseudo,
                'email' => $email,
                'iban' => $iban
            ],
            'errors' => $errors,
            'success' => false
        ]);
    }
}

class ModelEditProfile extends \Model
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
            'iban' => $data['iban'] ?? ''
        ];
    }

    public static function update_user(int $user_ud, string $full_name, string $pseudo, string $email, string $iban): bool
    {
        $query = self::execute(
            "UPDATE users SET full_name = :full_name, pseudo = :pseudo, email = :email, iban = :iban WHERE id = :id",
            [
                'id' => $user_ud,
                'full_name' => $full_name,
                'pseudo' => $pseudo,
                'email' => $email,
                'iban' => $iban ?: null
            ]
        );
        return $query !== false;
    }
}
