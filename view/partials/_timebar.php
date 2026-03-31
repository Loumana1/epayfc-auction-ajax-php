<?php
require_once "utils/AppTime.php";
?>
<nav class="navBar navBar-time">
    <div class="time-display">
        <i class="bi bi-clock"></i>
        <span class="time-text"><?= date('d/m/y H:i', AppTime::get_current_timestamp()) ?></span>
    </div>
    <div class="time-controls">


    <a href="setup" class="time-btn time-btn-setup" >
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

        <form method="post" action="time/reset" style="display: inline;">
            <button type="submit" class="time-btn time-btn-reset">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </button>
        </form>
    </div>
</nav>