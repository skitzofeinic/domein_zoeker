document.addEventListener("DOMContentLoaded", function () {
    const removeOrderButton = document.querySelectorAll('.remove-order-btn');

    removeOrderButton.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-order-id');

            if (!id) {
                alert("Invalid order ID");
                return;
            }

            const jsonData = JSON.stringify({
                id: id
            });

            $.ajax({
                url: 'order/remove-order',
                method: 'POST',
                contentType: 'application/json',
                data: jsonData,
                success: function () {
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the order.');
                }
            });
        });
    });
});
