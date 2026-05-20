<?php
require_once 'Model/User.php';
require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'framework/Model.php';

class ControllerEditProfile extends Controller
{
    public function index(): void
    {
        $current_user = $this->get_user_or_false();
        if (!$current_user) {
            $this->redirect("profile");
            return;
        }

        $current_user_id = $current_user->get_Id();
        $db_user = User::get_User_By_Id($current_user_id);

        (new View("edit_profile"))->show([
            'current_user_id' => $current_user_id,
            'currentUser' => $current_user,
            'user' => [
                'full_name' => $db_user->full_name,
                'pseudo' => $db_user->pseudo,
                'email' => $db_user->get_email(),
                'iban' => $db_user->iban ?? ''
            ],
            'header_title' => 'Edit Profile',
            'header_icon' => 'bi-person-fill',
            'back_url' => 'profile',
            'header_right_icon' => 'bi-floppy',
            'header_right_form_id' => 'edit-form',
            'errors' => [    
                'full_name' => '',
                'pseudo' => '',
                'email' => '',
                'iban' => '',
            ],
            'success' => false,
            'page_css' => ['edit_Profile.css'],
            'page_js' => ['user_validation.js'],
        ]);
    }

    public function save(): void
    {
        $current_user = $this->get_user_or_false();
        if (!$current_user) {
            $this->redirect("profile");
            return;
        }

        $user_id = $current_user->get_Id();
        $full_name = $_POST['full_name'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $email = $_POST['email'] ?? '';
        $iban = $_POST['iban'] ?? '';

        $errors = [    'full_name' => '',
        'pseudo' => '',
        'email' => '',
        'iban' => '',];

        if (empty(trim($full_name))) {
            $errors['full_name'] = "Full name is required";
        }
        if (empty(trim($pseudo))) {
            $errors['pseudo'] = "Username is required";
        }
        if (empty(trim($email))) {
            $errors['email'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }
         if (!empty(trim($full_name)) && User::is_full_name_taken(trim($full_name), $user_id)) {
            $errors['full_name'] = "Full name is already taken by another user.";
        }
        if (!empty(trim($pseudo)) && User::is_pseudo_taken(trim($pseudo), $user_id)) {
            $errors['pseudo'] = "Username is already taken by another user.";
        }
       
        if (!empty(trim($email)) && User::is_email_taken(trim($email), $user_id)) {
            $errors['email'] = "Email is already used by another user.";
        }
        if (empty(array_filter($errors))) {
            User::update_user($user_id, $full_name, $pseudo, $email, $iban);
            $this->redirect("profile");
            return;
        }

        (new View("edit_profile"))->show([
            'current_user_id' => $user_id,
            'currentUser' => $current_user,
            'current_user' => $current_user,
            'user' => [
                'id' => $user_id,
                'full_name' => $full_name,
                'pseudo' => $pseudo,
                'email' => $email,
                'iban' => $iban
            ],
            'errors' => $errors,
            'success' => false,
            'header_title' => 'Edit Profile',
            'header_icon' => 'bi-person-fill',
            'back_url' => 'profile',
            'header_right_icon' => 'bi-floppy',
            'header_right_form_id' => 'edit-form',
            'page_css' => ['edit_Profile.css'],
            'page_js' => ['user_validation.js'],
        ]);
    }

    public function check_availability_service(): void {
        $user = $this->get_user_or_redirect();
        header('Content-Type: application/json');
        
        $email = trim($_POST['email'] ?? '');
        $pseudo = trim($_POST['pseudo'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');

        echo json_encode([
            'email_available' => $email === '' || !User::is_email_taken($email, $user->id),
            'pseudo_available' => $pseudo === '' || !User::is_pseudo_taken($pseudo, $user->id),
            'full_name_available' => $full_name === '' || !User::is_full_name_taken($full_name, $user->id)
        ]);
    }
}

