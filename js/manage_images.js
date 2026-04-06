const itemId = document.body.dataset.itemId;

let toDelete = null;
let toDeleteEl = null;

/* =========================
   INIT
========================= */
document.addEventListener('DOMContentLoaded', () => {
    updateArrows();
});

/* =========================
   MOVE LEFT / RIGHT
========================= */
document.querySelectorAll('.btn-left, .btn-right').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();

        const isLeft = btn.classList.contains('btn-left');

        const current = btn.closest('.image-item');
        const container = document.getElementById('images-container');

        const target = isLeft
            ? current.previousElementSibling
            : current.nextElementSibling;

        if (!target) return;

        const oldPriority = current.dataset.priority;

        if (isLeft) {
            container.insertBefore(current, target);
        } else {
            container.insertBefore(target, current);
        }

        updatePriorities();

        try {
            await fetch(`manage_images/${isLeft ? 'move_left_ajax' : 'move_right_ajax'}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    item_id: itemId,
                    priority: oldPriority
                })
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
        toDeleteEl = btn.closest('.image-item');

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
            await fetch('manage_images/delete_ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    item_id: itemId,
                    priority: toDelete
                })
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

/* =========================
   UPDATE PRIORITIES
========================= */
function updatePriorities() {
    document.querySelectorAll('.image-item').forEach((el, index) => {
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
    const items = document.querySelectorAll('.image-item');

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