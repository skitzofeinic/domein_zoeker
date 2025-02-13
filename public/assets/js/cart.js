document.addEventListener("DOMContentLoaded", function () {
    const clearCartButton = document.getElementById('clear-cart');
    const checkoutButton = document.getElementById('checkout-button');

    if (clearCartButton) {
        clearCartButton.addEventListener('click', function () {
            fetch('cart/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({action: 'clear_cart'}),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while clearing the cart.');
                });
        });
    }


    const removeButtons = document.querySelectorAll('.remove-btn');
    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const domain = this.getAttribute('data-domain');
            const extension = this.getAttribute('data-extension');

            const jsonData = JSON.stringify({
                domain: domain,
                extension: extension,
            });

            $.ajax({
                url: 'cart/remove',
                method: 'POST',
                contentType: 'application/json',
                data: jsonData,
                success: function () {
                    location.reload();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });

    if (checkoutButton) {
        checkoutButton.addEventListener('click', function () {
            $.ajax({
                url: 'order/place-order',
                method: 'POST',
                success: function () {
                    alert("Order has been placed");
                    window.location.href = 'order';
                },
                error: function (error) {
                    console.error(error);
                }
            })
        })
    }
});
