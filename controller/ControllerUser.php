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

}