<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Domein Zoeker</title>
    <script src="/domein_zoeker/public/assets/js/search.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="public/assets/css/main.css">
    <link rel="stylesheet" href="public/assets/css/search.css">
</head>
<body>
<a id="orders" href="order">Orders</a>
<a id="cart" href="cart">Cart</a>

<div class="container">
    <div class="hero">
        <h1>Domain Search</h1>

        <form id="form">
            <input type="text" id="domainName" name="domainName" placeholder="Enter domain name" required>
            <input type="text" id="extension" name="extension" placeholder="Enter extension" required>
            <button type="button" id="addExtension">add</button>
        </form>
        <button type="submit" id="search">Search</button>
    </div>
    <div id="tldList">
    </div>

    <div id="searchResults">
    </div>
</div>

</body>
</html>
