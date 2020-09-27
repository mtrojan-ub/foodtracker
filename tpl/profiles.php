<?php
namespace Foodtracker;
$profiles = DB::GetProfiles();
?>

<h1>Profiles</h1>
<table class="table">
    <tr>
        <th>Gender</th>
        <th>Min Age</th>
        <th>Max Age</th>
    </tr>
    <?php foreach ($profiles as $profile):?>
        <tr>
            <td><?=$profile['gender']?></td>
            <td><?=$profile['age_min']?></td>
            <td><?=$profile['age_max']?></td>
        </tr>
    <?php endforeach;?>
</table>
<?php

