<?php

    class OrderModel {
        private $db;

        public function __construct(object $db) {
            $this->db = $db;
        }

        public function insert_order(string $address, string $tel, array $cart_product_list) {
            # Rendelés beszúrás
            // Egyedi public_id generálás
            $public_id = "";
            do {
                $public_id = $this->generatePublicId();
            } while($this->getOrderByPublicId($public_id));

            // Rendelés beszúrása
            $stmt = $this->db->prepare("INSERT INTO `order`(`public_id`, `user`, `address`, `tel`)
            VALUES (:public_id, :user, :address, :tel)");

            $user_id = $_SESSION["user"]["id"];

            $stmt->execute([
                ":public_id" => $public_id,
                ":user" => $user_id,
                ":address" => $address,
                ":tel" => $tel
            ]);

            if($stmt->errorCode() != "00000") {
                return false;
            }

            // A létrejött rendelés rekord egyedi azonosítóját lekérem a public_id alapján
            $order = $this->getOrderByPublicId($public_id);
            if(!$order) {
                return false;
            }

            # Tételek beszúrása
            foreach($cart_product_list as $cart_product) {
                $stmt = $this->db->prepare("INSERT INTO `order_item`(`order`, `product`, `qty`, `price`)
                VALUES (:order, :product, :qty, :price)");
                
                $order_id = $order["id"];
                $product_id = $cart_product["id"];
                $qty = $cart_product["cart_qty"];
                $price = $cart_product["price"];
                
                $stmt->execute([
                    ":order" => $order_id,
                    ":product" => $product_id,
                    ":qty" => $qty,
                    ":price" => $price,
                ]);

                if($stmt->errorCode() != "00000") {
                    return false;
                }
            }

            # Ha idáig eljut a kód, akkor nem volt hiba -> visszatérés true értékkel
            return true;
        }

        # Privát belső segédfüggvények:
        private function generatePublicId() {
            $char_list = "abcdefghijklmnopqrstuvwxyz0123456789";
            // $char_list[3]; // d

            $salt = "";
            for($i=0; $i<6; $i++) {
                $salt .= $char_list[rand(0, strlen($char_list)-1)];
            }

            return $salt;
        }

        private function getOrderByPublicId(string $public_id) {
            $stmt = $this->db->prepare("SELECT * FROM `order` WHERE `public_id` LIKE :public_id");
            $stmt->execute([":public_id" => $public_id]);
            return $stmt->fetch();
        }
    }

?>