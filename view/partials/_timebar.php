<nav class="navBar navBar-time">
    <div class="time-display">
        <i class="bi bi-clock"></i>
        <span class="time-text"><?= date('d/m/y H:i', strtotime(AppTime::get_current_datetime())) ?></span>
    </div>
    <div class="time-controls">

    <a href="<?= $web_root?>" class="time-btn time-btn-home">
            <i class="bi bi-house-fill"></i> Home
        </a>
    <a href="setup" class="time-btn time-btn-setup" onclick="return confirm('Restaurer les données originales ? Toutes les modifications seront perdues.');">
            <i class="bi bi-database-fill-gear"></i> Setup
        </a>
        <form method="post" action="time/advance" style="display: inline;">
            <input type="hidden" name="amount" value="1">
            <input type="hidden" name="unit" value="hour">
            <button type="submit" class="time-btn">+1h</button>
        </form>
        <form method="post" action="time/advance" style="display: inline;">
            <input type="hidden" name="amount" value="1">
            <input type="hidden" name="unit" value="day">
            <button type="submit" class="time-btn">+1day</button>
        </form>
        <form method="post" action="time/advance" style="display: inline;">
            <input type="hidden" name="amount" value="1">
            <input type="hidden" name="unit" value="week">
            <button type="submit" class="time-btn">+1week</button>
        </form>
        <form method="post" action="time/advance" style="display: inline;">
            <input type="hidden" name="amount" value="1">
            <input type="hidden" name="unit" value="month">
            <button type="submit" class="time-btn">+1month</button>
        </form>
        <form method="post" action="time/advance" style="display: inline;">
            <input type="hidden" name="amount" value="-1">
            <input type="hidden" name="unit" value="month">
            <button type="submit" class="time-btn">-1month</button>
        </form>
        <form method="post" action="time/reset" style="display: inline;">
            <button type="submit" class="time-btn time-btn-reset">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </button>
        </form>
    </div>
</nav>