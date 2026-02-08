<?php
require_once "framework/Controller.php";
require_once "model/User.php";
require_once "framework/Configuration.php";

class ControllerTest extends Controller {
    
    public function index(): void {
        $web_root = Configuration::get("web_root");
        
        echo "<!DOCTYPE html>";
        echo "<html lang='en'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<title>PRWB_2526_c04</title>";
        echo "<base href='$web_root'>";
        echo "</head>";
        echo "<body>";
        
        echo "<h1>Hello PRWB_2526_c04</h1>";
        
        // Liens de navigation
        echo "<p><a href='time'>Time management</a></p>";

        
        // Boutons de connexion rapide
        echo "<h2>Quick Login</h2>";
        echo "<form method='post' action='test/login_as'>";
        echo "<input type='hidden' name='user_id' value='4'>";
        echo "<button type='submit'>Login as Boris (ID 1)</button>";
        echo "</form><br>";
        
        echo "<form method='post' action='test/login_as'>";
        echo "<input type='hidden' name='user_id' value='2'>";
        echo "<button type='submit'>Login as Marc (ID 2)</button>";
        echo "</form><br>";
        
        echo "<form method='post' action='test/logout'>";
        echo "<button type='submit'>Logout</button>";
        echo "</form>";
        
        
        echo "<h2>Quick Navigation</h2>";
        echo "<p><a href='open_item/index/10'><button type='button'>Go to Item #10</button></a></p>";
        echo "<p><a href='open_item/index/1'>Open Item #1 owner quentin</a></p>";
        echo "<p><a href='open_item/index/2'>Open Item #2  owner MARC </a></p>";
        echo "<p><a href='open_item/index/10'>Open Item #10 Owner Boris</a></p>";
        echo "<p><a href='sales'><button type='button'>Go to Sales Page</button></a></p>";  // ← AJOUTER
echo "<p><a href='browser'><button type='button'>Go to Browser</button></a></p>";  
echo "<p><a href='user/change_password'>Change Password</a></p>"; 
        // Afficher l'utilisateur actuel
        $currentUser = $this->get_user_or_false();
        if ($currentUser) {
            echo "<p><strong>Currently logged as:</strong> " . $currentUser->get_Pseudo() . " (ID " . $currentUser->get_Id() . ")</p>";
        } else {
            echo "<h2><strong>Not logged in</strong></h2>";
        }
        
        echo "</body>";
        echo "</html>";
    }

    public function login_as(): void {
        $userId = $_POST['user_id'] ?? null;
        
        if ($userId) {
            $user = User::get_User_By_Id((int)$userId);
            if ($user) {

                $this->log_user($user, "", "index");
                return; 
            }
        }
        
       
        $this->redirect("", "index");
    }

    public function logout(): void {
        $_SESSION = array();
        session_destroy();
        $this->redirect("", "index");
    }
}