<div class="container bg-light">
    <div class="row">
        <h1 class="col-12 text-center my-4"><?=$page_title?></h1>

        <?php if(isset($product_delete)): ?>
        <div class="alert alert-info p-3 col-12 col-md-6 col-xl-4 mx-auto mb-4 text-center">
            <p class="m-0"><?=($product_delete) ? "Sikeres" : "Sikertelen"?> terméktörlés!</p>
        </div>
        <?php endif; ?>

        <?php if(isset($_SESSION["user"]) && $_SESSION["user"]["permission"] == "admin"): ?>
        <div class="text-start mb-3">
            <a href="<?=BASE_URL?>uj-termek" class="btn btn-info">Új termék</a>
        </div>
        <?php endif; ?>
        
    </div>

    <div class="row mb-4">

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <label for="category_filter" class="ps-2">Kategória:</label>
            <select id="category_filter" class="form-select filter-input">
                <option value="">Mindegy</option>
                <?php foreach($category_list as $category): ?>
                <option value="<?=$category["id"]?>"><?=$category["name"]?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <label for="brand_filter" class="ps-2">Márka:</label>
            <select id="brand_filter" class="form-select filter-input">
                <option value="">Mindegy</option>
                <?php foreach($brand_list as $brand): ?>
                <option value="<?=$brand["brand"]?>"><?=$brand["brand"]?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <label for="type_filter" class="ps-2">Típus:</label>
            <input type="text" id="type_filter" class="form-control filter-input">
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <label for="min_price_filter">Min ár:</label>
            <input type="number" id="min_price_filter" step="1" min="0" class="form-control filter-input" placeholder="... Ft tól">
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <label for="max_price_filter">Max ár:</label>
            <input type="number" id="max_price_filter" step="1" min="0" class="form-control filter-input" placeholder="... Ft ig">
        </div>

    </div>

    <div class="row" id="product_list"></div>
</div>