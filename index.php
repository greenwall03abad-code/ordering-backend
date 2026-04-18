<?php
require 'db.php';

$action = $_GET['action'] ?? '';

if ($action === 'init') {
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        emoji VARCHAR(10) NOT NULL,
        description VARCHAR(200) NOT NULL,
        stock TINYINT(1) NOT NULL DEFAULT 1
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        total DECIMAL(10,2) NOT NULL,
        cash DECIMAL(10,2) NOT NULL,
        change_amount DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_name VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        qty INT NOT NULL
    )");

    $count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($count == 0) {
        $products = [
            ['Adobo','Ulam',55,'🍖','Classic pork/chicken adobo'],
            ['Sinigang','Ulam',70,'🍲','Sour tamarind soup'],
            ['Dinakdakan','Ulam',60,'🥩','Grilled pork face'],
            ['Beanspork','Ulam',45,'🫘','Beans with pork'],
            ['Caldereta','Ulam',75,'🍛','Tomato-based beef stew'],
            ['Nilaga','Ulam',65,'🥣','Boiled beef/pork soup'],
            ['Plain Rice','Rice',15,'🍚','Steamed white rice'],
            ['Fried Rice','Rice',25,'🍳','Garlic fried rice'],
            ['Sinangag','Rice',20,'🍙','Filipino garlic rice'],
            ['Iced Tea','Beverage',20,'🧋','Bottled iced tea'],
            ['Water','Beverage',10,'💧','Mineral water'],
            ['Soda','Beverage',25,'🥤','Softdrink assorted'],
            ['Halo-Halo','Dessert',45,'🍧','Mixed Filipino dessert'],
            ['Leche Flan','Dessert',30,'🍮','Creamy caramel custard'],
            ['Lumpia','Snacks',15,'🥟','Spring roll 2pcs'],
            ['Puto','Snacks',10,'🧁','Steamed rice cake 2pcs'],
        ];
        $stmt = $pdo->prepare("INSERT INTO products (name,category,price,emoji,description) VALUES (?,?,?,?,?)");
        foreach ($products as $p) $stmt->execute($p);
    }
    echo json_encode(["success" => true]);
}

elseif ($action === 'get_products') {
    $rows = $pdo->query("SELECT * FROM products ORDER BY category, name")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
}

elseif ($action === 'save_order') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO orders (total,cash,change_amount) VALUES (?,?,?)");
    $stmt->execute([$data['total'], $data['cash'], $data['change']]);
    $orderId = $pdo->lastInsertId();
    $stmt2 = $pdo->prepare("INSERT INTO order_items (order_id,product_name,price,qty) VALUES (?,?,?,?)");
    foreach ($data['items'] as $item) {
        $stmt2->execute([$orderId, $item['name'], $item['price'], $item['qty']]);
    }
    echo json_encode(["success" => true, "order_id" => $orderId]);
}

elseif ($action === 'get_sales') {
    $orders = $pdo->query("SELECT o.*, GROUP_CONCAT(oi.product_name,' x',oi.qty SEPARATOR ', ') as items FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id GROUP BY o.id ORDER BY o.created_at DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
    $total = $pdo->query("SELECT SUM(total) as grand FROM orders")->fetchColumn();
    echo json_encode(["orders" => $orders, "grand_total" => $total]);
}

else {
    echo json_encode(["message" => "Ordering System API running!"]);
}
?>
