<div class="container bg-light">
    <div class="row">
        <h1 class="col-12 text-center my-4"><?=$page_title?></h1>

        <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto mb-4">
            <div class="bg-white shadow-sm p-3">
                <?php if(isset($error_messages)): // Validációs hiba esete ?>
                <div class="alert alert-warning p-2 text-center">
                    <h5 class="m-0">A megadott adatok nem megfelelőek!</h5>
                    <p class="m-0">A részletek lásd a beviteli mezők alatt!</p>
                </div>
                <?php endif; ?>

                <?php if(isset($reg_success)): // van regisztráció eredmény?>
                    <?php if($reg_success): // Sikeres regisztráció esete ?>
                        <div class="alert alert-success p-2 text-center">
                            <h4 class="m-0">Sikeres regisztráció!</h4>
                            <p class="m-0">Most már be tudsz jelentkezni.</p>
                        </div>
                    <?php else: // Sikertelen regisztráció esete?>
                        <div class="alert alert-danger p-2 text-center">
                            <h4 class="m-0">Sikerestelen regisztráció!</h4>
                            <p class="m-0">A regisztráció során váratlan alkalmazáshiba történt.</p>
                            <p class="m-0">Kérlek, vedd fel a kapcsolatot az oldal üzemeltetőjével!</p>
                        </div>
                    <?php endif; ?>

                <?php else: // Alap megnyitás / validációs hiba esete ?>

                <form action="<?=BASE_URL?>regisztracio" method="post">

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Teljes név:</label>
                        <input type="text" name="full_name" id="full_name"
                        class="form-control <?=(isset($error_messages["full_name"])) ? "is-invalid" : "";?>"
                        value="<?=(isset($old["full_name"])) ? $old["full_name"] : "";?>">
                        <?php if(isset($error_messages["full_name"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["full_name"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">e-mail cím:</label>
                        <input type="text" name="email" id="email"
                        class="form-control <?=(isset($error_messages["email"])) ? "is-invalid" : "";?>"
                        value="<?=(isset($old["email"])) ? $old["email"] : "";?>">
                        <?php if(isset($error_messages["email"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["email"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Jelszó:</label>
                        <input type="password" name="password" id="password"
                        class="form-control <?=(isset($error_messages["password"])) ? "is-invalid" : "";?>">
                        <?php if(isset($error_messages["password"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["password"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Jelszó megerősítése:</label>
                        <input type="password" name="password_confirm" id="password_confirm"
                        class="form-control <?=(isset($error_messages["password_confirm"])) ? "is-invalid" : "";?>">
                        <?php if(isset($error_messages["password_confirm"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["password_confirm"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary" name="reg_btn">Regisztráció</button>
                    </div>

                </form>

                <?php endif; ?>
            </div>
        </div>

    </div>
</div>