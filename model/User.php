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