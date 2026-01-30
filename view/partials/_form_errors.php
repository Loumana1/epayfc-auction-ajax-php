<?php
if (empty($errors)) return;
?>

<div class="bid-errors">
    <div class="bid-error-icon">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
    <div class="bid-error-content">
        <?php foreach ($errors as $error): ?>
            <p class="bid-error-message"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
</div>
