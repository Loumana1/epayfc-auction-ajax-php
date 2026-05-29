<nav class="navBar navBar-principal">
    <a href="browser">
        <i class="bi bi-search"></i>
        <span>Browse</span>
    </a>
    
    <?php if ($current_user ?? $currentUser ?? null): ?>
        <a href="my_items">
            <i class="bi bi-house"></i>
            <span>My Items</span>
        </a>
        <a href="item/add_edit_item">
            <i class="bi bi-plus-circle"></i>
            <span>Add Offer</span>
        </a>
        <?php $__nav_user = $current_user ?? $currentUser ?? null; ?>
        <?php if ($__nav_user && is_object($__nav_user) && $__nav_user->role === 'admin'): ?>
        <a href="category/manage">
            <i class="bi bi-tags"></i>
            <span>Categories</span>
        </a>
        <?php endif; ?>
        <a href="profile">
            <i class="bi bi-gear"></i>
            <span>Profile</span>
        </a>
    <?php else: ?>
        <a href="login">
            <i class="bi bi-person-plus"></i>
            <span>Join Us</span>
        </a>
    <?php endif; ?>
</nav>