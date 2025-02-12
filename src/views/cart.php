<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Search</title>
    <link rel="stylesheet" href="public/assets/css/main.css">
    <link rel="stylesheet" href="public/assets/css/cart.css">
</head>
<body>
<div class="container">
    <div class="item-side">
        <h2>Your Cart</h2>

        <div class="items">
            <div class="domain-item">
                <strong>example1.com</strong>
                <button class="remove-btn" data-domain="example1.com" data-price="10" data-currency="USD"
                        data-status="free">Remove
                </button>
                <span>10 USD</span>
            </div>

            <div class="domain-item">
                <strong>example2.net</strong>
                <button class="remove-btn" data-domain="example2.net" data-price="15" data-currency="USD"
                        data-status="free">Remove
                </button>
                <span>15 USD</span>
            </div>

            <div class="domain-item">
                <strong>example3.org</strong>
                <button class="remove-btn" data-domain="example3.org" data-price="12" data-currency="USD"
                        data-status="free">Remove
                </button>
                <span>12 USD</span>
            </div>

            <div class="domain-item">
                <strong>example4.io</strong>
                <button class="remove-btn" data-domain="example4.io" data-price="20" data-currency="USD"
                        data-status="free">Remove
                </button>
                <span>20 USD</span>
            </div>
        </div>
    </div>

    <div class="checkout-side">
        <h2>Order Summary (4 Items)</h2>

        <div id="totals">
            <p><strong>Subtotal:</strong> 22 USD</p>
            <p><strong>Tax (10%):</strong> 2.2 USD</p>
            <p><strong>Total:</strong> 24.2 USD</p>
        </div>
        <button id="checkout-button">Proceed to Checkout</button>
    </div>
</div>
</body>
</html>
