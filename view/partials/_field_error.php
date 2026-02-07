<?php
if (empty($field_errors_to_show)) return;
?>

<?php foreach ($field_errors_to_show as $error): ?>
    <span class="field-error">
        <?= htmlspecialchars($error) ?>
    </span>
<?php endforeach; ?>
