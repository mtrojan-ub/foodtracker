<?php
namespace Foodtracker;
$foods = DB::GetFoods();
?>

<h1>Foods</h1>
<table class="table">
    <tr>
        <th>Name</th>
        <th>kcal / 100g</th>
    </tr>
    <?php foreach ($foods as $food):?>
        <tr>
            <td><a href="?page=food_nutrients&id="<?=$food['id']?>><?=$food['name']?></a></td>
            <td><?=$food['kcal']?></td>
        </tr>
    <?php endforeach;?>
</table>
