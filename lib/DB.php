<?php

namespace Foodtracker;

class DB {
    static protected function GetConnection() {
        if (!isset($GLOBALS['mysqli']))
            $GLOBALS['mysqli'] = new \mysqli('localhost', 'root', '', 'foodtracker');
        return $GLOBALS['mysqli'];
    }

    static protected function Query($query): \mysqli_result {
        return self::GetConnection()->query($query);
    }

    static protected function Escape($param) {
        return self::GetConnection()->real_escape_string($param);
    }

    static protected function Select($query): array {
        if ($result = self::Query($query)) {
            $return = [];
            while($row = $result->fetch_assoc()) {
                $return[] = $row;
            }
            return $return;
        }
    }

    static public function GetFood($id): array {
        return self::Select('SELECT * FROM foods WHERE id=' . self::Escape($id))[0];
    }

    static public function GetFoods(): array {
        return self::Select('SELECT * FROM foods ORDER BY name ASC');
    }

    static public function GetNutrients(): array {
        return self::Select('SELECT * FROM nutrients ORDER BY name ASC');
    }

    static public function GetFoodNutrients($foodId): array {
        $query = 'SELECT * FROM foods '
               . 'LEFT JOIN food_nutrients ON foods.id = food_nutrients.id_food '
               . 'LEFT JOIN nutrients ON nutrients.id = food_nutrients.id_nutrient '
               . 'WHERE foods.id=' . self::Escape($foodId) . ' '
               . 'ORDER BY nutrients.name ASC';
        return self::Select($query);
    }

    static public function GetProfiles(): array {
        return self::Select('SELECT * FROM profiles');
    }

    static public function GetProtocolForUser($userId): array {
        return self::Select('SELECT *, foods.kcal * protocols.amount / 100 AS real_kcal FROM protocols ' .
                            'LEFT JOIN foods ON foods.id=protocols.id_food ' .
                            'WHERE id_user=' . self::Escape($userId) . ' ' .
                            'ORDER BY protocols.date ASC, protocols.time ASC');
    }

    static public function GetProtocolCaloriesForUser($userId): int {
        $rows = self::Select('SELECT *, SUM(foods.kcal * protocols.amount / 100) AS real_kcal FROM protocols ' .
                             'LEFT JOIN foods ON protocols.id_food = foods.id ' .
                             'WHERE protocols.id_user=' . self::Escape($userId));

        return $rows[0]['real_kcal'] ?? 0;
    }

    static public function GetProtocolNutrientsForUser($userId): array {
        $user = self::GetUser($userId);
        return self::Select('SELECT *, SUM(ROUND(food_nutrients.amount *(protocols.amount / 100),3)) AS real_amount FROM protocols ' .
                            'LEFT JOIN foods ON protocols.id_food = foods.id ' .
                            'RIGHT JOIN food_nutrients ON food_nutrients.id_food = foods.id ' .
                            'RIGHT JOIN nutrients ON nutrients.id = food_nutrients.id_nutrient ' .
                            'LEFT JOIN rdas ON rdas.id_nutrient = nutrients.id ' .
                            'WHERE protocols.id_user=' . self::Escape($userId) . ' ' .
                            'AND rdas.id_profile=' . self::Escape($user['id_profile']) . ' ' .
                            'GROUP BY nutrients.name');
    }

    static public function GetRDA($profileId, $nutrientId): ?array {
        $rdas = self::Select('SELECT * FROM rdas LEFT JOIN nutrients ON rdas.id_nutrient = nutrients.id ' .
                             'WHERE id_profile=' . self::Escape($profileId) . ' AND id_nutrient=' . self::Escape($nutrientId));
        return $rdas[0] ?? null;
    }

    static public function GetUser($userId): array {
        return self::Select('SELECT * FROM users ' .
                            'LEFT JOIN profiles ON users.id_profile = profiles.id ' .
                            'WHERE users.id=' . self::Escape($userId))[0];
    }
}
