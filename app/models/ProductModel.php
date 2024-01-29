<?php

    class ProductModel {
        private $db;

        public function __construct(object $db) {
            $this->db = $db;
        }

        public function product_list() {
            // TODO: filtering and searching
            $sql_query = "SELECT
            product.id,
            product.category AS category_id,
            category.name AS category_name,
            product.brand,
            product.type,
            product.price,
            product.img
            FROM product INNER JOIN category
            ON product.category=category.id";

            $stmt = $this->db->prepare($sql_query);
            // bindParam
            $stmt->execute();
            $product_list = $stmt->fetchAll();

            foreach($product_list as $index => $product) {
                $product_list[$index]["formatted_price"] = number_format($product["price"], 0, "", " ")." Ft";
            }

            return $product_list;
        }
    }

?>