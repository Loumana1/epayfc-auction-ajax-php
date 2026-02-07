<main class="error-container">
    <div class="error-icon-wrapper">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
    <h1 class="error-oops">ERROR!</h1>
    <p class="error-tip-text">Something went wrong</p>
    <div class="error-box">
        <div class="error-details-box">
            <?= $error ?? '' ?>
        </div>
    </div>
</main>
