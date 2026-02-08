<?php 

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';


class ControllerUser extends Controller {

    public function index() : void {
        $this->profile();
    }

    public function profile() : void {
        $user = $this->get_user_or_redirect();
    
    }

    public function change_password(): void {
        $user = $this->get_user_or_redirect();
        $field_errors = [
            'current_password' => [],
            'new_password' => [],
            'confirm_password' => []
        ];

        if (isset($_POST['current_password'])) {
            $current_password = $_POST['current_password'] ?? null;
            $new_password = $_POST['new_password'] ?? null;
            $confirm_password = $_POST['confirm_password'] ?? null;

           $field_errors = $user->validate_new_password($current_password, $new_password, $confirm_password);

            if (empty(array_filter($field_errors))) {
               $user->set_password($new_password);
                $this->redirect('profile');
                return;
            }
        }

        $this->change_password_show_view($user, $field_errors);
    }

    private function change_password_show_view(object $user, array $field_errors): void {
        (new View("change_password"))->show([
            'header_title' => 'Change password',
            'header_icon' => 'bi-cart-fill',
            'back_url' => 'profile',
            'header_right_icon' => 'bi-floppy',
            'header_right_text' => 'Save',
            'header_right_form_id' => 'change-password-form',
            'field_errors_current_password' => $field_errors['current_password'] ?? [],
            'field_errors_new_password'     => $field_errors['new_password'] ?? [],
            'field_errors_confirm_password' => $field_errors['confirm_password'] ?? [],
            'current_user' => $user,
            'page_css' => ['change_password.css']
        ]);
    }
    
}