<?php

namespace Foodtracker;

class ViewHelper {

    static public function ForceLogin(): array {
        if (self::IsLoggedIn())
            return self::GetUser();

        require('../tpl/sub/login.php');

        if (self::IsLoggedIn())
            return self::GetUser();

        die();
    }

    static public function GetCurrentPage(): string {
        return $_GET['page'] ?? 'home';
    }

    static public function GetFoodSelect(): string {
        $foodSelect = '<select name="food">';
        $foods = DB::GetFoods();
        foreach ($foods as $food)
            $foodSelect .= '<option value="' . htmlspecialchars($food['id']) . '">' . htmlspecialchars($food['name']) . '</option>';

        $foodSelect .= '</select>';
        return $foodSelect;
    }

    static public function GetNutrientCaption($nutrient): string {
        $caption = '';
        $caption .= ($nutrient['external_id_netdoktor'] != '') ? '<a href="//www.netdoktor.de/'.$nutrient['external_id_netdoktor'].'" target="_blank">' : '';
        $caption .= $nutrient['name'];
        $caption .= ($nutrient['external_id_netdoktor'] != '') ? '</a>' : '';
        return $caption;
    }

    static public function GetUser(): array {
        return $_SESSION['user'];
    }

    static public function IsLoggedIn(): bool {
        return isset($_SESSION['user']);
    }

    static public function SetUser(array $user) {
        $_SESSION['user'] = $user;
    }
}
