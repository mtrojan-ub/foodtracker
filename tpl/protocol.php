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
<table>
    <tr>
        <th>Nutrient</th>
        <th>Amount</th>
    </tr>
    <?php foreach ($nutrients as $nutrient):?>
        <tr>
            <td><?=$nutrient['name']?></td>
            <td><?=$nutrient['real_amount']?></td>
        </tr>
    <?php endforeach;?>
</table>
