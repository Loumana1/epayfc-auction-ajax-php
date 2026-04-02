<?php

require_once "framework/Controller.php";
require_once "framework/Configuration.php";

class ControllerConfig extends Controller
{
    /**
     * Service AJAX : retourne les constantes de validation au format JSON.
     * Utilisé par les scripts JS pour ne pas hardcoder les limites côté client.
     */


    public function index(): void {}

    public function validation_service(): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            'title_min_length'       => (int) Configuration::get('title_min_length'),
            'title_max_length'       => (int) Configuration::get('title_max_length'),
            'description_min_length' => (int) Configuration::get('description_min_length'),
            'duration_min_days'      => (int) Configuration::get('duration_min_days'),
            'duration_max_days'      => (int) Configuration::get('duration_max_days'),
            'password_min_length'    => (int) Configuration::get('password_min_length'),
            'password_max_length'    => (int) Configuration::get('password_max_length'),
        ]);
    }
}
