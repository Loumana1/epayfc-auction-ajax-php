<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";

class ControllerItem extends Controller {

    public function index(): void {
        $this->redirect("item", "add_edit_item");
    }

    public function add_edit_item(): void {
        $user = $this->get_user_or_false();
        $owner_id = $user ? $user->get_id() : 1;

        $item_id = isset($_GET["param1"]) ? (int)$_GET["param1"] : null;
        $item = null;

        if ($item_id !== null) {
            $item = Item::get_by_id_for_edit($item_id);
            if ($item === null || $item->get_owner() !== $owner_id) {
                $this->redirect();
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = $_POST["title"] ?? "";
            $description = $_POST["description"] ?? null;
            $duration_days = (int)($_POST["duration_days"] ?? 7);

            $starting_bid_raw = trim($_POST["starting_bid"] ?? "");
            $buy_now_raw = trim($_POST["buy_now_price"] ?? "");
            $sale_price_raw = trim($_POST["sale_price"] ?? "");

            $is_direct_sale = ($sale_price_raw !== "");

            $starting_bid = null;
            $buy_now_price = null;

            if ($is_direct_sale) {
                $starting_bid = 0.0;
                $buy_now_price = $sale_price_raw !== "" ? (float)$sale_price_raw : null;
            } else {
                $starting_bid = $starting_bid_raw !== "" ? (float)$starting_bid_raw : null;
                $buy_now_price = $buy_now_raw !== "" ? (float)$buy_now_raw : null;
            }

            $created_at = $item === null ? date("Y-m-d H:i:s") : $item->get_created_at();

            $new_item = new Item(
                $item_id,
                $title,
                $description,
                $owner_id,
                $created_at,
                $buy_now_price,
                $duration_days,
                $starting_bid
            );

            $errors = $new_item->persist();

            if (!empty($errors)) {
                (new View("add_edit_item"))->show([
                    "item_id" => $item_id,
                    "title" => $title,
                    "description" => $description ?? "",
                    "duration_days" => $duration_days,
                    "starting_bid" => $starting_bid_raw,
                    "buy_now_price" => $buy_now_raw,
                    "sale_price" => $sale_price_raw,
                    "errors" => $errors
                ]);
                return;
            }

            $this->redirect("item", "open_item", (string)$new_item->get_id());
        }

        (new View("add_edit_item"))->show([
            "item_id" => $item_id,
            "title" => $item ? $item->get_title() : "",
            "description" => $item ? ($item->get_description() ?? "") : "",
            "duration_days" => $item ? $item->get_duration_days() : 7,
            "starting_bid" => $item ? (string)($item->get_starting_bid() ?? "") : "",
            "buy_now_price" => $item ? (string)($item->get_buy_now_price() ?? "") : "",
            "sale_price" => $item && (($item->get_starting_bid() ?? 0) <= 0) ? (string)($item->get_buy_now_price() ?? "") : "",
            "errors" => []
        ]);
    }
}
