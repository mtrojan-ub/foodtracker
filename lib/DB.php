<?php

namespace Foodtracker;

class DB {

    static protected function GetConnection() {
        if (!isset($GLOBALS['mysqli']))
            $GLOBALS['mysqli'] = new \mysqli(FOODTRACKER_DB_HOST, FOODTRACKER_DB_USERNAME, FOODTRACKER_DB_PASSWORD, FOODTRACKER_DB_DATABASE);
        return $GLOBALS['mysqli'];
    }

    static protected function Query($query) {
        return self::GetConnection()->query($query);
    }

    static protected function Escape($param) {
        if (is_array($param)) {
            $params = [];
            foreach ($param as $paramEntry)
                $params[] = self::GetConnection()->real_escape_string($paramEntry);
            return $params;
        } else
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

    static protected function SelectSingleColumn($query, $columnName): array {
        $values = [];
        $rows = self::Select($query);
        foreach ($rows as $row)
            $values[] = $row[$columnName];
        return $values;
    }

    static public function AddFood($name, $kcal, $amountDefault, $unitDefault) {
        $values = self::Escape([$name, $kcal, $amountDefault, $unitDefault]);
        return self::Query('INSERT INTO foods (name, kcal, amount_default, unit_default) ' .
                           'VALUES ("' . implode('","', $values) . '")');
    }

    static public function AddProtocolEntry($userId, $foodId, $amout, $date, $time) {
        $values = self::Escape([$userId, $foodId, $amout, $date, $time]);
        return self::Query('INSERT INTO protocols (id_user, id_food, amount, date, time) ' .
                           'VALUES ("' . implode('","', $values) . '")');
    }

    static public function DeleteFood($id) {
        return self::Query('DELETE FROM foods WHERE id=' . self::Escape($id));
    }

    static public function DeleteProtocolEntry($id) {
        return self::Query('DELETE FROM protocols WHERE id=' . self::Escape($id));
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

    static public function GetProtocolForUser($userId, $date=null): array {
        $query = 'SELECT *, foods.kcal * protocols.amount / 100 AS real_kcal, protocols.id AS id_protocol ' .
                 'FROM protocols LEFT JOIN foods ON foods.id=protocols.id_food ' .
                 'WHERE id_user=' . self::Escape($userId) . ' ';

        if ($date !== null)
            $query .= 'AND protocols.date = "' . self::Escape($date) . '" ';

        $query .= 'ORDER BY protocols.date ASC, protocols.time ASC';
        return self::Select($query);
    }

    static public function GetProtocolCaloriesForUser($userId, $date=null): int {
        $query = 'SELECT *, SUM(foods.kcal * protocols.amount / 100) AS real_kcal FROM protocols ' .
                 'LEFT JOIN foods ON protocols.id_food = foods.id ' .
                 'WHERE protocols.id_user=' . self::Escape($userId) . ' ';

        if ($date !== null)
            $query .= 'AND protocols.date = "' . self::Escape($date) . '" ';

        $rows = self::Select($query);

        return $rows[0]['real_kcal'] ?? 0;
    }

    static public function GetProtocolDatesForUser($userId) {
        return self::SelectSingleColumn('SELECT DISTINCT date FROM protocols WHERE id_user=' . self::Escape($userId) . ' ORDER BY date ASC', 'date');
    }

    static public function GetProtocolNutrientsForUser($userId, $date=null): array {
        $user = self::GetUser($userId);
        $query = 'SELECT *, SUM(ROUND(food_nutrients.amount *(protocols.amount / 100),3)) AS real_amount FROM protocols ' .
                 'LEFT JOIN foods ON protocols.id_food = foods.id ' .
                 'RIGHT JOIN food_nutrients ON food_nutrients.id_food = foods.id ' .
                 'RIGHT JOIN nutrients ON nutrients.id = food_nutrients.id_nutrient ' .
                 'LEFT JOIN rdas ON rdas.id_nutrient = nutrients.id ' .
                 'WHERE protocols.id_user=' . self::Escape($userId) . ' ' .
                 'AND rdas.id_profile=' . self::Escape($user['id_profile']) . ' ';

        if ($date != null)
            $query .= 'AND protocols.date = "' . self::Escape($date) . '" ';

        $query .= 'GROUP BY nutrients.name';
        return self::Select($query);
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

    static public function GetUserByLogin($username, $password): ?array {
        $rows = self::Select('SELECT * FROM users ' .
                             'LEFT JOIN profiles ON users.id_profile = profiles.id ' .
                             'WHERE users.login="' . self::Escape($username) . '" ' .
                             'AND users.password="' . self::Escape(md5($password)) . '"');
        return $rows[0] ?? null;
    }

    static public function IsFoodInProtocol($foodId): bool {
        $rows = self::Select('SELECT count(*) AS count FROM protocols WHERE id_food=' . self::Escape($foodId));
        return $rows[0]['count'] > 0;
    }
}
