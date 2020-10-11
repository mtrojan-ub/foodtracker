<?php

namespace Foodtracker;

?>

<h1>Login</h1>
<p style="color: red;"><?=ViewHelper::GetLoginResultMessage()?></p>
<form method="post" action="?page=<?=ViewHelper::GetCurrentPage()?>">
    <div class="form-group">
        <label for="username">Username</label>
        <input class="form-control" id="username" name="username" placeholder="Username" required="required">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="required">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
