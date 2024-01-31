<?php

    require_once ROOT."/app/models/ProductModel.php";

    class PageController {
        private $productModel;

        public function __construct(object $db) {
            $this->productModel = new ProductModel($db);
        }

        public function not_found() {
            return $this->view("not_found", "Az oldal nem található");
        }

        public function main_page() {
            return $this->view("main", "Termékek", [
                "category_list" => $this->productModel->category_list(),
                "brand_list" => $this->productModel->brand_list()
            ], ["main"]);
        }

        public function product_page() {
            if(isset($_GET["id"]) && is_numeric($_GET["id"])) {
                $product_data = $this->productModel->product_data($_GET["id"]);
                if($product_data) {
                    $page_title = $product_data["brand"]." ".$product_data["type"];
                    return $this->view("product", $page_title, ["product_data" => $product_data]);
                }
            }
            
            header("Location: ".BASE_URL);
        }

        # Privát belső segédfüggvény - helper
        private function view(string $view_name, string $page_title, array $variables = [], array $js_scripts = []) {
            // $variables asszociatív tömb elemeinek konvertálása önálló változókká
            foreach($variables as $var_name => $var_value) {
                ${$var_name} = $var_value;
            }
            $variables = null;
            unset($variables);

            require_once ROOT."/app/views/_html_top.php";
            require_once ROOT."/app/views/$view_name.php";
            require_once ROOT."/app/views/_html_bottom.php";
        }
    }


?>