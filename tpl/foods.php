<?php

namespace Foodtracker;

$action = $_POST['action'] ?? null;
switch($action) {
    case 'add_food':
        DB::AddFood($_POST['name'], $_POST['kcal'], $_POST['amount_default'], $_POST['unit_default']);
        break;
}

$foods = DB::GetFoods();
?>

<h1>Foods</h1>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>kcal / 100g</th>
            <th>default amount</th>
            <th>default unit</th>
            <?php if (ViewHelper::IsLoggedIn()): ?>
                <th>action</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($foods as $food):?>
            <tr>
                <td><a href="?page=food_nutrients&id=<?=$food['id']?>"><?=$food['name']?></a></td>
                <td><?=$food['kcal']?></td>
                <td><?=$food['amount_default']?></td>
                <td><?=$food['unit_default']?></td>
                <?php if (ViewHelper::IsLoggedIn()): ?>
                    <td>
                        <form action="?page=<?=urlencode(ViewHelper::GetCurrentPage())?>" method="post">
                            <input type="hidden" name="action" value="delete_food">
                            <button type="submit" class="btn btn-primary" <?=DB::IsFoodInProtocol($food['id']) ? 'disabled="disabled"' : ''?>>delete</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach;?>
    </tbody>
    <?php if (ViewHelper::IsLoggedIn()): ?>
        <tfoot>
            <tr>
                <form action="?page=<?=urlencode(ViewHelper::GetCurrentPage())?>" method="post">
                    <input type="hidden" name="action" value="add_food">
                    <td><input name="name" required="required"></td>
                    <td><input name="kcal" required="required" pattern="\d+"></td>
                    <td><input name="amount_default" value="100" required="required" pattern="\d+"></td>
                    <td><select name="unit_default"><option>g</option><option>ml</option></select></td>
                    <td><button type="submit" class="btn btn-primary">add</button></td>
                </form>
            </tr>
        </tfoot>
    <?php endif; ?>
</table>
