<?php
require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'utils/AppTime.php';
require_once 'model/Item.php';
require_once 'model/ItemPicture.php';
require_once 'model/User.php';

class ControllerManageImages extends Controller
{
    public function index(): void
    {
        $current_user = $this->get_user_or_false();
        if (!$current_user) {
            $this->redirect("login");
            return;
        }

        $item_id = $_GET['param1'] ?? null;
        if (!$item_id) {
            $this->redirect("profile");
            return;
        }

        $item = Item::get_by_id((int) $item_id);
        if (!$item || $item->get_seller()->get_Id() !== $current_user->get_Id()) {
            $this->redirect("profile");
            return;
        }

        $pictures = ItemPicture::get_all_by_item((int) $item_id);
        (new View("manage_images"))->show([
            'item' => $item,
            'pictures' => $pictures,
            'picture_count' => count($pictures),
            'current_user' => $current_user,
            'header_title' => 'Manage Images',
            'header_icon' => 'bi-images'
        ]);
    }

    public function upload(): void
    {
        $current_user = $this->get_user_or_false();
        if (!$current_user){
            $this->redirect("login");
            return;
        }

        $item_id =$_GET['param1'] ?? $_POST['item_id'] ?? null;
        if (!$item_id)
            return;

        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = 'uploads/items/'. $item_id . '/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $extension = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $new_file_name = bin2hex(random_bytes(8)) . '_' . AppTime::get_current_timestamp() . '.' . $extension;
                $file_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($tmp_name, $file_path)) {
                    ItemPicture::add((int) $item_id, $file_path);
                }
            }
        }
        $this->redirect("manage_images", "index", $item_id);
    }

    public function delete(): void
    {
        $current_user = $this->get_user_or_false();
        $item_id = $_GET['param1'] ?? null;
        $priority = $_GET['param2'] ?? null;

        if ($current_user && $item_id && $priority !== null) {
            ItemPicture::delete((int) $item_id, (int) $priority);
        }
        $this->redirect("manage_images", "index", $item_id ?? '');
    }

    public function move_left(): void
    {
        $current_user = $this->get_user_or_false();
        $item_id = $_GET['param1'] ?? null;
        $priority = $_GET['param2'] ?? null;

        if ($current_user && $item_id && $priority !== null) {
            ItemPicture::move_left((int) $item_id, (int) $priority);
        }
        $this->redirect("manage_images", "index", $item_id ?? '');
    }

    public function move_right(): void
    {
        $current_user = $this->get_user_or_false();
        $item_id = $_GET['param1'] ?? null;
        $priority = $_GET['param2'] ?? null;

        if ($current_user && $item_id && $priority !== null) {
            ItemPicture::move_right((int) $item_id, (int) $priority);
        }
        $this->redirect("manage_images", "index", $item_id ?? '');
    }



   public function move_left_ajax(): void {
    $current_user = $this->get_user_or_false();
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$current_user || !$data) return;

    $item_id = (int)$data['item_id'];
    $priority = (int)$data['priority'];

    $item = Item::get_by_id($item_id);
    if (!$item || $item->get_seller()->get_Id() !== $current_user->get_Id()) return;

    ItemPicture::move_left($item_id, $priority);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
}


   public function move_right_ajax(): void {
        $current_user = $this->get_user_or_false();
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$current_user || !$data) return;

        $item_id = (int)$data['item_id'];
        $priority = (int)$data['priority'];

        $item = Item::get_by_id($item_id);
        if (!$item || $item->get_seller()->get_Id() !== $current_user->get_Id()) return;

        ItemPicture::move_right($item_id, $priority);

        header('Content-Type: application/json');
        echo json_encode(["success" => true]);
    }


    public function delete_ajax(): void {
        $current_user = $this->get_user_or_false();
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$current_user || !$data) return;

        $item_id = (int)$data['item_id'];
        $priority = (int)$data['priority'];

        $item = Item::get_by_id($item_id);
        if (!$item || $item->get_seller()->get_Id() !== $current_user->get_Id()) return;

        ItemPicture::delete($item_id, $priority);

        header('Content-Type: application/json');
        echo json_encode(["success" => true]);
    }
}