<?php
namespace Foodtracker;
$nutrients = DB::GetNutrients();
?>

<h1>Nutrients</h1>
<table class="table">
    <tr>
        <th>Name</th>
        <th>Unit</th>
    </tr>
    <?php foreach ($nutrients as $nutrient):?>
        <tr>
            <td><?=ViewHelper::GetNutrientCaption($nutrient)?></td>
            <td><?=$nutrient['unit']?></td>
        </tr>
    <?php endforeach;?>
</table>
