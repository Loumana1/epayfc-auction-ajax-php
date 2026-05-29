<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
require_once "model/Category.php";

class ControllerCategory extends Controller {

    private function require_admin(): object {
        $user = $this->get_user_or_redirect();
        if ($user->role !== 'admin') {
            $this->redirect('browser');
        }
        return $user;
    }

    private function show_manage(object $user, array $save_errors = [], array $add_errors = [], string $new_name = ''): void {
        (new View('manage_categories'))->show([
            'categories'   => Category::get_all_with_counts(),
            'currentUser'  => $user,
            'header_title' => 'Manage Categories',
            'page_css'     => ['manage_categories.css'],
            'page_js'      => ['manage_categories.js'],
            'save_errors'  => $save_errors,
            'add_errors'   => $add_errors,
            'new_name'     => $new_name,
        ]);
    }

    public function index(): void {
        $this->manage();
    }

    public function manage(): void {
        $user = $this->require_admin();
        $this->show_manage($user);
    }

    public function save(): void {
        $user = $this->require_admin();
        $id   = (int)($_GET['param1'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        if (!Category::get_by_id($id)) {
            $this->redirect('category', 'manage');
        }

        $errors = Category::validate_name($name);
        if (empty($errors) && Category::name_exists($name)) {
            $errors[] = "A category with this name already exists.";
        }

        if (empty($errors)) {
            Category::update_name($id, $name);
            $this->redirect('category', 'manage');
        }

        $this->show_manage($user, [$id => $errors]);
    }

    public function add(): void {
        $user = $this->require_admin();
        $name = trim($_POST['name'] ?? '');

        $errors = Category::validate_name($name);
        if (empty($errors) && Category::name_exists($name)) {
            $errors[] = "A category with this name already exists.";
        }

        if (empty($errors)) {
            Category::create($name);
            $this->redirect('category', 'manage');
        }

        $this->show_manage($user, [], $errors, $name);
    }

    public function move_up(): void {
        $this->require_admin();
        $id = (int)($_GET['param1'] ?? 0);
        Category::move_up($id);
        $this->redirect('category', 'manage');
    }

    public function move_down(): void {
        $this->require_admin();
        $id = (int)($_GET['param1'] ?? 0);
        Category::move_down($id);
        $this->redirect('category', 'manage');
    }

    public function delete_confirm(): void {
        $user = $this->require_admin();
        $id   = (int)($_GET['param1'] ?? 0);
        $cat  = Category::get_by_id($id);

        if (!$cat || Category::has_items($id)) {
            $this->redirect('category', 'manage');
        }

        (new View('delete_category_confirm'))->show([
            'category'     => $cat,
            'currentUser'  => $user,
            'header_title' => 'Delete Category',
            'page_css'     => ['manage_categories.css'],
        ]);
    }

    public function delete(): void {
        $this->require_admin();
        $id = (int)($_GET['param1'] ?? 0);
        if (!Category::has_items($id)) {
            Category::delete_by_id($id);
        }
        $this->redirect('category', 'manage');
    }

    // Services AJAX

    public function check_name_service(): void {
        $this->require_admin();
        header('Content-Type: application/json');
        $name   = trim($_POST['name'] ?? '');
        $errors = Category::validate_name($name);
        if (empty($errors) && Category::name_exists($name)) {
            $errors[] = "A category with this name already exists.";
        }
        echo json_encode(['valid' => empty($errors), 'errors' => $errors]);
    }

    public function update_name_service(): void {
        $this->require_admin();
        header('Content-Type: application/json');
        $id   = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        $errors = Category::validate_name($name);
        if (empty($errors) && Category::name_exists($name)) {
            $errors[] = "A category with this name already exists.";
        }
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }
        if (!Category::get_by_id($id)) {
            echo json_encode(['success' => false, 'errors' => ['Category not found.']]);
            return;
        }
        Category::update_name($id, $name);
        echo json_encode(['success' => true]);
    }

    public function delete_service(): void {
        $this->require_admin();
        header('Content-Type: application/json');
        $id = (int)($_POST['id'] ?? 0);
        if (Category::has_items($id)) {
            echo json_encode(['success' => false, 'error' => 'Category has items.']);
            return;
        }
        Category::delete_by_id($id);
        echo json_encode(['success' => true]);
    }

    public function update_priorities_service(): void {
        $this->require_admin();
        header('Content-Type: application/json');
        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids)) {
            echo json_encode(['success' => false]);
            return;
        }
        Category::update_priorities(array_map('intval', $ids));
        echo json_encode(['success' => true]);
    }

    public function add_service(): void {
        $this->require_admin();
        header('Content-Type: application/json');
        $name   = trim($_POST['name'] ?? '');
        $errors = Category::validate_name($name);
        if (empty($errors) && Category::name_exists($name)) {
            $errors[] = "A category with this name already exists.";
        }
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }
        $cat = Category::create($name);
        echo json_encode(['success' => true, 'id' => $cat->id, 'name' => $cat->name, 'priority' => $cat->priority]);
    }
}
