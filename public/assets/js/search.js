document.addEventListener("DOMContentLoaded", function () {
    let tlds = [];

    // Function to handle adding a TLD (used by both "Add" button and "Enter" key)
    function addTLD() {
        let extension = document.getElementById('extension').value.trim()
            .replace(/^\.|\.+$/g, '')  // Remove leading/trailing dots
            .replace(/[^a-zA-Z0-9-]/g, '');  // Allow only letters, numbers, and hyphens

            if (!extension) {
            alert("No extensions found");
            return;
        }

        const extensionInArray = tlds.find(tld => tld.extension === extension);
        if (extensionInArray) {
            alert("already added");
            return;
        }

        tlds.push({ extension });
        document.getElementById('extension').value = '';
        addTLDToList(extension);  // Add it to the UI list
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
            name: domainName,
            extension: tld.extension
        }));

        const jsonData = JSON.stringify(data);
        console.log("Sending data:", jsonData);

        $.ajax({
            url: 'home/search',
            method: 'POST',
            contentType: 'application/json',
            data: jsonData
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
    function addTLDToList(extension) {
        const tldList = document.getElementById('tldList');
        const listItem = document.createElement('p');
        listItem.textContent = extension;

        // Create a Remove button
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

    function handleBuyButtonClick(event) {
        const button = event.target;
        const domain = button.getAttribute('data-domain').split('.');

        const domainName = domain[0];
        const extension = domain[1];
        const price = button.getAttribute('data-price');
        const currency = button.getAttribute('data-currency');
        const status = button.getAttribute('data-status');

        const jsonData = JSON.stringify({
            domain: domainName,
            extension: extension,
            price: price,
            currency: currency,
            status: status,
        });

        button.disabled = true;
        button.textContent = 'In Cart';
        button.classList.add('disabled-btn');

        $.ajax({
            url: 'cart/add',
            method: 'POST',
            contentType: 'application/json',
            data: jsonData
        })
            .done(function (response) {
            });
    }
});
