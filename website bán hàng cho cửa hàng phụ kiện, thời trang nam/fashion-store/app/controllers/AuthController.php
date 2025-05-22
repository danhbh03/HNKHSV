<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Models\UserModel;
use Slim\Views\PhpRenderer;
use PDO;

class AuthController
{
    protected PhpRenderer $view;
    protected PDO $db;

    public function __construct(PhpRenderer $view, PDO $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    public function signup(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $fullname = $data['fullname'] ?? '';
        $email = $data['email'] ?? '';
        $retype_password = $data['retype_password'] ?? ''; 

        if (empty($username) || empty($password) || empty($fullname) || empty($email)) {
            return $this->view->render($response, 'signup.php', ['error' => 'Vui lòng điền đầy đủ thông tin.']);
        }

        if (strlen($password) < 6) {
            return $this->view->render($response, 'signup.php', ['error' => 'Mật khẩu phải có ít nhất 6 ký tự.']);
        }

        if (!preg_match('/[A-Za-z]/', $password)) {
            return $this->view->render($response, 'signup.php', ['error' => 'Mật khẩu phải chứa ít nhất một chữ cái.']);
        }

        if (!preg_match('/[0-9]/', $password)) {
            return $this->view->render($response, 'signup.php', ['error' => 'Mật khẩu phải chứa ít nhất một số.']);
        }

        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return $this->view->render($response, 'signup.php', ['error' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.']);
        }
        
        if ($password !== $retype_password) {
            return $this->view->render($response, 'signup.php', ['error' => 'Mật khẩu không khớp.']);
        }

        $userModel = new UserModel($this->db);
        if ($userModel->findByUsername($username)) {
            return $this->view->render($response, 'signup.php', ['error' => 'Tên người dùng đã tồn tại.']);
        }
        if ($userModel->findByEmail($email)) {
            return $this->view->render($response, 'signup.php', ['error' => 'Email đã tồn tại.']);
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare('INSERT INTO customer (fullname, email) VALUES (:fullname, :email)');
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $userId = $this->db->lastInsertId();

        $stmt = $this->db->prepare('INSERT INTO login_info (id, username, password_hash) VALUES (:id, :username, :password_hash)');
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $hashedPassword);
        $stmt->execute();

        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}