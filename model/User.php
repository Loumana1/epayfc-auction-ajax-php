<?php
require_once "framework/Model.php";

class User extends Model {

    public int $id;
    public string $full_name;
    private string $email;
    public string $pseudo;
    private string $hashed_password;
    public string $role;

    public function __construct(int $id, string $full_name, string $email, string $pseudo, string $hashed_password, string $role) {
        $this->id = $id;
        $this->full_name = $full_name;
        $this->email = $email;
        $this->pseudo = $pseudo;
        $this->hashed_password = $hashed_password;
        $this->role = $role;
    }


     public function get_email(): string {
        return $this->email;
    }

  
    public function check_password(string $password): bool {
        return password_verify($password, $this->hashed_password);
    }

    public static function get_user_by_mail(string $email): ?User {
        $query = self::execute("SELECT * FROM users WHERE email = :email", ["email" => $email]);
        $row = $query->fetch();

        if (!$row) {
            return null;
        }
        return new User(
            (int)$row["id"], 
            $row["full_name"], 
            $row["email"], 
            $row["pseudo"], 
            $row["password"], 
            $row["role"]
        );
    }
    public $id;
    public $full_name;
    public $pseudo;
    public $email;
    public $role;
    public $picture_path;
    public $hashed_password;
    public $iban;

    public function __construct(
        int $id,
        string $full_name,
        string $pseudo,
        string $email,
        string $role,
        ?string $picture_path,
        ?string $iban,
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

}