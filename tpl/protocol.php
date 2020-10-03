<?php
namespace Foodtracker;
$protocols = DB::GetProtocolForUser(1);
$nutrients = DB::GetProtocolNutrientsForUser(1);
?>

<h1>Protocol</h1>
<h2>Foods</h2>
<table class="table">
    <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Food</th>
        <th>Amount</th>
    </tr>
    <?php foreach ($protocols as $protocol):?>
    <tr>
        <td><?=$protocol['date']?></td>
        <td><?=$protocol['time']?></td>
        <td><a href="?page=food_nutrients&id=<?=$protocol['id']?>"><?=$protocol['name']?></a></td>
        <td><?=$protocol['amount']?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Nutrients</h2>
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
                $rdaDiffAllowed = $nutrient['value'] * 0.1;
                $rdaMinValue = $nutrient['value'] - $rdaDiffAllowed;
                $rdaMaxValue = $nutrient['value'] + $rdaDiffAllowed;
                $rdaPercent = round(($nutrient['real_amount'] / $nutrient['value']) * 100);

                if ($nutrient['real_amount'] < $rdaMinValue)
                    $color = 'red';
                else if ($nutrient['real_amount'] > $rdaMaxValue)
                    $color = 'yellow';
                else
                    $color = 'black';
            ?>
            <tr>
                <td><?=$nutrient['name']?></td>
                <td style="color: <?=$color?>"><?=$nutrient['real_amount'] . $nutrient['unit']?></td>
                <td style="color: <?=$color?>"><?=$rdaPercent?></td>
                <td style="color: <?=$color?>"><?=$rdaDiffDisplay . $nutrient['unit']?></td>
                <td><?=$nutrient['value'] . $nutrient['unit']?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>


<script>
    $(document).ready( function () {
        $('#nutrientsTable').DataTable({
            paging: false
        });
    });
</script>
