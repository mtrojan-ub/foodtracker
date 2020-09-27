<?php
namespace Foodtracker;
$id = $_GET['id'] ?? null;
if (!isset($id))
    die('missing id');
$ingredient = DB::GetIngredient($id);
$nutrients = DB::GetNutrientsForIngredient($id);
?>

<h1><?=$ingredient['name']?></h1>
<table>
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
