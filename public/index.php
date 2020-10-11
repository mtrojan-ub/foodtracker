<?php

namespace foodtracker;

require('../inc.php');

session_start();

// Login/logout actions must be processed before rendering the menu
if (ViewHelper::IsLoggedIn()) {
    ViewHelper::ProcessLogout();
} else {
    ViewHelper::ProcessLogin();
}

?>

<html>
    <head>
        <link rel="stylesheet" href="res/bootstrap.min.css">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
        <script src="res/jquery-3.5.1.slim.min.js"></script>
        <script src="res/popper.min.js"></script>
        <script src="res/bootstrap.min.js"></script>
        <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Foodtracker</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="?page=home">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPersonal" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Personal
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownPersonal">
                            <a class="dropdown-item" href="?page=protocol">Protocol</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBasics" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Basics
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownBasics">
                            <a class="dropdown-item" href="?page=nutrients">Nutrients</a>
                            <a class="dropdown-item" href="?page=foods">Foods</a>
                            <a class="dropdown-item" href="?page=profiles">Profiles</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="?page=nutrients_per_profile">Nutrients per Profile</a>
                        </div>
                    </li>
                    <?php if (ViewHelper::IsLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="?logout=true">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <div class="container-fluid">
            <?php
            $page = ViewHelper::GetCurrentPage();

            if (!empty($page))
                require('../tpl/' . $page . '.php');
            ?>
        </div>
    </body>
</html>
