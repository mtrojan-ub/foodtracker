<?php

namespace Foodtracker;

class DB {
    static protected function Query($query): \mysqli_result {
        // keep it simple for now, use persistent connections + custom access data later
        $connection = new \mysqli('localhost', 'root', '', 'foodtracker');
        return $connection->query($query);
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
        return self::Select('SELECT * FROM foods WHERE id=' . $id)[0];
    }

    static public function GetFoods(): array {
        return self::Select('SELECT * FROM foods');
    }

    static public function GetNutrients(): array {
        return self::Select('SELECT * FROM nutrients');
    }

    static public function GetFoodNutrients($foodId): array {
        $query = 'SELECT * FROM foods '
               . 'LEFT JOIN food_nutrients ON foods.id = food_nutrients.id_food '
               . 'LEFT JOIN nutrients ON nutrients.id = food_nutrients.id_nutrient '
               . 'WHERE foods.id=' . $foodId;
        return self::Select($query);
    }

    static public function GetProfiles(): array {
        return self::Select('SELECT * FROM profiles');
    }

    static public function GetRDA($profileId, $nutrientId): ?array {
        $rdas = self::Select('SELECT * FROM rdas LEFT JOIN nutrients ON rdas.id_nutrient = nutrients.id ' .
                             'WHERE id_profile=' . $profileId . ' AND id_nutrient=' . $nutrientId);
        return $rdas[0] ?? null;
    }
}
