<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=BASE_URL?>public/css/style.css?v=<?=time()?>">
    <title><?=$page_title?></title>
</head>
<body class="bg-secondary">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?=BASE_URL?>">TechShop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <?php if(isset($_SESSION["user"])): ?>
            <span class="navbar-text">Bejelentkezve: <?=$_SESSION["user"]["full_name"]?></span>
            <?php endif; ?>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?=BASE_URL?>termekek">Termékek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=BASE_URL?>kosar">Kosár</a>
                    </li>

                    <?php if(isset($_SESSION["user"])): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?=BASE_URL?>kijelentkezes">Kijelentkezés</a>
                    </li>

                    <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?=BASE_URL?>regisztracio">Regisztráció</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=BASE_URL?>bejelentkezes">Bejelentkezés</a>
                    </li>
                    
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
    <div class="navbar-spacing"></div>
