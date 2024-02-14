<div class="container bg-light">
    <div class="row">
        <h1 class="col-12 text-center my-4"><?=$page_title?></h1>

        <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto mb-4">
            <div class="bg-white shadow-sm p-3">

                <?php if(isset($insert_success)): ?>

                <div class="alert alert-info p-3 mb-4">
                    <h5 class="m-0">A termék hozzáadása <?=($insert_success) ? "sikeres" : "sikertelen"?> volt!</h5>
                </div>

                <?php else: ?>
                
                <form action="<?=BASE_URL?>uj-termek" method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="category">Kategória:</label>
                        <select name="category" id="category"
                        class="form-select <?=(isset($error_messages["category"])) ? "is-invalid" : ""?>">
                            <option value="">Válassz!</option>
                            <?php foreach($category_list as $category): ?>
                            <option value="<?=$category["id"]?>"
                            <?=(isset($old["category"]) && $old["category"]==$category["id"]) ? "selected" : ""?>>
                                    <?=$category["name"]?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($error_messages["category"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["category"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="brand">Márka:</label>
                        <input type="text" name="brand" id="brand"
                        class="form-control <?=(isset($error_messages["brand"])) ? "is-invalid" : ""?>"
                        value="<?=(isset($old["brand"])) ? $old["brand"] : ""?>">
                        <?php if(isset($error_messages["brand"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["brand"]?></div>
                        <?php endif; ?>
                    </div>
            
                    <div class="mb-3">
                        <label for="type">Típus:</label>
                        <input type="text" name="type" id="type"
                        class="form-control <?=(isset($error_messages["type"])) ? "is-invalid" : ""?>"
                        value="<?=(isset($old["type"])) ? $old["type"] : ""?>">
                        <?php if(isset($error_messages["type"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["type"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="price">Ár:</label>
                        <input type="number" name="price" id="price"
                        class="form-control <?=(isset($error_messages["price"])) ? "is-invalid" : ""?>"
                        value="<?=(isset($old["price"])) ? $old["price"] : ""?>">
                        <?php if(isset($error_messages["price"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["price"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="description">Leírás:</label>
                        <textarea name="description" id="description" rows="4"
                        class="form-control <?=(isset($error_messages["description"])) ? "is-invalid" : ""?>"><?=(isset($old["description"])) ? $old["description"] : ""?></textarea>
                        <?php if(isset($error_messages["description"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["description"]?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="img">Termékkép:</label>
                        <input type="file" name="img" id="img"
                        class="form-control <?=(isset($error_messages["img"])) ? "is-invalid" : ""?>"
                        accept="image/jpeg" required>
                        <?php if(isset($error_messages["img"])): ?>
                        <div class="invalid-feedback"><?=$error_messages["img"]?></div>
                        <?php endif; ?>
                    </div> 
            
                    <div class="text-center">
                        <button type="submit" name="new_product_btn" class="btn btn-info">Új termék hozzáadása</button>
                    </div>
                
                </form>

                <?php endif; ?>

            </div>
        </div>

    </div>
</div>