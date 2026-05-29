<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerLogin extends Controller {

    public function index() : void {

        if ($this->user_logged()) {
            $this->redirect("browser");
        }

        (new View("login"))->show([
            "no_header_footer" => true,
            "header_title" => "Login",
            "page_css" => ["login.css"],
            "mail" => "",
            "errors" => ['mail' => [], 'password' => []],
            "dev_users" => Configuration::is_dev() ? User::get_all_users() : []
        ]);
    }

    public function login(): void {

        if ($this->user_logged()) {
            $this->redirect("browser");
        }

        $mail = $_POST["mail"] ?? "";
        $password = $_POST["password"] ?? "";

        $errors = ['mail' => [], 'password' => []];

        if ($mail === "") {
            $errors['mail'][] = "Mail is required.";
        }
        if ($password === "") {
            $errors['password'][] = "Password is required.";
        }

        if (empty($errors['mail']) && empty($errors['password'])) {
            $user = User::get_user_by_mail($mail);
            if (!$user) {
                $errors['mail'][] = "Unknown user.";
            } elseif (!$user->check_password($password)) {
                $errors['password'][] = "Incorrect password.";
            } else {
                $this->log_user($user, "browser");
                return;
            }
        }

        (new View("login"))->show([
            "no_header_footer" => true,
            "header_title" => "Login",
            "page_css" => ["login.css"],
            "mail" => $mail,
            "errors" => $errors,
            "dev_users" => Configuration::is_dev() ? User::get_all_users() : []
        ]);
    }

    public function login_as(): void {

        if (!Configuration::is_dev()) {
            $this->redirect("login");
        }

        $id = (int)($_GET['param1'] ?? 0);

        if ($id > 0) {
            $user = User::get_User_By_Id($id);
            if ($user) {
                $this->log_user($user, "browser");
            }
        }

        $this->redirect("login");
    }

}
