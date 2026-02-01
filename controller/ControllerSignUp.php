<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerSignup extends Controller
{

    public function index(): void
    {
        $this->register();
    }

    public function register(): void
    {
        $errors = [
            'email' => [],
            'full_name' => [],
            'pseudo' => [],
            'password' => [],
            'password_confirm' => []
        ];

        $email = "";
        $full_name = "";
        $pseudo = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST["email"] ?? "");
            $full_name = trim($_POST["full_name"] ?? "");
            $pseudo = trim($_POST["pseudo"] ?? "");
            $password = $_POST["password"] ?? "";
            $password_confirm = $_POST["password_confirm"] ?? "";

            $errors = User::validate_signup($email, $full_name, $pseudo, $password, $password_confirm);

            // Check if there are any errors
            $hasErrors = false;
            foreach ($errors as $fieldErrors) {
                if (!empty($fieldErrors)) {
                    $hasErrors = true;
                    break;
                }
            }

            if (!$hasErrors) {
                // Registration successful
                $user = User::signup($email, $full_name, $pseudo, $password);

                if ($user) {
                    $_SESSION['success_message'] = "Registration successful! Please log in.";
                    $this->redirect("login");
                    return;
                } else {
                    $errors['email'][] = "Registration failed. Please try again.";
                }
            }
        }

        (new View("signup"))->show([
            "email" => $email,
            "full_name" => $full_name,
            "pseudo" => $pseudo,
            "errors" => $errors
        ]);
    }
}
