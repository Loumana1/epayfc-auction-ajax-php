<?php
require_once "framework/Model.php";

class User extends Model {
    //Je prends tt les proprotes pour moment 
    //Verifier ce que les autres ont mis dans leur Model User 
    // faudra merge rapidement pour que ca soit pas un bouzouf
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
}