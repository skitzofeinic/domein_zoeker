document.addEventListener("DOMContentLoaded", function () {
    let tlds = [];

    /**
     * Add a new TLD to the list if valid and not already present.
     *
     * @returns {void}
     */
    function addTLD() {
        let extension = document.getElementById('extension').value.trim()
            .replace(/^\.|\.+$/g, '')
            .replace(/[^a-zA-Z0-9-]/g, '');

        if (!extension) {
            alert("No extensions found");
            return;
        }

        const extensionInArray = tlds.find(tld => tld.extension === extension);
        if (extensionInArray) {
            alert("already added");
            return;
        }

        tlds.push({extension});
        document.getElementById('extension').value = '';
        addTLDToList(extension);
        console.log('TLDs:', tlds);
    }

    document.getElementById('addExtension').addEventListener('click', function () {
        addTLD();
    });

    document.getElementById('extension').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            addTLD();
        }
    });

    /**
     * Event listener for searching domains when the search button is clicked.
     *
     * This function checks if at least one TLD is added and if the domain name is provided before sending a search
     * request.
     * It sends the domain and selected TLD extensions to the API, then processes and displays the search
     * results upon successful response.
     *
     * @event click
     * @returns {void}
     */
    document.getElementById('search').addEventListener('click', function () {
        if (tlds.length === 0) {
            alert("You must have at least one TLD.");
            return;
        }

        const domainName = document.getElementById('domainName').value.trim();

        if (!domainName) {
            alert("Please enter a domain name.");
            return;
        }

        const data = tlds.map(tld => ({
            name: domainName, extension: tld.extension
        }));

        const jsonData = JSON.stringify(data);
        console.log("Sending data:", jsonData);

        $.ajax({
            url: 'home/search', method: 'POST', contentType: 'application/json', data: jsonData
        })
            .done(function (response) {
                console.log('Raw response:', response);
                try {
                    console.log("type: ", typeof response);
                    const parsedResponse = JSON.parse(response);
                    console.log('Parsed response:', parsedResponse);
                    populateResults(parsedResponse);
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            });
    });

    /**
     * Populates the search results section with domain availability and pricing information.
     *
     * @param {Array} results - Array of domain results, each containing domain name, status, price.
     * @returns {void}
     */
    function populateResults(results) {
        const searchResultsDiv = document.getElementById('searchResults');
        searchResultsDiv.innerHTML = '';

        results.forEach(result => {
            let domainDiv = document.createElement('div');
            domainDiv.classList.add('domain-item');

            let domainName = result.domain;
            let status = result.status;
            let price = result.price.product.price;
            let currency = result.price.product.currency;

            let buttonHtml = '<button>Unavailable</button>';

            if (status === 'free') {
                buttonHtml = `
                <button class="available-btn" 
                    data-domain="${domainName}"
                    data-price="${price}"
                    data-currency="${currency}"
                    data-status="${status}">
                    Buy
                </button>`;
            }
            domainDiv.innerHTML = `
                <strong>${domainName}</strong>
                <div>
                    ${price} ${currency}
                    ${buttonHtml}
                </div>
            `;

            const buyButton = domainDiv.querySelector('.available-btn');
            if (buyButton) {
                buyButton.addEventListener('click', handleBuyButtonClick);
            }
            searchResultsDiv.appendChild(domainDiv);
        });
    }

    /**
     * Adds the TLD to the displayed list in the DOM.
     *
     * @param {string} extension - The TLD extension (e.g., 'com', 'org', etc.).
     * @returns {void}
     */
    function addTLDToList(extension) {
        const tldList = document.getElementById('tldList');
        const listItem = document.createElement('p');
        listItem.textContent = extension;

        const removeButton = document.createElement('button');
        removeButton.textContent = 'x';

        removeButton.addEventListener('click', function () {
            tlds = tlds.filter(tld => tld.extension !== extension);
            tldList.removeChild(listItem);
            console.log('TLDs after removal:', tlds);
        });

        listItem.appendChild(removeButton);
        tldList.appendChild(listItem);
    }

    /**
     * Handles the click event for adding a domain to the cart.
     *
     * @param {Event} event - The event triggered by clicking the "Buy" button for a domain.
     * @returns {void}
     */
    function handleBuyButtonClick(event) {
        const button = event.target;
        const domain = button.getAttribute('data-domain').split('.');

        const domainName = domain[0];
        const extension = domain[1];
        const price = button.getAttribute('data-price');
        const currency = button.getAttribute('data-currency');
        const status = button.getAttribute('data-status');

        const jsonData = JSON.stringify({
            domain: domainName, extension: extension, price: price, currency: currency, status: status,
        });

        button.disabled = true;
        button.textContent = 'In Cart';
        button.classList.add('disabled-btn');

        $.ajax({
            url: 'cart/add', method: 'POST', contentType: 'application/json', data: jsonData
        })
            .done(function (response) {
            });
    }
});
