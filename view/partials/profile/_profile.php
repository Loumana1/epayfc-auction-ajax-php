<!-- User Info Card -->
        <div class="user-card">
            <?php if (!empty($currentUser->picture_path)): ?>
                <img src="<?= $web_root . $currentUser->picture_path ?>" alt="Profile" class="user-avatar">
            <?php else: ?>
                <div class="user-avatar-placeholder">👤</div>
            <?php endif; ?>
            <h2 class="user-name"><?= htmlspecialchars($currentUser->full_name) ?></h2>
            <p class="user-pseudo">@<?= htmlspecialchars($currentUser->pseudo) ?></p>
            <p class="user-email"><?= htmlspecialchars($currentUser->get_email()) ?></p>
        </div>

<!-- My Activities Section -->
<div class="section">
    <div class="section-header">My Activities</div>

    <a href="sales/index" class="menu-item">
        <div class="menu-icon">📄</div>
        <div class="menu-content">
            <div class="menu-title">Sales</div>
            <div class="menu-desc">Items you have sold</div>
        </div>
    </a>

    <a href="purchases/index" class="menu-item">
        <div class="menu-icon">🛍️</div>
        <div class="menu-content">
            <div class="menu-title">Purchases</div>
            <div class="menu-desc">Items you have purchased</div>
        </div>
    </a>
</div>

<!-- Account Settings Section -->
<div class="section">
    <div class="section-header">Account Settings</div>

    <a href="edit_profile/index" class="menu-item">
        <div class="menu-icon">👤</div>
        <div class="menu-content">
            <div class="menu-title">Edit Profile</div>
            <div class="menu-desc">Update your personal information</div>
        </div>
    </a>

    <a href="user/change_password" class="menu-item">
        <div class="menu-icon">•••</div>
        <div class="menu-content">
            <div class="menu-title">Change Password</div>
            <div class="menu-desc">Update your account security</div>
        </div>
    </a>

    <a href="profile_picture/index" class="menu-item">
        <div class="menu-icon">📷</div>
        <div class="menu-content">
            <div class="menu-title">Profile Picture</div>
            <div class="menu-desc">Upload or change your profile picture</div>
        </div>
    </a>

    <a href="profile/logout" class="menu-item logout">
        <div class="menu-icon">🚪</div>
        <div class="menu-content">
            <div class="menu-title">Logout</div>
            <div class="menu-desc">Sign out of your account</div>
        </div>
    </a>
</div>