<?php
namespace Foodtracker;
$id = $_GET['id'] ?? null;
if (!isset($id))
    die('missing food id');

$action = $_POST['action'] ?? null;
switch($action) {
    case 'add_nutrient':
        DB::AddFoodNutrient($id, $_POST['id_nutrient'], str_replace(',', '.', $_POST['amount']));
        break;
    case 'delete_nutrient':
        DB::DeleteFoodNutrient($_POST['id_food_nutrient']);
        break;
}

$food = DB::GetFood($id);
$nutrients = DB::GetFoodNutrients($id);
?>

<h1><?=$food['name']?></h1>
<h2><?=$food['kcal']?>kcal / 100g</h2>
<table class="table">
    <thead>
        <tr>
            <th>Nutrient</th>
            <th>Amount / 100g</th>
            <?php if (ViewHelper::IsLoggedIn()): ?>
                <th>action</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nutrients as $nutrient): ?>
            <tr>
                <td><?=ViewHelper::GetNutrientCaption($nutrient)?></td>
                <td><?=$nutrient['amount']?><?=$nutrient['unit']?></td>
                <?php if (ViewHelper::IsLoggedIn()): ?>
                    <td>
                        <form action="?page=<?=urlencode(ViewHelper::GetCurrentPage())?>&id=<?=htmlspecialchars($id)?>" method="post">
                            <input type="hidden" name="action" value="delete_nutrient">
                            <input type="hidden" name="id_food_nutrient" value="<?=htmlspecialchars($nutrient['id_food_nutrient'])?>">
                            <button type="submit" class="btn btn-primary">delete</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <?php if (ViewHelper::IsLoggedIn()): ?>
        <tfoot>
            <tr>
                <form action="?page=<?=urlencode(ViewHelper::GetCurrentPage())?>&id=<?=urlencode($id)?>" method="post">
                    <input type="hidden" name="action" value="add_nutrient">
                    <td><?=ViewHelper::GetNutrientSelect()?></td>
                    <td><input name="amount" required="required" pattern="\d+((\.|,)\d+)?"></td>
                    <td><button type="submit" class="btn btn-primary">add</button></td>
                </form>
            </tr>
        </tfoot>
    <?php endif; ?>
</table>
