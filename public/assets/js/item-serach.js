function debounce(func, delay) {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

async function fetchProducts(searchTerm) {
    try {
        const params = new URLSearchParams();
        if (searchTerm) params.append("q", searchTerm);

        const response = await fetch(
            `${window.APP_BASE_URL}/api/items/search?${params.toString()}`,
        );

        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }

        const data = await response.json();
        return data.products || [];
    } catch (error) {
        console.error("Fetch error:", error);
        showError("Failed to load items. Please try again.");
        return [];
    }
}

function showError(message) {
    const container = document.getElementById("searchResults");
    container.textContent = "";

    const alert = document.createElement("div");
    alert.className = "col-12";
    alert.innerHTML = `<div class="alert alert-danger">${message}</div>`;

    container.appendChild(alert);
}

function renderProducts(products) {
    const resultsContainer = document.getElementById("searchResults");
    const defaultContainer = document.getElementById("defaultProducts");

    if (defaultContainer) defaultContainer.style.display = "none";

    resultsContainer.textContent = "";

    if (products.length === 0) {
        resultsContainer.innerHTML = `<div class="col-12 text-muted text-center py-4">No items found</div>`;
        return;
    }

    products.forEach((product) => {
        resultsContainer.appendChild(createProductCard(product));
    });
}

function createProductCard(product) {
    const col = document.createElement("div");
    col.className = "col-md-3 col-sm-6";

    const description =
        product.detail && product.detail.length > 100
            ? product.detail.substring(0, 100) + "..."
            : product.detail || "";

    col.innerHTML = `
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">${escapeHtml(product.listing_product)}</h5>
                <p class="card-text">${escapeHtml(description)}</p>
                <p class="fw-bold text-success">$${parseFloat(product.price).toFixed(2)}</p>
                <span class="badge bg-secondary">${escapeHtml(product.category_name || "Uncategorized")}</span>
            </div>
        </div>
    `;

    return col;
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const searchResults = document.getElementById("searchResults");

    if (!searchInput || !searchResults) return;

    async function performSearch() {
        const searchTerm = searchInput.value.trim();

        // if empty → show default PHP list again
        if (!searchTerm) {
            searchResults.innerHTML = "";
            const defaultContainer = document.getElementById("defaultProducts");
            if (defaultContainer) defaultContainer.style.display = "block";
            return;
        }

        const products = await fetchProducts(searchTerm);
        renderProducts(products);
    }

    const debouncedSearch = debounce(performSearch, 300);

    searchInput.addEventListener("input", debouncedSearch);

    searchInput.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            searchInput.value = "";
            searchResults.innerHTML = "";

            const defaultContainer = document.getElementById("defaultProducts");
            if (defaultContainer) defaultContainer.style.display = "block";
        }
    });

    if (searchInput.value.trim()) {
        performSearch();
    }
});
