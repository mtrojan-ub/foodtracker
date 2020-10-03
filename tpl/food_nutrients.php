<?php
namespace Foodtracker;
$id = $_GET['id'] ?? null;
if (!isset($id))
    die('missing id');
$food = DB::GetFood($id);
$nutrients = DB::GetFoodNutrients($id);
?>

<h1><?=$food['name']?></h1>
<h2><?=$food['kcal']?>kcal / 100g</h2>
<table class="table">
    <tr>
        <th>Nutrient</th>
        <th>Amount / 100g</th>
    </tr>
    <?php foreach ($nutrients as $nutrient): ?>
        <tr>
            <td><?=$nutrient['name']?></td>
            <td><?=$nutrient['amount']?><?=$nutrient['unit']?></td>
        </tr>
    <?php endforeach; ?>
</table>
