<?php

    class ProductModel {
        private $db;
        private $allowed_fields = ["category", "brand", "type", "min_price", "max_price"];

        public function __construct(object $db) {
            $this->db = $db;
        }

        public function product_list(array $filters) {
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
            ON product.category=category.id
            WHERE `status` = 1";

            $sql_query_filter_part = "";
            $stmt_params = [];

            foreach($filters as $field => $value) {
                if(in_array($field, $this->allowed_fields)) {
                    switch($field) {
                        case "type":
                            $sql_query_filter_part .= " AND product.$field LIKE :$field";
                            $stmt_params[":$field"] = '%'.$value.'%';
                            break;
                        case "min_price":
                            $sql_query_filter_part .= " AND product.price >= :$field";
                            $stmt_params[":$field"] = $value;
                            break;
                        case "max_price":
                            $sql_query_filter_part .= " AND product.price <= :$field";
                            $stmt_params[":$field"] = $value;
                            break;
                        default:
                            $sql_query_filter_part .= " AND product.$field = :$field";
                            $stmt_params[":$field"] = $value;
                    }
                }
            }

            $stmt = $this->db->prepare($sql_query.$sql_query_filter_part);

            $stmt->execute($stmt_params);
            $product_list = $stmt->fetchAll();

            foreach($product_list as $index => $product) {
                $product_list[$index]["formatted_price"] = number_format($product["price"], 0, "", " ")." Ft";
            }

            return $product_list;
        }

        public function product_data(int $id) {
            $sql_query = "SELECT
            product.id,
            product.category AS category_id,
            category.name AS category_name,
            product.brand,
            product.type,
            product.price,
            product.img,
            product.description
            FROM product INNER JOIN category
            ON product.category=category.id
            WHERE product.id = :product_id
            AND `status` = 1";
            $stmt = $this->db->prepare($sql_query);
            $stmt->execute([":product_id" => $id]);
            $product_data = $stmt->fetch();
            
            if($product_data) {
                $product_data["formatted_price"] = number_format($product_data["price"], 0, "", " ")." Ft";
            }
            
            return $product_data;
        }

        public function category_list() {
            $stmt = $this->db->query("SELECT * FROM category");
            return $stmt->fetchAll();
        }

        public function brand_list() {
            $stmt = $this->db->query("SELECT DISTINCT brand FROM product WHERE `status` = 1 ORDER BY brand ASC");
            return $stmt->fetchAll();
        }

        public function cart_data() {
            $cart_product_list = [];
            $cart_total_price = 0;
            foreach($_SESSION["cart"] as $product_id => $qty) {
                $product_data = $this->product_data($product_id);
                $product_data["cart_qty"] = $qty;
                $product_data["subtotal_price"] = number_format($product_data["price"]*$qty, 0, "", " ")." Ft";
                $cart_product_list[] = $product_data;
                $cart_total_price += $product_data["price"]*$qty;
            }

            return [
                "cart_product_list" => $cart_product_list,
                "cart_total_price" => number_format($cart_total_price, 0, "", " ")." Ft"
            ];
        }

        # Admin folyamatok
        public function delete(int $product_id) {
            $stmt = $this->db->prepare("UPDATE `product` SET `status` = 0 WHERE `id` = :product_id");
            $stmt->execute([":product_id" => $product_id]);
            return ($stmt->errorCode() == "00000");
        }

        public function insert(array $data) {
            $stmt = $this->db->prepare("INSERT INTO product(category, brand, type, price, description, img)
            VALUES (:category, :brand, :type, :price, :description, :img)");

            $category = $data["category"];
            $brand = $data["brand"];
            $type = $data["type"];
            $price = $data["price"];
            $description = $data["description"];
            $img = $data["img"];
            
            $stmt->execute([
                ":category" => $category,
                ":brand" => $brand,
                ":type" => $type,
                ":price" => $price,
                ":description" => $description,
                ":img" => $img,
            ]);

            return ($stmt->errorCode() == "00000");
        }
    }

?>