<?php

namespace Foodtracker;

class ViewHelper {
    static public function GetNutrientCaption($nutrient): string {
        $caption = '';
        $caption .= ($nutrient['external_id_netdoktor'] != '') ? '<a href="//www.netdoktor.de/'.$nutrient['external_id_netdoktor'].'" target="_blank">' : '';
        $caption .= $nutrient['name'];
        $caption .= ($nutrient['external_id_netdoktor'] != '') ? '</a>' : '';
        return $caption;
    }

    static public function GetFoodSelect(): string {
        $foodSelect = '<select name="food">';
        $foods = DB::GetFoods();
        foreach ($foods as $food)
            $foodSelect .= '<option value="' . htmlspecialchars($food['id']) . '">' . htmlspecialchars($food['name']) . '</option>';

        $foodSelect .= '</select>';
        return $foodSelect;
    }
}