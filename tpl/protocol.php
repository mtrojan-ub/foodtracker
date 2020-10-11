<?php
namespace Foodtracker;
$user = DB::GetUser(1);
$today = date('Y-m-d');
$dateSelected = $_GET['date'] ?? $today;
$dates = DB::GetProtocolDatesForUser($user['id']);
if (!in_array($today, $dates))
    $dates[] = $today;
$protocols = DB::GetProtocolForUser($user['id'], $dateSelected);
$calories = DB::GetProtocolCaloriesForUser($user['id'], $dateSelected);
$nutrients = DB::GetProtocolNutrientsForUser($user['id'], $dateSelected);

?>

<h1>Protocol</h1>
<?php foreach ($dates as $date):?>
    <?php $dateLabel = ($date == $today) ? 'today' : $date?>
    <?php if ($date != $dateSelected): ?>
        <a href="?page=protocol&date=<?=urlencode($date)?>"><?=$dateLabel?></a>
    <?php else: ?>
        <b><?=$dateLabel?></b>
    <?php endif; ?>
<?php endforeach; ?>

<h2>Foods</h2>
<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Food</th>
            <th>Amount</th>
            <th>kcal</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($protocols as $protocol):?>
        <tr>
            <td><?=$protocol['date']?></td>
            <td><?=$protocol['time']?></td>
            <td><a href="?page=food_nutrients&id=<?=$protocol['id']?>"><?=$protocol['name']?></a></td>
            <td><?=$protocol['amount'] . $protocol['unit_default']?></td>
            <td><?=round($protocol['real_kcal'])?></td>
            <td><button>delete</button></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td><?=$dateSelected?></td>
            <td><input name="time" value="07:00:00" required="required" pattern="\d{2}:\d{2}:\d{2}"></td>
            <td><?=ViewHelper::GetFoodSelect()?></td>
            <td><input name="amount" value="100" required="required" pattern="\d+"></td>
            <td></td>
            <td><button>add</button></td>
        </tr>
    </tfoot>
</table>

<h2>Nutrients</h2>
<?php
    if ($calories < $user['kcal'])
        $color = 'green';
    else
        $color = '#EEEE00'; // dark yellow for better readibility
?>
<h3 style="color: <?=$color?>"><?=$calories?> / <?=$user['kcal']?>kcal</h3>
<table id="nutrientsTable" class="table">
    <thead>
        <tr>
            <th>Nutrient</th>
            <th>Amount</th>
            <th>% of RDA</th>
            <th>Diff to RDA</th>
            <th>RDA</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nutrients as $nutrient):?>
            <?php
                $rdaDiff = $nutrient['value'] - $nutrient['real_amount'];
                $rdaDiffDisplay = $nutrient['real_amount'] - $nutrient['value'];
                $rdaPercent = round(($nutrient['real_amount'] / $nutrient['value']) * 100);

                if ($rdaPercent < 25)
                    $color = 'red';
                else if ($rdaPercent < 50)
                    $color = 'orange';
                else if ($rdaPercent < 75)
                    $color = '#EEEE00'; // dark yellow for better readibility
                else if ($rdaPercent < 125)
                    $color = 'green';
                else if ($rdaPercent > 175)
                    $color = 'red';
                else if ($rdaPercent > 150)
                    $color = 'orange';
                else if ($rdaPercent > 125)
                    $color = '#EEEE00'; // dark yellow for better readibility

                $aRDAStart = ($nutrient['external_id_dge'] != '') ? '<a href="//www.dge.de/wissenschaft/referenzwerte/'.$nutrient['external_id_dge'].'" target="_blank">' : '';
                $aRDAEnd = ($nutrient['external_id_dge'] != '') ? '</a>' : '';
            ?>
            <tr>
                <td><?=ViewHelper::GetNutrientCaption($nutrient)?></td>
                <td style="color: <?=$color?>"><?=$nutrient['real_amount'] . $nutrient['unit']?></td>
                <td style="color: <?=$color?>"><?=$rdaPercent?></td>
                <td style="color: <?=$color?>"><?=$rdaDiffDisplay . $nutrient['unit']?></td>
                <td><?=$aRDAStart?><?=$nutrient['value'] . $nutrient['unit']?><?=$aRDAEnd?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>


<script>
    $(document).ready( function () {
        $('#nutrientsTable').DataTable({
            paging: false,
            order: [[ 2, "desc" ]]
        });
    });
</script>
