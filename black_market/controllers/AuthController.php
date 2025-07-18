<?php
class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function login() {
        if (isLoggedIn()) {
            redirect("index.php");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->user->login($username, $password)) {
                $_SESSION['user_id'] = $this->user->user_id;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['email'] = $this->user->email;
                
                redirect("index.php");
            } else {
                $error = "Username atau password salah!";
            }
        }

        include 'views/auth/login.php';
    }

    public function register() {
        if (isLoggedIn()) {
            redirect("index.php");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'] ?? '';
            $this->user->password = $_POST['password'] ?? '';
            $this->user->email = $_POST['email'] ?? '';

            if (empty($this->user->username) || empty($this->user->password) || empty($this->user->email)) {
                $error = "Semua field harus diisi!";
            } elseif ($this->user->usernameExists($this->user->username)) {
                $error = "Username sudah digunakan!";
            } else {
                if ($this->user->register()) {
                    $success = "Registrasi berhasil! Silakan login.";
                } else {
                    $error = "Registrasi gagal!";
                }
            }
        }

        include 'views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        redirect("index.php?controller=auth&action=login");
    }
}
?>