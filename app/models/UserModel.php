<?php

    class UserModel {
        private $db;
        public $permissions = [
            "0" => "basic",
            "1" => "admin"
        ];

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

        public function verifyLoginData(string $login_email, string $login_password) {
            $stmt = $this->db->prepare("SELECT * FROM `user` WHERE `email` LIKE :email");
            $stmt->execute([":email" => $login_email]);
            $user_data = $stmt->fetch();

            if(!$user_data) { // Nincs ilyen e-mail cím
                return false;
            }

            return ($this->hashPassword($login_password, $user_data["password_salt"]) == $user_data["password"])
            ? $user_data : false;
        }

        # Privát belső segédfüggvények:
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