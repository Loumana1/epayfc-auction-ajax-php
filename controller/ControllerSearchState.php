<?php

require_once "framework/Controller.php";

class ControllerSearchState extends Controller
{
    public function index(): void
    {
        http_response_code(404);
    }

    public function encode(): void
    {
        $from = $_POST['from'] ?? 'browser';
        $q = trim($_POST['q'] ?? '');

        if ($from !== 'browser' && $from !== 'my_items') {
            $from = 'browser';
        }

        $search_state = Tools::url_safe_encode([
            'from' => $from,
            'q' => $q
        ]);

        header('Content-Type: application/json');
        echo json_encode(['search_state' => $search_state]);
    }
}