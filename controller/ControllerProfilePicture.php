<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
require_once 'framework/Configuration.php';
require_once 'utils/ImageProcessor.php';

class ControllerProfilePicture extends Controller {

    public function index(): void {

        $session_user = $this->get_user_or_redirect();
        $currentUser = User::get_User_By_Id($session_user->get_Id());
        if (!$currentUser) {
                $this->redirect('login');
                return;
            }
        
        (new View("profile_picture"))->show([
            "currentUser"  => $currentUser,
            "header_title" => "Manage profile picture",
            "header_icon"  => "bi-person-circle",
            "back_url"     => "profile",
            "page_css"     => ["profile_picture.css"],
        ]);

    }

    public function upload(): void {
        $session_user = $this->get_user_or_redirect();

        if (!isset($_FILES["picture"]) || $_FILES["picture"]["error"] !== UPLOAD_ERR_OK) {
            $this->redirect("profile_picture");
            return;
        }


        $tmp =$_FILES['picture']['tmp_name'];

        if (!is_uploaded_file($tmp)) {
            $this->redirect('profile_picture');
            return;
        } 


        $current_user = User::get_User_By_Id($session_user->get_Id());
        if (!$current_user) {
            $this->redirect('login');
            return;
        }


        try {
            // del ancienne photo
            if ($current_user->get_picture_path()) {
                ImageProcessor::delete_files($current_user->get_picture_path());
            }

            //ajout la nouvel
            $main_path = ImageProcessor::process_profile_upload($tmp, $current_user->get_Id());


            $current_user->set_picture_path($main_path);
            $current_user->save_picture();
        } catch (InvalidArgumentException $e) {
        
        }
       

        $this->redirect("profile_picture");
    }

    public function delete(): void {
        $session_user = $this->get_user_or_redirect();
        $user = User::get_User_By_Id($session_user->get_Id());

        if (!$user) {
            $this->redirect('login');
            return;
        }

        if ($user->get_picture_path()) {
            ImageProcessor::delete_files($user->get_picture_path());
            $user->set_picture_path(null);
            $user->save_picture();
        }

        $this->redirect("profile_picture");
    }

}