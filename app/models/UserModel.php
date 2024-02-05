<?php

    class UserModel {
        private $db;

        public function __construct(object $db) {
            $this->db = $db;
        }

        public function isEmailUnique(string $email) {
            $stmt = $this->db->prepare("SELECT email FROM user WHERE email like ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $result = $stmt->fetch();
            return ($result) ? false : true;
        }

        public function insertNewUser(array $data) {
            $password_salt = $this->generateSalt();
            $password = $this->hashPassword($data["password"], $password_salt);

            $stmt = $this->db->prepare("INSERT INTO `user`(`email`, `password`, `password_salt`, `full_name`) VALUES (:email, :password, :password_salt, :full_name)");

            $email = $data["email"];
            $full_name = $data["full_name"];

            $stmt->execute([
                ":email" => $email,
                ":password" => $password,
                ":password_salt" => $password_salt,
                ":full_name" => $full_name,
            ]);

            return ($stmt->errorCode() == "00000");
        }

        private function generateSalt() {
            $char_list = "abcdefghijklmnopqrstuvwxyz0123456789";
            // $char_list[3]; // d

            $salt = "";
            for($i=0; $i<20; $i++) {
                $salt .= $char_list[rand(0, strlen($char_list)-1)];
            }

            return $salt;
        }

        private function hashPassword(string $password, string $salt) {
            return hash("sha512", $password.md5($salt));
        }
    }

?>