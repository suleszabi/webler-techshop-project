<div class="container bg-light">
    <div class="row">
        <h1 class="col-12 text-center my-4"><?=$page_title?></h1>

        <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto mb-4">
            <div class="bg-white shadow-sm p-3">
                <?php if(isset($error_messages)): // Validációs hiba esete ?>
                <div class="alert alert-warning p-2 text-center">
                    <?php if(isset($error_messages["login"])): ?>
                    <h5 class="m-0">Hibás bejelentkezési adatok!</h5>
                    <p class="m-0">Kérjük ellenőrizd a megadott e-mail címet és jelszót!</p>
                    <?php else: ?>
                    <h5 class="m-0">A megadott adatok nem megfelelőek!</h5>
                    <p class="m-0">A részletek lásd a beviteli mezők alatt!</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if(isset($login_success)) : ?>
                    <div class="alert alert-success p-2 text-center">
                        <h4 class="m-0">Sikeres bejelentkezés!</h4>
                    </div>
                <?php else: ?>

                <form action="<?=BASE_URL?>bejelentkezes" method="post">

                    <div class="mb-3">
                        <label for="email">e-mail cím:</label>
                        <input type="text" name="email" id="email"
                        class="form-control <?=(isset($error_messages["email"])) ? "is-invalid" : "";?>"
                        value="<?=(isset($old["email"])) ? $old["email"] : ""?>">
                        <?php if(isset($error_messages["email"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["email"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password">Jelszó:</label>
                        <input type="password" name="password" id="password"
                        class="form-control <?=(isset($error_messages["password"])) ? "is-invalid" : "";?>">
                        <?php if(isset($error_messages["password"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["password"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="text-end">
                        <button type="submit" name="login_btn" class="btn btn-primary">Bejelentkezés</button>
                    </div>
                </form>

                <?php endif;?>

            </div>
        </div>

    </div>
</div>