<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Domain Search</title>
    <script src="/domein_zoeker/public/assets/js/search.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h1>Domain Search</h1>

<form id="form">
    <input type="text" id="domainName" name="domainName" placeholder="Enter domain name" required>
    <input type="text" id="extension" name="extension" placeholder="Enter extension" required>
    <button type="submit" id="addExtension">add</button>
</form>
    <button type="submit" id="search">Search</button>

<div id="searchResults">
<!--    mock data i want to populate make me a func-->
    <div>
        Example.com
        <div>
            15.00
            <button>Buy</button>
        </div>
    </div>
</div>

</body>
</html>
