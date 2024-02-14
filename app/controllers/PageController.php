<?php

    require_once ROOT."/app/models/ProductModel.php";
    require_once ROOT."/app/models/UserModel.php";
    require_once ROOT."/app/models/OrderModel.php";

    class PageController {
        private $productModel;
        private $userModel;
        private $orderModel;

        public function __construct(object $db) {
            $this->productModel = new ProductModel($db);
            $this->userModel = new UserModel($db);
            $this->orderModel = new OrderModel($db);
        }

        public function not_found() {
            return $this->view("not_found", "Az oldal nem található");
        }

        public function main_page() {
            $view_data = [
                "category_list" => $this->productModel->category_list(),
                "brand_list" => $this->productModel->brand_list()
            ];
            if(isset($_SESSION["product_delete"])) {
                $view_data["product_delete"] = $_SESSION["product_delete"];
                unset($_SESSION["product_delete"]);
            }
            return $this->view("main", "Termékek", $view_data, ["main"]);
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

        public function registrate() {
            # Bejelentkezés ellenőrzés
            if(isset($_SESSION["user"])) {
                header("Location: ".BASE_URL);
                return null;
            }

            # Azonosítjuk a felhasználó megnyitási célját
            if(!isset($_POST["reg_btn"])) {
                return $this->view("reg_form", "Regisztráció");
            }

            # Inputok validálása
            $error_messages = [];

            // Teljes név validálása
            // kötelező | min:3 | max:255
            if(!isset($_POST["full_name"]) || empty($_POST["full_name"])) {
                $error_messages["full_name"] = "A teljes név megadása kötelező!";
            } else if(mb_strlen($_POST["full_name"]) < 3) {
                $error_messages["full_name"] = "A teljes névnek legalább 3 karakter hosszúnak kell lennie!";
            } else if(mb_strlen($_POST["full_name"]) > 255) {
                $error_messages["full_name"] = "A teljes név legfeljebb 255 karakter hosszú lehet!";
            }

            // e-mail cím validálása
            // kötelező | email formátum | max:255 | egyedi
            if(!isset($_POST["email"]) || empty($_POST["email"])) {
                $error_messages["email"] = "Az e-mail cím megadása kötelező!";
            } else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error_messages["email"] = "Az e-mail cím formátuma nem megfelelő!";
            } else if(mb_strlen($_POST["email"]) > 255) {
                $error_messages["email"] = "Az e-mail cím legfeljebb 255 karakter hosszú lehet!";
            } else if(!$this->userModel->isEmailUnique($_POST["email"])) {
                $error_messages["email"] = "Ezzel az e-mail címmel már van regisztrált felhasználó!";
            }

            // Jelszó validálása
            // Kötelező | min:8 | max:64 | tartalmaz:számot,kisbetűt,nagybetűt
            if(!isset($_POST["password"]) || empty($_POST["password"])) {
                $error_messages["password"] = "A jelszó megadása kötelező!";
            } else if(strlen($_POST["password"]) < 8) {
                $error_messages["password"] = "A jelszónak legalább 8 karakter hosszúnak kell lennie!";
            } else if(strlen($_POST["password"]) > 64) {
                $error_messages["password"] = "A jelszó maximum 64 karakter hosszú lehet!";
            } else if(
                !preg_match('/[0-9]/', $_POST["password"]) ||
                !preg_match('/[a-z]/', $_POST["password"]) ||
                !preg_match('/[A-Z]/', $_POST["password"])
            ) {
                $error_messages["password"] = "A jelszónak tartalmaznia kell számot, kis- és nagybetűt!";
            }


            // Jelszó megerősítés validálása
            // Kötelező | Ha a password jó, akkor egyezés
            if(!isset($_POST["password_confirm"]) || empty($_POST["password_confirm"])) {
                $error_messages["password_confirm"] = "A jelszó megerősítés megadása kötelező!";
            } else if(!isset($error_messages["password"]) && $_POST["password"] != $_POST["password_confirm"]) {
                $error_messages["password_confirm"] = "A megerősítés nem egyezik a jelszóval!";
            }

            # Validálás kiértékelése
            if(!empty($error_messages)) { // van hiba
                return $this->view("reg_form", "Regisztráció", [
                    "error_messages" => $error_messages, 
                    "old" => $_POST
                ]);
            }

            # Regisztráció folyamata
            $reg_success = $this->userModel->insertNewUser($_POST);

            # Sikeres regisztráció visszajelzés
            return $this->view("reg_form", "Regisztráció", ["reg_success" => $reg_success]);
            
            
        }

        public function login() {
            # Bejelentkezés ellenőrzés
            if(isset($_SESSION["user"])) {
                header("Location: ".BASE_URL);
                return null;
            }

            # Űrlap küldés ténye
            if(!isset($_POST["login_btn"])) {
                return $this->view("login_form", "Bejelentkezés");
            }

            # Inputok validálása
            $error_messages = [];

            if(!isset($_POST["email"]) || empty($_POST["email"])) {
                $error_messages["email"] = "Az e-mail cím megadása kötelező!";
            } else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error_messages["email"] = "Az e-mail cím formátuma hibás!";
            }

            if(!isset($_POST["password"]) || empty($_POST["password"])) {
                $error_messages["password"] = "A jelszó megadása kötelező!";
            }

            # Frontend validálás kiértékelése
            if(!empty($error_messages)) {
                return $this->view("login_form", "Bejelentkezés", [
                    "error_messages" => $error_messages,
                    "old" => $_POST
                ]);
            }

            # Bejelentkezés folyamata
            $user_data = $this->userModel->verifyLoginData($_POST["email"], $_POST["password"]);

            if(!$user_data) {
                return $this->view("login_form", "Bejelentkezés", [
                    "error_messages" => ["login" => false],
                    "old" => $_POST
                ]);
            }

            # Bejelentkezés állapot tárolása
            $_SESSION["user"] = [
                "id" => $user_data["id"],
                "email" => $user_data["email"],
                "full_name" => $user_data["full_name"],
                "permission" => $this->userModel->permissions[$user_data["permission"]]
            ];

            return $this->view("login_form", "Bejelentkezés", ["login_success" => true]);
        }

        public function logout() {
            unset($_SESSION["user"]);
            unset($_SESSION["cart"]);
            header("Location: ".BASE_URL);
        }

        public function cart() {
            if(!isset($_SESSION["cart"])) {
                return $this->view("cart", "Kosár", ["alert_message" => "cart_empty"]);
            }

            $cart_data = $this->productModel->cart_data();

            return $this->view("cart", "Kosár", [
                "cart_product_list" => $cart_data["cart_product_list"],
                "cart_total_price" => $cart_data["cart_total_price"]
            ]);
        }

        public function cartMethod() {
            # GET kulcsok a vezérléshez
            // muvelet= opciók: termek-hozzaadas, termek-csokkentes, termek-torles, kosar-urites
            // termek= numeric id
            if(!isset($_GET["muvelet"])) {
                header("Location: ".BASE_URL);
                return null;
            }

            $methodsWithProduct = ["termek-hozzaadas", "termek-csokkentes", "termek-torles"];
            if(in_array($_GET["muvelet"], $methodsWithProduct)) {
                if(isset($_GET["termek"]) && is_numeric($_GET["termek"])) {
                    $termek_id = $_GET["termek"];
                    $product_data = $this->productModel->product_data($termek_id);
                    if(!$product_data) {
                        header("Location: ".BASE_URL);
                        return null;
                    }
                } else {
                    header("Location: ".BASE_URL);
                    return null;
                }
            }

            /*
                Kosár szerkezete a Sessionben
                $_SESSION["cart"] = [
                    "15" => 2,
                    "3" => 1
                ]
                Értelmezés
                    A 15. azonosítójú termékből 2db, a 3. azonosítójúból pedig 1 darab van a kosárban
            */

            switch($_GET["muvelet"]) {
                case "termek-hozzaadas":
                    if(isset($_SESSION["cart"][$termek_id])) {
                        $_SESSION["cart"][$termek_id]++;
                    } else {
                        $_SESSION["cart"][$termek_id] = 1;
                    }
                    break;
                case "termek-csokkentes":
                    if(isset($_SESSION["cart"][$termek_id])) {
                        if($_SESSION["cart"][$termek_id] > 1) {
                            $_SESSION["cart"][$termek_id]--;
                        } else {
                            unset($_SESSION["cart"][$termek_id]);
                        }

                        if(empty($_SESSION["cart"])) {
                            unset($_SESSION["cart"]);
                        }
                    }
                    break;
                case "termek-torles":
                    unset($_SESSION["cart"][$termek_id]);
                    if(empty($_SESSION["cart"])) {
                        unset($_SESSION["cart"]);
                    }
                    break;
                case "kosar-urites":
                    unset($_SESSION["cart"]);
                    break;
                default:
                    header("Location: ".BASE_URL);
                    return null;
            }

            header("Location: ".BASE_URL."kosar");
            return null;

        }

        public function order() {
            if(!isset($_SESSION["cart"]) || !isset($_SESSION["user"])) {
                header("Location: ".BASE_URL."kosar");
                return null;
            }

            # Kosár adatok a megjelenítéshez
            $cart_data = $this->productModel->cart_data();

            if(!isset($_POST["order_btn"])) {
                return $this->view("order", "Rendelés leadása", [
                    "cart_product_list" => $cart_data["cart_product_list"],
                    "cart_total_price" => $cart_data["cart_total_price"]
                ]);
            }

            # Validálás
            $error_messages = [];

            if(!isset($_POST["address"]) || empty($_POST["address"])) {
                $error_messages["address"] = "A cím megadása kötelező!";
            } else if(mb_strlen($_POST["address"]) > 255) {
                $error_messages["address"] = "A cím legfeljebb 255 karakter hosszú lehet!";
            }

            if(!isset($_POST["tel"]) || empty($_POST["tel"])) {
                $error_messages["tel"] = "A telefonszám megadása kötelező!";
            } else if(mb_strlen($_POST["tel"]) > 100) {
                $error_messages["tel"] = "A telefonszám legfeljebb 100 karakter hosszú lehet!";
            }

            if(!empty($error_messages)) {
                return $this->view("order", "Rendelés leadása", [
                    "cart_product_list" => $cart_data["cart_product_list"],
                    "cart_total_price" => $cart_data["cart_total_price"],
                    "error_messages" => $error_messages,
                    "old" => $_POST
                ]);
            }

            $order = $this->orderModel->insert_order($_POST["address"], $_POST["tel"], $cart_data["cart_product_list"]);

            if($order) {
                unset($_SESSION["cart"]);
            }

            return $this->view("order", "Rendelés leadása", ["insert_order" => $order]);
        }

        # Admin folyamatok
        public function deleteProduct() {
            if(
                isset($_SESSION["user"]) && $_SESSION["user"]["permission"] == "admin" &&
                isset($_GET["termek"]) && is_numeric($_GET["termek"])
                
            ) {
                // "törlési" művelet csak bejelentkezett admin esetén
                $_SESSION["product_delete"] = $this->productModel->delete($_GET["termek"]);
            }

            header("Location: ".BASE_URL);
            return null;
        }

        public function newProduct() {
            if(!isset($_SESSION["user"]) || $_SESSION["user"]["permission"] != "admin") {
                header("Location: ".BASE_URL);
                return null;
            }

            if(!isset($_POST["new_product_btn"])) {
                return $this->view("new_product_form", "Új termék", [
                    "category_list" => $this->productModel->category_list()
                ]);
            }

            # Validálás
            $error_messages = [];

            if(!isset($_POST["category"]) || empty($_POST["category"])) {
                $error_messages["category"] = "A kategória kiválasztása kötelező!";
            } else if(!is_numeric($_POST["category"])) {
                $error_messages["category"] = "Hibás kategória azonosító!";
            }

            if(!isset($_POST["brand"]) || empty($_POST["brand"])) {
                $error_messages["brand"] = "A márka megadása kötelező!";
            }

            if(!isset($_POST["type"]) || empty($_POST["type"])) {
                $error_messages["type"] = "A típus megadása kötelező!";
            }

            if(!isset($_POST["price"]) || empty($_POST["price"])) {
                $error_messages["price"] = "Az ár megadása kötelező!";
            } else if(!is_numeric($_POST["price"])) {
                $error_messages["price"] = "Az ár formátuma hibás! Csak számot adj meg!";
            }

            if(!isset($_POST["description"]) || empty($_POST["description"])) {
                $error_messages["description"] = "A leírás megadása kötelező!";
            }

            if(!empty($error_messages)) {
                return $this->view("new_product_form", "Új termék", [
                    "category_list" => $this->productModel->category_list(),
                    "error_messages" => $error_messages,
                    "old" => $_POST
                ]);
            }

            # Fájl feltöltés / validálás
            if(isset($_FILES["img"]["tmp_name"]) && !empty($_FILES["img"]["tmp_name"])) {

                if($_FILES["img"]["type"] == "image/jpeg") {

                    $img_filename = uniqid()."_".$_FILES["img"]["name"];
                    $img_path = ROOT."/public/img/product_img/$img_filename";
                    

                    if(move_uploaded_file($_FILES["img"]["tmp_name"], $img_path)) {
                        
                        # Adatbázis adattárolás és visszatérés
                        return $this->view("new_product_form", "Új termék", [
                            "insert_success" => $this->productModel->insert(array_merge($_POST, ["img" => $img_filename]))
                        ]);

                    } else {
                        $error_messages["img"] = "A kép tárolása során hiba lépett fel!";
                    }

                } else {
                    $error_messages["img"] = "A kép formátuma nem JPG!";
                }

            } else {
                $error_messages["img"] = "A fájl tallózása során hiba lépett fel!";
            }

            return $this->view("new_product_form", "Új termék", [
                "category_list" => $this->productModel->category_list(),
                "error_messages" => $error_messages,
                "old" => $_POST
            ]);
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