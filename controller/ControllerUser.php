<?php

require_once "framework/Controller.php";
require_once 'framework/View.php';


class ControllerUser extends Controller {

    public function index(): void {
        if (!Configuration::is_dev()) {
            $this->redirect();
        }

        (new View(""))->show();
    }

    public function login() : void {
        (new View("login")) -> show();
    }

    
}