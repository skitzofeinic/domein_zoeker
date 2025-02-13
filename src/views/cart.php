<?php
/** @var float $subtotal */
/** @var float $tax */
/** @var float $total */
/** @var array $cart_items */
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Domein Zoeker</title>
    <script src="/domein_zoeker/public/assets/js/cart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="public/assets/css/main.css">
    <link rel="stylesheet" href="public/assets/css/cart.css">
</head>
<body>
<div class="container">
    <div class="item-side">
        <div class="item-side-header">
            <h2>Your Cart</h2>
            <?php if ($cart_items): ?>
                <button type="button" id="clear-cart">Clear cart</button>
            <?php endif; ?>
        </div>

        <div class="items">
            <?php foreach ($cart_items as $cart_item): ?>
                <?php if ($cart_item): ?>
                    <div class="domain-item">
                        <button class="remove-btn" data-domain="<?= htmlspecialchars($cart_item['domain']) ?>"
                                data-extension="<?= htmlspecialchars($cart_item['extension']) ?>">
                            Remove
                        </button>
                        <strong><?= htmlspecialchars($cart_item['domain'] . '.' . $cart_item['extension']) ?></strong>
                        <span><?= number_format($cart_item['price'], 2) ?> <?= htmlspecialchars($cart_item['currency']) ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="checkout-side">
        <h2>Order Summary</h2>

        <?php if ($cart_items): ?>
            <div id="totals">
                <p><strong>Subtotal:</strong> <?= number_format($subtotal, 2) ?> <?= htmlspecialchars($cart_items[0]['currency']) ?></p>
                <p><strong>VAT:</strong> <?= number_format($tax, 2) ?> <?= htmlspecialchars($cart_items[0]['currency']) ?></p>
                <p><strong>Total:</strong> <?= number_format($total, 2) ?> <?= htmlspecialchars($cart_items[0]['currency']) ?></p>
            </div>

            <button id="checkout-button">Proceed to Checkout</button>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
