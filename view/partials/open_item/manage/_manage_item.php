<?php
if (!$isOwner) return;
?>

<div class="owner-section">
    <h4 class="owner-section-title">Manage Your Item</h4>
    <div class="owner-actions">
        
        <!-- Edit -->
        <?php if ($hasActiveBids || $itemPurchased): ?>
            <span class="btn-manage-item btn-disabled">Edit Item Details</span>
        <?php else: ?>
            <a href="add_edit_item?param1=<?= $item->get_Id() ?>" class="btn-manage-item">Edit Item Details</a>
        <?php endif; ?>
        
        <!-- Images -->
        <?php if ($itemPurchased): ?>
            <span class="btn-manage-item btn-disabled">Manage Images</span>
        <?php else: ?>
            <a href="manage_images?param1=<?= $item->get_Id() ?>" class="btn-manage-item">Manage Images</a>
        <?php endif; ?>
        
        <!-- Delete -->
        <?php if ($itemPurchased): ?>
            <span class="btn-delete-item btn-disabled">Delete Item</span>
        <?php else: ?>
            <a href="delete_confirm/index/<?= $item->get_Id() ?>" class="btn-delete-item">Delete Item</a>
        <?php endif; ?>
        
    </div>
</div>