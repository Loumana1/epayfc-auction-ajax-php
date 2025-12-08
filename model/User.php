<?php 

require_once "framework/Model.php";

class User extends Model {

    public function __construct(public string $pseudo, public string $hashed_password, public ?string $profile =null, public ?string $picture_path = null )
    {
        throw new \Exception('Not implemented');
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