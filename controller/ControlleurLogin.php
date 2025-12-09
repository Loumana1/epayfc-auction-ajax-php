<?php       

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControlleurLogin extends Controller {

    public function index() : void {
        (new View("login"))->show ([
            "mail" => "",
            "errors" => []
        ]);
    }

    public function login(): void {
        $mail = $_POST["mail"] ?? "";
        $password = $_POST["password"] ?? "";
        $errors = [];
        if ($mail === "" || $password === "") {
            $errors[] = "erreur : le Mail et mot de passe sont requis";
        }

        $user = User::get_user_by_mail($mail);

        if (!$user) {
            $errors[] = "Unknown user.";
        }

        if (empty($errors) && !$user->check_password($password)) {
            $errors[] = "Erreur : Mot de passe incorrect.";
        }

        if (!empty($errors)) {

            (new View("login"))->show([
                "mail" => $mail,
                "errors" => $errors
            ]);

            return; 
        }

         $this->log_user($user);
         $this->redirect("home"); 
         // encore a definir pour la redirection 
    
    }
}