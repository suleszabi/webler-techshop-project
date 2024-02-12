<div class="container bg-light">
    <div class="row">
        <h1 class="col-12 text-center my-4"><?=$page_title?></h1>

        <div class="col-12 text-center mb-4">
            <div class="bg-white shadow-sm p-3">
                
                <?php if(isset($alert_message) && $alert_message == "cart_empty"): ?>
                <div class="col-12 col-md-6 col-xl-4 mx-auto alert alert-warning text-center p-3">
                    <h5 class="m-0">A kosár üres!</h5>
                </div>
                <?php endif; ?>

                <?php if(isset($_SESSION["cart"])): ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-start">Termék</th>
                                <th class="text-end">Egységár</th>
                                <th class="text-end"></th>
                                <th class="text-center">Darab</th>
                                <th class="text-start"></th>
                                <th class="text-end">Részösszeg</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cart_product_list as $cart_product): ?>
                            
                            <tr>
                                <td class="text-start"><?=$cart_product["brand"]?> <?=$cart_product["type"]?> <?=$cart_product["category_name"]?></td>
                                <td class="text-end"><?=$cart_product["formatted_price"]?></td>
                                <td class="text-end">
                                    <a href="<?=BASE_URL?>kosarmuvelet?muvelet=termek-csokkentes&termek=<?=$cart_product["id"]?>" class="btn btn-sm btn-secondary fw-bold" title="Darabszám csökkentése">-</a>
                                </td>
                                <td class="text-center"><?=$cart_product["cart_qty"]?></td>
                                <td class="text-start">
                                    <a href="<?=BASE_URL?>kosarmuvelet?muvelet=termek-hozzaadas&termek=<?=$cart_product["id"]?>" class="btn btn-sm btn-secondary fw-bold" title="Darabszám növelése">+</a>
                                </td>
                                <td class="text-end"><?=$cart_product["subtotal_price"]?></td>
                                <td class="text-center">
                                    <a href="<?=BASE_URL?>kosarmuvelet?muvelet=termek-torles&termek=<?=$cart_product["id"]?>" class="btn btn-sm btn-secondary fw-bold" title="Termék törlése">X</a>
                                </td>
                            </tr>

                            <?php endforeach; ?>
                            <tr>
                                <td class="text-start fw-bold" colspan="5">Kosárérték:</td>
                                <td class="text-end fw-bold"><?=$cart_total_price?></td>
                                <td class="text-center">
                                    <a href="<?=BASE_URL?>kosarmuvelet?muvelet=kosar-urites" class="btn btn-sm btn-warning fw-bold">Kosár ürítés</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php endif; ?>

                <?php if(isset($_SESSION["cart"])): ?>
                
                    <?php if(isset($_SESSION["user"])): ?>
                        <div class="text-center my-3">
                            <a href="<?=BASE_URL?>rendeles" class="btn btn btn-primary">Rendelés leadása</a>
                        </div>
                    <?php else: ?>
                        <div class="text-center my-3">
                            <p class="fw-bold">A rendelés leadásához be kell jelentkezned!</p>
                            <a href="<?=BASE_URL?>bejelentkezes" class="btn btn-primary">Bejelentkezés</a>
                            <a href="<?=BASE_URL?>regisztracio" class="btn btn-primary">Regisztráció</a>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>
        </div>

    </div>
</div>