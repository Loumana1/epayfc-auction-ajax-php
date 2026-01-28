<nav class="navBar navBar-principal">
    <a href="browser">
        <i class="bi bi-search"></i>
        <span>Browse</span>
    </a>
    
    <?php if ($currentUser): ?>
        <a href="my_items">
            <i class="bi bi-house"></i>
            <span>My Items</span>
        </a>
        <a href="add_edit_item">
            <i class="bi bi-plus-circle"></i>
            <span>Add Offer</span>
        </a>
        <a href="profile">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    <?php else: ?>
        <a href="login">
            <i class="bi bi-person-plus"></i>
            <span>Join Us</span>
        </a>
    <?php endif; ?>
</nav>