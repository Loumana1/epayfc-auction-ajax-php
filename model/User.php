<?php
require_once "framework/Model.php";

class User extends Model {

    public int $id;
    public string $full_name;
    public string $pseudo;
    private string $email;
    public string $role;
    public ?string $picture_path;
    public ?string $iban;
    private ?string $hashed_password;

    public function __construct(
        int $id,
        string $full_name,
        string $pseudo,
        string $email,
        string $role,
        ?string $picture_path = null,
        ?string $iban = null,
        ?string $hashed_password = null
    ) {
        $this->id = $id;
        $this->full_name = $full_name;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->role = $role;
        $this->picture_path = $picture_path;
        $this->iban = $iban;
        $this->hashed_password = $hashed_password;
    }


    public static function get_user_by_mail(string $email): ?User {
    $query = self::execute(
        "SELECT * FROM users WHERE email = :email",
        ["email" => $email]
    );
    $row = $query->fetch();

    if (!$row) {
        return null;
    }

    return new User(
        $row["id"],
        $row["full_name"],
        $row["pseudo"],
        $row["email"],
        $row["role"],
        $row["picture_path"] ?? null,
        $row["iban"] ?? null,
        $row["password"] ?? null
    );
    }


    
     public function check_password(string $password): bool {
        return $this->hashed_password !== null
            && password_verify($password, $this->hashed_password);
    }

    
    
    public function get_email(): string {
            return $this->email;
    }
    

    public function get_Id(): int {
        return $this->id;
    }

           // choper info de l'utilisateur courant
           public static function get_User_By_Id(int $userId): User|false {
            $query = self::execute("SELECT * FROM users WHERE id = :id", ['id' => $userId]);
            $data = $query->fetch();
            if ($data === false) { 
                return false;
            } else {
                return new User($data["id"],
                 $data["full_name"],
                  $data["pseudo"],
                   $data["email"],
                    $data["role"], 
                    $data["picture_path"], 
                    $data["iban"],
                    $data["password"] ?? null);
            }
        }

    public function get_Pseudo(): string {
        return $this->pseudo;
    }


    public function get_FullName(): string{
        return $this->full_name;
    }
public function get_Thumbnail_Path(): ?string {
    if (!$this->picture_path) {
        return null;
    }
    return str_replace('.jpg', '_thumbnail.jpg', $this->picture_path);
}

public function has_Picture(): bool {
    return !empty($this->picture_path);
}

public function get_user_or_false () {

    return null;

}


    private static function validate_password(string $password): array {
        $errors = [];
       if (strlen($password) < 8 || strlen($password) > 16) {
        $errors[] = "Password length must be between 8 and 16.";
    }

    // Majuscule
    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }

    // Minuscule
    if (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }

    // Chiffre
    if (!preg_match("/\d/", $password)) {
        $errors[] = "Password must contain at least one number.";
    }

    // Non alphanumérique (IMPORTANT: conforme à l’énoncé)
    if (!preg_match("/[^a-zA-Z0-9]/", $password)) {
        $errors[] = "Password must contain at least one non-alphanumeric character.";
    }

    return $errors;
    }


    public static function validate_passwords(string $password, string $password_confirm): array {
        $errors = self::validate_password($password);
        if ($password !== $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }


public function get_hashed_password(): ?string {
    if ($this->hashed_password) {
        return $this->hashed_password;
    }
    // Si pas dans l'objet, récupérer depuis la DB
    $query = self::execute("SELECT password FROM users WHERE id = :id", ['id' => $this->id]);
    $data = $query->fetch();
    return $data ? $data['password'] : null;
}

public static function update_password(int $userId, string $hashedPassword): void {
    self::execute(
        "UPDATE users SET password = :password WHERE id = :id",
        ['password' => $hashedPassword, 'id' => $userId]
    );
}


public static function validate_change_password(self $user, ?string $currentPassword, ?string $newPassword, ?string $confirmPassword): array {
    $fieldErrors = [
        'current_password' => [],
        'new_password' => [],
        'confirm_password' => []
    ];

    $hashedPassword = $user->get_hashed_password();
    if (!$currentPassword || !$hashedPassword || !password_verify($currentPassword, $hashedPassword)) {
        $fieldErrors['current_password'][] = "Current password is incorrect.";
    }

    if ($newPassword !== null && $newPassword !== '' && $confirmPassword !== null && $confirmPassword !== '') {
        $validationErrors = self::validate_passwords($newPassword, $confirmPassword);
        foreach ($validationErrors as $error) {
            if (strpos($error, 'twice the same') !== false) {
                $fieldErrors['confirm_password'][] = $error;
            } elseif (strpos($error, 'length') !== false || strpos($error, '8 and 16') !== false) {
                $fieldErrors['new_password'][] = $error;
            } elseif (strpos($error, 'uppercase') !== false || strpos($error, 'number') !== false || strpos($error, 'punctuation') !== false) {
                $fieldErrors['new_password'][] = $error;
            } else {
                $fieldErrors['new_password'][] = $error;
            }
        }
    } else {
        if (!$newPassword) {
            $fieldErrors['new_password'][] = "Please enter a new password.";
        }
        if (!$confirmPassword) {
            $fieldErrors['confirm_password'][] = "Please confirm your new password.";
        }
    }

    return $fieldErrors;
}
// ============ SIGN UP METHODS ============

    public static function email_exists(string $email): bool
    {
        $query = self::execute(
            "SELECT COUNT(*) as count FROM users WHERE email = :email",
            ["email" => $email]
        );
        $row = $query->fetch();
        return $row['count'] > 0;
    }

    public static function pseudo_exists(string $pseudo): bool
    {
        $query = self::execute(
            "SELECT COUNT(*) as count FROM users WHERE pseudo = :pseudo",
            ["pseudo" => $pseudo]
        );
        $row = $query->fetch();
        return $row['count'] > 0;
    }

    public static function full_name_exists(string $full_name): bool
    {
        $query = self::execute(
            "SELECT COUNT(*) as count FROM users WHERE full_name = :full_name",
            ["full_name" => $full_name]
        );
        $row = $query->fetch();
        return $row['count'] > 0;
    }

    public static function validate_signup(
        string $email,
        string $full_name,
        string $pseudo,
        string $password,
        string $password_confirm
    ): array {
        $errors = [
            'email' => [],
            'full_name' => [],
            'pseudo' => [],
            'password' => [],
            'password_confirm' => []
        ];

        // Email validation
        if (empty($email)) {
            $errors['email'][] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = "Invalid email format.";
        } elseif (self::email_exists($email)) {
            $errors['email'][] = "This email is already registered.";
        }

        // Full name validation
        if (empty($full_name)) {
            $errors['full_name'][] = "Full name is required.";
        } elseif (self::full_name_exists($full_name)) {
            $errors['full_name'][] = "This name is already taken.";
        }

        // Pseudo validation
        if (empty($pseudo)) {
            $errors['pseudo'][] = "Pseudo is required.";
        } elseif (self::pseudo_exists($pseudo)) {
            $errors['pseudo'][] = "This pseudo is already taken.";
        }

        // Password validation
        $passwordErrors = self::validate_passwords($password, $password_confirm);
        foreach ($passwordErrors as $error) {
            if (strpos($error, 'twice the same') !== false) {
                $errors['password_confirm'][] = $error;
            } else {
                $errors['password'][] = $error;
            }
        }

        return $errors;
    }

    public static function signup(
        string $email,
        string $full_name,
        string $pseudo,
        string $password
    ): ?User {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        self::execute(
            "INSERT INTO users (email, full_name, pseudo, password, role) 
             VALUES (:email, :full_name, :pseudo, :password, 'user')",
            [
                'email' => $email,
                'full_name' => $full_name,
                'pseudo' => $pseudo,
                'password' => $hashed
            ]
        );

        return self::get_user_by_mail($email);
    }

    public function get_picture_path(): ?string {
        return $this->picture_path;
    }

    public function set_picture_path(?string $path): void {
        $this->picture_path = $path;
    }

    public function save_picture(): void {
        self::execute(
            "UPDATE users SET picture_path = :path WHERE id = :id",
            [
                "path" => $this->picture_path,
                "id" => $this->id
            ]
        );
    }


}