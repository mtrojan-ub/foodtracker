<?php

namespace Foodtracker;

class ViewHelper {

    static public function ForceLogin(): array {
        if (self::IsLoggedIn())
            return self::GetUser();

        require('../tpl/sub/login.php');
        die();
    }

    static public function GetCurrentPage(): string {
        return $_GET['page'] ?? 'home';
    }

    static public function GetFoodSelect(): string {
        $foodSelect = '<select name="id_food">';
        $foods = DB::GetFoods();
        foreach ($foods as $food)
            $foodSelect .= '<option value="' . htmlspecialchars($food['id']) . '">' . htmlspecialchars($food['name']) . '</option>';

        $foodSelect .= '</select>';
        return $foodSelect;
    }

    static public function GetLoginResultMessage(): string {
        return $_SESSION['login_result_message'] ?? '';
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

    static public function Logout() {
        unset($_SESSION['user']);
    }

    static public function ProcessLogin() {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $_SESSION['login_result_message'] = '';

        if ($username != '' && $password != '') {
            $user = DB::GetUserByLogin($username, $password);
            if ($user !== null) {
                ViewHelper::SetUser($user);
                return;
            }
            else
                $_SESSION['login_result_message'] = 'Wrong username or password, try again!';
        }
    }

    static public function ProcessLogout() {
        if (isset($_GET['logout']))
            self::Logout();
    }

    static public function SetUser(array $user) {
        $_SESSION['user'] = $user;
    }
}
