<?php
if (!$is_owner) return;
?>

<div class="owner-section">
    <h4 class="owner-section-title">Manage Your Item</h4>
    <div class="owner-actions">
        
        <!-- Edit -->
        <?php if ($has_active_bids ||!$is_open): ?>
            <span class="btn-manage-item btn-disabled">
            <i class="bi bi-pencil"></i> Edit Item Details</span>
        <?php else: ?>
            <a href="add_edit_item?param1=<?= $item->get_Id() ?>" class="btn-manage-item">
            <i class="bi bi-pencil"></i> Edit Item Details
            </a>
        <?php endif; ?>
        
        <!-- Images -->
        <?php if (!$is_open): ?>
            <span class="btn-manage-item btn-disabled">
            <i class="bi bi-images"></i> Manage Images

            </span>
        <?php else: ?>
            <a href="manage_images?param1=<?= $item->get_Id() ?>" class="btn-manage-item">
            <i class="bi bi-images"></i> Manage Images

            </a>
        <?php endif; ?>
        
        <!-- Delete -->
        <?php if (!$is_open): ?>
            <span class="btn-delete-item btn-disabled">
            <i class="bi bi-trash"></i> Delete Item


            </span>
        <?php else: ?>
            <a href="delete_confirm/index/<?= $item->get_Id() ?>" class="btn-delete-item">
            <i class="bi bi-trash"></i> Delete Item
        </a>
        <?php endif; ?>
        
    </div>
</div>