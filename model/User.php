<?php 

require_once "framework/Model.php";

class User extends Model {

     private string $mail;
    private string $hashed_password;

    public function __construct(string $mail, string $hashed_password) {
        $this->mail = $mail;
        $this->hashed_password = $hashed_password;
    }


     public function get_mail(): string {
        return $this->mail;
    }

  
    public function check_password(string $password): bool {
        return password_verify($password, $this->hashed_password);
    }

    public static function get_user_by_mail(string $mail): ?User {
        $query = self::execute("SELECT * FROM user WHERE mail = :mail", ["mail" => $mail]);
        $row = $query->fetch();

        if (!$row) {
            return null;
        }
        return new User($row["mail"], $row["hashed_password"]);
    }

    private static function validate_password(string $password): array {
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        }
        if (!preg_match("/[A-Z]/", $password) ||
            !preg_match("/\d/", $password) ||
            !preg_match("/['\";:,.\/?!\\-]/", $password)) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
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