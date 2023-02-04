<?php ?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="partials/style.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>Diplomová práca</title>
</head>
<body>
<header>
    <div class="header_section">
        <div class="container-fluid header_main">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <span class="menu-place"></span>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Zoznam predmetov</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pridať</a>
                            <div class="dropdown-content">
                                <a class="nav-link" href="addSubject.php">Predmet</a>
                                <a class="nav-link" href="addTeacher.php">Učiteľa</a>
                                <a class="nav-link" href="addRoom.php">Miestnosť</a>
                                <a class="nav-link" href="addFieldOfStudy.php">Odbor</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Vymazať</a>
                            <div class="dropdown-content">
                                <a class="nav-link" href="deleteSubject.php">Predmet</a>
                                <a class="nav-link" href="deleteTeacher.php">Učiteľa</a>
                                <a class="nav-link" href="deleteRoom.php">Miestnosť</a>
                                <a class="nav-link" href="deleteFieldOfStudy.php">Odbor</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
</header>
