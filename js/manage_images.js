$(function () {
    const $grid = $("#sortable-images");
    if (!$grid.length) return;

    const itemId = $grid.data("item-id");

    $grid.sortable({
        items: ".img-box",
        cursor: "grabbing",
        placeholder: "sortable-placeholder", 
        tolerance: "pointer",
        update: function () {
            const order = [];
            $grid.find(".img-box").each(function() {
                order.push($(this).data("path"));
            });

            $.ajax({
                url: $("base").attr("href") + "manage_images/update_order",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({ item_id: itemId, order: order }),
                success: function () {
                    console.log("Order updated");
                    location.reload();
                },
                error: function (xhr) {
                    alert("Failed to update order.");
                    location.reload();
                }
            });
        } 
    });
    $grid.disableSelection();
})