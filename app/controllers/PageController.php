<?php

    require_once ROOT."/app/models/ProductModel.php";
    require_once ROOT."/app/models/UserModel.php";

    class PageController {
        private $productModel;
        private $userModel;

        public function __construct(object $db) {
            $this->productModel = new ProductModel($db);
            $this->userModel = new UserModel($db);
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
                "full_name" => $user_data["full_name"]
            ];

            return $this->view("login_form", "Bejelentkezés", ["login_success" => true]);
        }

        public function logout() {
            unset($_SESSION["user"]);
            header("Location: ".BASE_URL);
        }

        public function cart() {
            if(!isset($_SESSION["cart"])) {
                return $this->view("cart", "Kosár", ["alert_message" => "cart_empty"]);
            }

            $cart_product_list = [];
            foreach($_SESSION["cart"] as $product_id => $qty) {
                $product_data = $this->productModel->product_data($product_id);
                $product_data["cart_qty"] = $qty;
                $cart_product_list[] = $product_data;
            }

            return $this->view("cart", "Kosár", ["cart_product_list" => $cart_product_list]);
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