<?php       

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerLogin extends Controller {

    public function index() : void {
        (new View("login"))->show ([
            "mail" => "",
            "errors" => []
        ]);
    }

    public function login(): void {
        $mail = $_POST["mail"] ?? "";
        $password = $_POST["password"] ?? "";
     

        if ($mail === "" || $password === "") {
            (new View("login"))->show([
                "mail" => $mail,
                "errors" => ["Mail and Password are required."]
            ]);
            return;
        }

        $user = User::get_user_by_mail($mail);
        if (!$user) {
            (new View("login"))->show([
                "mail" => $mail,
                "errors" => ["Unknown user."]
            ]);
            return;
        }

        if (!$user->check_password($password)) {
            (new View("login"))->show([
                "mail" => $mail,
                "errors" => ["Incorrect Pasword."]
            ]);
            return;
        }

         $this->log_user($user);
         $this->redirect("browse_items"); 
         // encore a definir pour la redirection 
    
    }
}