<?php
namespace Foodtracker;
$nutrients = DB::GetIngredients();
?>

<h1>Ingredients</h1>
<table>
    <tr>
        <th>Name</th>
        <th>kcal / 100g</th>
    </tr>
    <?php foreach ($nutrients as $nutrient):?>
        <tr>
            <td><a href="?page=ingredient_nutrients&id="<?=$nutrient['id']?>><?=$nutrient['name']?></a></td>
            <td><?=$nutrient['kcal']?></td>
        </tr>
    <?php endforeach;?>
</table>
