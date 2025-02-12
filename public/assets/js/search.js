document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('search').addEventListener('submit', function (e) {
        e.preventDefault();

        const domainName = document.getElementById('domainName').value;
        const extension = document.getElementById('extension').value;

        const data = {
            domainName: domainName, extension: extension
        };

        const jsonData = JSON.stringify(data);
        console.log("Sending data:", jsonData);

        $.ajax({
            url: '/domein_zoeker/search', method: 'POST', contentType: 'application/json', data: jsonData
        })
            .done(function (response) {
                response = JSON.parse(response);
                console.log('Search results:', response);
                populateResults(response[0]);
            })
            .fail(function (xhr, status, error) {
                console.error('Error:', error); // Handle errors
            });
        function populateResults(result) {
            const searchResultsDiv = document.getElementById('searchResults');
            searchResultsDiv.innerHTML = ''; // Clear any previous results

            let domainDiv = document.createElement('div');
            let domainName = result.domain;
            let status = result.status;
            let price = result.price.product.price;
            let currency = result.price.product.currency;

            let buttonHtml = '<button>Buy</button>'; // Default Buy button

            if (status !== 'free') {
                buttonHtml = `<button disabled>Unavailable</button>`; // Disable button if not free
            }

            domainDiv.innerHTML = `
                <strong>${domainName}</strong>
                <div>
                    ${price} ${currency}
                    ${buttonHtml}
                </div>
            `;
            searchResultsDiv.appendChild(domainDiv);
        }
    });
});
