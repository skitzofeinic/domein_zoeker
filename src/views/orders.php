<?php
/** @var array $orders */
/** @var array $orderItems */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders - Domein Zoeker</title>
    <script src="/domein_zoeker/public/assets/js/order.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="public/assets/css/main.css">
    <link rel="stylesheet" href="public/assets/css/order.css">
</head>
<body>
<div class="container">
    <?php foreach ($orders as $order): ?>
        <div class="order">
            <div class="left">
                <div class="order-header">
                    <h3>Order number: <?= htmlspecialchars($order['id']); ?></h3>
                    <p>Date: <?= htmlspecialchars($order['created_at']); ?></p>
                </div>
                <div class="order-cost">
                    <p>Subtotal: <?= htmlspecialchars($order['subtotal']); ?> EUR</p>
                    <p>VAT: <?= htmlspecialchars($order['vat']); ?> EUR</p>
                    <p>Total: <?= htmlspecialchars($order['total']); ?> EUR</p>
                </div>
                <button class="remove-order-btn" type="button" data-order-id="<?= htmlspecialchars($order['id']); ?>">
                    Remove
                </button>
            </div>
            <div class="order-item-list">
                <h3>Domains</h3>
                <?php foreach ($orderItems[$order['id']] as $item): ?>
                    <div class="order-item">
                        <p>
                            <?= htmlspecialchars($item['domain']) . '.' . htmlspecialchars($item['extension']); ?>
                        </p>
                        <p>
                            <?= htmlspecialchars($item['price']); ?> EUR
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>
</body>
</html>
