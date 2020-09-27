<?php
namespace Foodtracker;
?>

<h1>Nutrients per Profile</h1>
<table>
    <?php $profiles = DB::GetProfiles();?>
    <tr>
        <th>Nutrient</th>
        <?php foreach ($profiles as $profile):?>
            <th>RDA <?=$profile['age_min'] . '-' . $profile['age_max'] . '(' . $profile['gender'] . ')'?></th>
        <?php endforeach;?>
    </tr>

    <?php
    $nutrients = DB::GetNutrients();
    foreach ($nutrients as $nutrient) {
        ?>
        <tr>
            <td><?=$nutrient['name']?></td>
            <?php foreach ($profiles as $profile):?>
                <td>
                    <?php $rda = DB::GetRDA($profile['id'], $nutrient['id']);?>
                    <?=!empty($rda) ? ($rda['value'] . $rda['unit']) : ''?>
                </td>
            <?php endforeach;?>
        </tr>
        <?php
    }
    ?>
</table>
