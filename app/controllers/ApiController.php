<?php

    require_once ROOT."/app/models/ProductModel.php";

    class ApiController {
        private $productModel;

        public function __construct(object $db) {
            $this->productModel = new ProductModel($db);
        }

        public function list_products() {
            echo json_encode($this->productModel->product_list($_POST));
        }
    }

?>