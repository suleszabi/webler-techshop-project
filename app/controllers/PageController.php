<?php

    require_once ROOT."/app/models/ProductModel.php";

    class PageController {
        private $productModel;

        public function __construct(object $db) {
            $this->productModel = new ProductModel($db);
        }

        public function main_page() {
            
            // Tartalomhoz szükséges adatok gyűjtése, ellenőrzések stb.
            return $this->view("main", "Termékek", [], ["main"]);
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