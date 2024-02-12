<div class="container bg-light">
    <div class="row">
        <h1 class="col-12 text-center my-4"><?=$page_title?></h1>
        
        <div class="col-12 col-lg-6 mb-4">
            <div class="product-img-container product-page text-center bg-white shadow-sm">
                <img
                src="<?=BASE_URL?>/public/img/product_img/<?=$product_data["img"]?>"
                alt="<?=$page_title?> képe"
                title="<?=$page_title?>"
                class="img-fluid">
            </div>
        </div>

        <div class="col-12 col-lg-6 mb-4">
            <div class="shadow-sm bg-white">
                <table class="table">
                    <tr>
                        <td>Kategória:</td>
                        <td><?=$product_data["category_name"]?></td>
                    </tr>
                    <tr>
                        <td>Márka:</td>
                        <td><?=$product_data["brand"]?></td>
                    </tr>
                    <tr>
                        <td>Típus:</td>
                        <td><?=$product_data["type"]?></td>
                    </tr>
                    <tr>
                        <td>Ár:</td>
                        <td><?=$product_data["formatted_price"]?></td>
                    </tr>
                </table>
            </div>

            <div class="text-center my-5">
                <a href="<?=BASE_URL?>kosarmuvelet?muvelet=termek-hozzaadas&termek=<?=$product_data["id"]?>" class="btn btn-lg btn-primary">Kosárba</a>
            </div>

        </div>

        <div class="col-12 mb-4">
            <div class="shadow-sm bg-white p-3">
                <p class="m-0"><?=$product_data["description"]?></p>
            </div>
        </div>

    </div>
</div>