<?php

function format_euro(?float $amount): string {
    return '€ ' . number_format((float) $amount, 2, ',', '.');
}
