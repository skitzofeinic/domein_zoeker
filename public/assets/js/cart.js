document.addEventListener("DOMContentLoaded", function () {
    const clearCartButton = document.getElementById('clear-cart');
    const checkoutButton = document.getElementById('checkout-button');

    /**
     * Clears the cart when the "clear-cart" button is clicked.
     * Sends a POST request to clear the cart on the server.
     *
     * @param {Event} event - The event triggered by the "clear-cart" button click.
     */
    if (clearCartButton) {
        clearCartButton.addEventListener('click', function () {
            fetch('cart/clear', {
                method: 'POST', headers: {
                    'Content-Type': 'application/json',
                }, body: JSON.stringify({action: 'clear_cart'}),
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

    /**
     * Removes an item from the cart when a "remove-btn" button is clicked.
     * Sends a POST request to remove the specific domain and extension from the cart.
     *
     * @param {Event} event - The event triggered by the "remove-btn" button click.
     * @param {string} domain - The domain name to be removed.
     * @param {string} extension - The extension of the domain to be removed.
     */
    const removeButtons = document.querySelectorAll('.remove-btn');
    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const domain = this.getAttribute('data-domain');
            const extension = this.getAttribute('data-extension');

            const jsonData = JSON.stringify({
                domain: domain, extension: extension,
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

    /**
     * Places an order when the "checkout-button" is clicked.
     * Sends a POST request to place the order and redirects to the order page.
     *
     * @param {Event} event - The event triggered by the "checkout-button" click.
     */
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function () {
            $.ajax({
                url: 'order/place-order', method: 'POST', success: function () {
                    alert("Order has been placed");
                    window.location.href = 'order';
                }, error: function (error) {
                    console.error(error);
                }
            })
        })
    }
});
