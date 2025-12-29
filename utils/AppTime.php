<?php

//on suppose que la session est déjà démarrée

class AppTime {
    public static function get_offset(): int {
        return $_SESSION['time_offset'] ?? 0;
    }

    public static function set_offset(int $offset): void {
        $_SESSION['time_offset'] = $offset;
    }

    private static function to_seconds(int $amount, string $unit): int {
        switch($unit) {
            case 'hour':
                return $amount * 3600;
            case 'day':
                return $amount * 86400;
            case 'week':
                return $amount * 604800;
                //toutes les itesms sont deja passé dans le temps 
                //je veux pouvoir returner dans le temps 
                //pose quel quelque prblèmes :
                // - que se passe t'il pour les bids déjà fait ?
                // - que ce passe t'il pour les elements vendu ? 
                // - les annonces active ? 
                // - pour, notPurchased, isOpen, hasBids,  Je ne verifie la jamais la date 
                // - je vois qu'a utilisé dDatetime
//convertir le moi en seconde 
                case 'month':
                    return $amount * 2592000;
            default:
                return 0;
        }
    }

    public static function add_period(int $amount, string $unit): void {
        $seconds = self::to_seconds($amount, $unit);
        self::set_offset(self::get_offset() + $seconds);
    }

    public static function reset_offset(): void {
        self::set_offset(0);
    }

    public static function get_current_datetime(): string {
        $dt = new DateTime();
        //Pouvoir prendre des chiffre negatifs -> retourner en arriere avec month
        $offset = self::get_offset();
        $dt->modify($offset . " seconds");
        return $dt->format("Y-m-d H:i:s");
    }
}