<?php
require 'db.php';

$orders = $pdo->query("
    SELECT o.id, o.total, o.cash, o.change_amount, o.created_at,
           GROUP_CONCAT(oi.product_name,' x',oi.qty SEPARATOR ', ') as items
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    GROUP BY o.id
    ORDER BY o.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

$grand = $pdo->query("SELECT SUM(total) FROM orders")->fetchColumn();
$count = count($orders);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sales Dashboard</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: sans-serif; background: #f4f6f9; padding: 24px; color: #333; }
  h1 { font-size: 22px; font-weight: 600; margin-bottom: 20px; color: #1a1a2e; }
  .cards { display: flex; gap: 16px; margin-bottom: 24px; }
  .card { background: white; border-radius: 12px; padding: 20px 24px; flex: 1;
          box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
  .card .label { font-size: 13px; color: #888; margin-bottom: 6px; }
  .card .value { font-size: 28px; font-weight: 700; color: #1a1a2e; }
  .card.green .value { color: #0f6e56; }
  table { width: 100%; border-collapse: collapse; background: white;
          border-radius: 12px; overflow: hidden;
          box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
  thead { background: #1a1a2e; color: white; }
  th { padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 500; }
  td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid #f0f0f0; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #f9fafb; }
  .badge { display: inline-block; background: #e1f5ee; color: #0f6e56;
           padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
</style>
</head>
<body>
<h1>Sales Dashboard</h1>

<div class="cards">
  <div class="card">
    <div class="label">Total Orders</div>
    <div class="value"><?= $count ?></div>
  </div>
  <div class="card green">
    <div class="label">Grand Total Sales</div>
    <div class="value">₱<?= number_format($grand, 2) ?></div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Items Ordered</th>
      <th>Total</th>
      <th>Cash</th>
      <th>Change</th>
      <th>Date & Time</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($orders as $o): ?>
    <tr>
      <td><strong><?= $o['id'] ?></strong></td>
      <td><?= htmlspecialchars($o['items']) ?></td>
      <td><span class="badge">₱<?= number_format($o['total'], 2) ?></span></td>
      <td>₱<?= number_format($o['cash'], 2) ?></td>
      <td>₱<?= number_format($o['change_amount'], 2) ?></td>
      <td><?= $o['created_at'] ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</body>
</html>
