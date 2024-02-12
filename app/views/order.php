<div class="container bg-light">
    <div class="row">
        <h1 class="col-12 text-center my-4"><?=$page_title?></h1>

        <?php if(isset($insert_order)): ?>
        
            <?php if($insert_order): ?>
            <div class="col-12 col-md-6 col-xl-4 mx-auto alert alert-success p-3">
                <h5 class="text-center m-0">A rendelés leadása sikeres volt!</h5>
            </div>
            <?php else: ?>
            <div class="col-12 col-md-6 col-xl-4 mx-auto alert alert-danger p-3">
                <h5 class="text-center m-0">A rendelés leadása során hiba lépett fel!</h5>
                <p class="fw-bold m-0 text-center">Kérjük, mihamarabb vegye fel a kapcsolatot az oldal üzemeltetőjével!</p>
            </div>
            <?php endif; ?>

            <div class="col-12 text-center mb-4">
                <a href="<?=BASE_URL?>" class="btn btn-primary">Vissza a főoldalra</a>
            </div>

        <?php else: ?>

        <div class="col-12 mb-4">
            <div class="bg-white shadow-sm p-3">

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-start">Termék</th>
                                <th class="text-end">Egységár</th>

                                <th class="text-center">Darab</th>

                                <th class="text-end">Részösszeg</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cart_product_list as $cart_product): ?>
                            
                            <tr>
                                <td class="text-start"><?=$cart_product["brand"]?> <?=$cart_product["type"]?> <?=$cart_product["category_name"]?></td>
                                <td class="text-end"><?=$cart_product["formatted_price"]?></td>

                                <td class="text-center"><?=$cart_product["cart_qty"]?></td>

                                <td class="text-end"><?=$cart_product["subtotal_price"]?></td>

                            </tr>

                            <?php endforeach; ?>
                            <tr>
                                <td class="text-start fw-bold" colspan="3">Kosárérték:</td>
                                <td class="text-end fw-bold"><?=$cart_total_price?></td>

                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto mb-4">
            <div class="bg-white shadow-sm p-3">
                <h4 class="text-center">Rendelési adatok</h4>

                <form action="<?=BASE_URL?>rendeles" method="post">
                
                    <div class="mb-3">
                        <label for="address">Cím:</label>
                        <input type="text" name="address" id="address"
                        class="form-control <?=(isset($error_messages["address"])) ? "is-invalid" : "";?>"
                        value="<?=(isset($old["address"])) ? $old["address"] : ""?>">
                        <?php if(isset($error_messages["address"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["address"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="tel">Telefon:</label>
                        <input type="text" name="tel" id="tel"
                        class="form-control <?=(isset($error_messages["tel"])) ? "is-invalid" : "";?>"
                        value="<?=(isset($old["tel"])) ? $old["tel"] : ""?>">
                        <?php if(isset($error_messages["tel"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["tel"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="text-end">
                        <button type="submit" name="order_btn" class="btn btn-primary">Rendelés leadása</button>
                    </div>

                </form>
            </div>
        </div>

        <?php endif; ?>

    </div>
</div>