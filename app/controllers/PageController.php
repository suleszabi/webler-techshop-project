<?php

    class PageController {
        private $db;

        public function __construct(object $db) {
            $this->db = $db;
        }

        public function main_page() {
            
            // Tartalomhoz szükséges adatok gyűjtése, ellenőrzések stb.
            return $this->view("main", "Főoldal");
        }

        # Privát belső segédfüggvény - helper
        private function view(string $view_name, string $page_title) {
            require_once ROOT."/app/views/_html_top.php";
            require_once ROOT."/app/views/$view_name.php";
            require_once ROOT."/app/views/_html_bottom.php";
        }
    }


?>