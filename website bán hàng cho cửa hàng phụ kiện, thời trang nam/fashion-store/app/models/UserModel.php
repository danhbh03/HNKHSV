<?php
    namespace App\Models;

    use PDO;

    class UserModel {
        protected $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }

        public function findByUsername($username) {
            $stmt = $this->db->prepare('SELECT * FROM login_info WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        public function findByEmail($email) {
            $stmt = $this->db->prepare('SELECT * FROM customer WHERE email = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function updateToken($userId, $token) {
            $stmt = $this->db->prepare('UPDATE login_info SET token = :token WHERE id = :id');
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
        }
    }
?>
