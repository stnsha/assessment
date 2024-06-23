<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Phone Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link {
            text-decoration: none;
            color: black;
        }

        .nav-link.active {
            color: blue;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid justify-content-center">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" aria-current="page" href="index.php">List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'form.php' ? 'active' : ''; ?>" href="form.php">Form</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'statistic.php' ? 'active' : ''; ?>" href="statistic.php">Statistic</a>
                </li>
            </ul>
        </div>
    </nav>