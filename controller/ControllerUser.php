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
        $fieldErrors = [
            'current_password' => [],
            'new_password' => [],
            'confirm_password' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? null;
            $newPassword = $_POST['new_password'] ?? null;
            $confirmPassword = $_POST['confirm_password'] ?? null;

            $fieldErrors = User::validate_change_password($user, $currentPassword, $newPassword, $confirmPassword);

            if (empty(array_filter($fieldErrors))) {
                User::update_password($user->get_Id(), password_hash($newPassword, PASSWORD_DEFAULT));
                $this->redirect('profile');
                return;
            }
        }

        $this->change_password_show_view($user, $fieldErrors);
    }

    private function change_password_show_view(object $user, array $fieldErrors): void {
        (new View("change_password"))->show([
            'header_title' => 'Change password',
            'header_icon' => 'bi-cart-fill',
            'back_url' => 'profile',
            'header_right_icon' => 'bi-floppy',
            'header_right_text' => 'Save',
            'header_right_form_id' => 'change-password-form',
            'fieldErrors' => $fieldErrors,
            'currentUser' => $user
        ]);
    }
    
}