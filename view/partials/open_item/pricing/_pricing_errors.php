<?php
if (empty($bidErrors)) return;
?>

<div class="bid-errors">
    <div class="bid-error-icon">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
    <div class="bid-error-content">
        <?php foreach ($bidErrors as $error): ?>
            <p class="bid-error-message"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
</div>