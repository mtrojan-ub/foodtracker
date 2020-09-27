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
        <td><?=$protocol['name']?></td>
        <td><?=$protocol['amount']?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Nutrients</h2>
<table class="table">
    <tr>
        <th>Nutrient</th>
        <th>Amount</th>
        <th>RDA</th>
        <th>Recommended min (-10%)</th>
        <th>Recommended max (+10%)</th>
    </tr>
    <?php foreach ($nutrients as $nutrient):?>
        <?php
            $rdaDiff = $nutrient['value']-$nutrient['real_amount'];
            $rdaDiffAllowed = $nutrient['value'] * 0.1;
            $rdaMinValue = $nutrient['value'] - $rdaDiffAllowed;
            $rdaMaxValue = $nutrient['value'] + $rdaDiffAllowed;

            if ($nutrient['real_amount'] < $rdaMinValue)
                $color = 'red';
            else if ($nutrient['real_amount'] > $rdaMaxValue)
                $color = 'yellow';
            else
                $color = 'black';
        ?>
        <tr>
            <td><?=$nutrient['name']?></td>
            <td style="color: <?=$color?>"><?=$nutrient['real_amount']?></td>
            <td><?=$nutrient['value']?></td>
            <td><?=$rdaMinValue?></td>
            <td><?=$rdaMaxValue?></td>
        </tr>
    <?php endforeach;?>
</table>
