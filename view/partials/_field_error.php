<?php
if (empty($fieldErrorsToShow)) return;
?>

<?php foreach ($fieldErrorsToShow as $error): ?>
    <span class="field-error">
        <?= htmlspecialchars($error) ?>
    </span>
<?php endforeach; ?>
