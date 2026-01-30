<?php
require_once "framework/Model.php";

class User extends Model {

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
        ?string $iban
    ) {
        $this->id = $id;
        $this->full_name = $full_name;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->role = $role;
        $this->picture_path = $picture_path;
        $this->iban = $iban;
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
                    $data["iban"]);
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



private static function validate_password(string $password) : array {
    $errors = [];
    if (strlen($password) <  8 || strlen($password) > 16) {
        $errors[] = "Password length must be between 8 and 16.";
    } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
        $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark." ;
    }
    return $errors;
}

public static function validate_passwords(string $password, string $password_confirm) : array {
        $errors = user::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
}

}