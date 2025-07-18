<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Black Market</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        nav {
            background-color: #2c2c2c;
            padding: 15px 0;
            margin-bottom: 30px;
        }
        
        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        nav li {
            position: relative;
        }
        
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        nav a:hover {
            background-color: #444;
        }
        
        .card {
            background-color: #2c2c2c;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        
        .order-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .order-section {
            background-color: #2c2c2c;
            border-radius: 8px;
            padding: 20px;
        }
        
        .order-section h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }
        
        .order-item {
            background-color: #3c3c3c;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .order-item h3 {
            color: #FFC107;
            margin-bottom: 10px;
        }
        
        .order-details {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #ccc;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            background-color: #3c3c3c;
            border: 1px solid #555;
            border-radius: 5px;
            color: #fff;
        }
        
        button, .btn {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        
        button:hover, .btn:hover {
            background-color: #45a049;
        }
        
        .btn-delete {
            background-color: #f44336;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .btn-delete:hover {
            background-color: #d32f2f;
        }
        
        .btn-chat {
            background-color: #2196F3;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .btn-chat:hover {
            background-color: #1976D2;
        }
        
        .chat-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .chat-messages {
            background-color: #2c2c2c;
            border-radius: 8px;
            padding: 20px;
            height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        
        .message.sent {
            background-color: #4CAF50;
            text-align: right;
            margin-left: 20%;
        }
        
        .message.received {
            background-color: #3c3c3c;
            text-align: left;
            margin-right: 20%;
        }
        
        .message-time {
            font-size: 12px;
            color: #aaa;
        }
        
        .chat-input {
            display: flex;
            gap: 10px;
        }
        
        .chat-input textarea {
            flex: 1;
            resize: none;
        }
        
        .conversation-item {
            background-color: #3c3c3c;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .conversation-item:hover {
            background-color: #4c4c4c;
        }
        
        .unread-badge {
            background-color: #f44336;
            color: #fff;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 12px;
            position: absolute;
            top: -5px;
            right: -10px;
            min-width: 20px;
            text-align: center;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-error {
            background-color: #f44336;
            color: #fff;
        }
        
        .alert-success {
            background-color: #4CAF50;
            color: #fff;
        }
        
        .login-container {
            max-width: 400px;
            margin: 50px auto;
        }
        
        .wts { border-left: 4px solid #FF5722; }
        .wtb { border-left: 4px solid #2196F3; }
        
        @media (max-width: 768px) {
            .order-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <nav>
        <div class="container">
            <ul>
                <li><a href="<?= BASE_URL ?>index.php">Beranda</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=order&action=create">Buat Order</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=chat">Chat</a>
                <li><a href="<?= BASE_URL ?>index.php?controller=contract">Contract</a></li>
                    <?php
                        $database = new Database();
                        $db = $database->getConnection();
                        $chat = new Chat($db);
                        $unread = $chat->getUnreadCount($_SESSION['user_id']);
                        if ($unread > 0): 
                    ?>
                        <span class="unread-badge"><?= $unread ?></span>
                    <?php endif; ?>
                </li>
                <?php if ($_SESSION['username'] === 'admin'): ?>

                    <li><a href="<?= BASE_URL ?>index.php?controller=admin" style="background-color: #f44336;">🛡️ Admin Panel</a></li>
                <?php endif; ?>
                <li style="margin-left: auto;">
                    <span style="margin-right: 20px;">Hai, <?= $_SESSION['username'] ?></span>
                    <a href="<?= BASE_URL ?>index.php?controller=auth&action=logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <?php endif; ?>
       
    <div class="container">