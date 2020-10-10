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
}