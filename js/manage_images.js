let itemId = null;
let baseUrl = '';
let toDelete = null;
let toDeleteEl = null;

document.addEventListener('DOMContentLoaded', () => {
    const managePage = document.querySelector('.manage-page');
    itemId = managePage ? managePage.dataset.itemId : null;
    const base = document.querySelector('base');
    baseUrl = base ? base.getAttribute('href') : '';

    updateArrows();

    /* =========================
       MOVE LEFT / RIGHT
    ========================= */
    document.querySelectorAll('.btn-left, .btn-right').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            const isLeft = btn.classList.contains('btn-left');

            const current = btn.closest('.img-box');
            const container = document.getElementById('sortable-images');

            const target = isLeft
                ? current.previousElementSibling
                : current.nextElementSibling;

            if (!target) return;

            const oldPriority = btn.dataset.priority;

            if (isLeft) {
                container.insertBefore(current, target);
            } else {
                container.insertBefore(target, current);
            }

            updatePriorities();

            try {
                await fetch(baseUrl + `manage_images/${isLeft ? 'move_left' : 'move_right'}/${itemId}/${oldPriority}`, {
                    method: 'POST'
                });
            } catch (e) {
                console.error(e);
            }

            updateArrows();
        });
    });

    /* =========================
       DELETE (MODAL)
    ========================= */
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            toDelete = btn.dataset.priority;
            toDeleteEl = btn.closest('.img-box');

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });

    /* =========================
       CONFIRM DELETE
    ========================= */
    const confirmBtn = document.getElementById('confirmDelete');

    if (confirmBtn) {
        confirmBtn.addEventListener('click', async () => {

            if (!toDelete) return;

            try {
                await fetch(baseUrl + `manage_images/delete/${itemId}/${toDelete}`, {
                    method: 'POST'
                });
            } catch (e) {
                console.error(e);
            }

            if (toDeleteEl) toDeleteEl.remove();

            updatePriorities();
            updateArrows();

            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            if (modal) modal.hide();

            toDelete = null;
            toDeleteEl = null;
        });
    }
});

/* =========================
   UPDATE PRIORITIES
========================= */
function updatePriorities() {
    document.querySelectorAll('.img-box').forEach((el, index) => {
        const newPriority = index + 1;

        el.dataset.priority = newPriority;

        el.querySelectorAll('button').forEach(btn => {
            btn.dataset.priority = newPriority;
        });
    });
}

/* =========================
   UPDATE ARROWS
========================= */
function updateArrows() {
    const items = document.querySelectorAll('.img-box');

    items.forEach((item, index) => {
        const leftBtn = item.querySelector('.btn-left');
        const rightBtn = item.querySelector('.btn-right');

        // Gérer la flèche gauche
        if (leftBtn) {
            if (index === 0) {
                leftBtn.disabled = true;
                leftBtn.classList.add('disabled');
            } else {
                leftBtn.disabled = false;
                leftBtn.classList.remove('disabled');
            }
        }

        // Gérer la flèche droite
        if (rightBtn) {
            if (index === items.length - 1) {
                rightBtn.disabled = true;
                rightBtn.classList.add('disabled');
            } else {
                rightBtn.disabled = false;
                rightBtn.classList.remove('disabled');
            }
        }
    }); 
}
// Drag && drop 
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
                    updatePriorities();
                    updateArrows();
                },
                error: function (xhr) {
                    alert("Failed to update order.");
                    $grid.sortable("cancel");
                }
            });
        } 
    });
    $grid.disableSelection();
})