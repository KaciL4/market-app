function debounce(func, delay) {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

async function fetchItems(searchTerm) {
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

        return data.items || [];
    } catch (error) {
        console.error("Fetch error:", error);
        showError("Failed to load items. Please try again.");
        return [];
    }
}

function showError(message) {
    const container = document.getElementById("searchResults");
    container.innerHTML = "";

    const alert = document.createElement("div");
    alert.className = "col-12";
    alert.innerHTML = `<div class="alert alert-danger">${message}</div>`;
    container.appendChild(alert);
}

function renderItems(items) {
    const resultsContainer = document.getElementById("searchResults");
    const defaultContainer = document.getElementById("defaultProducts");

    resultsContainer.innerHTML = "";

    if (defaultContainer) defaultContainer.style.display = "none";

    if (!items.length) {
        resultsContainer.innerHTML = `
            <div class="col-12 text-center text-muted py-4">
                No items found
            </div>
        `;
        return;
    }

    items.forEach((item) => {
        resultsContainer.appendChild(createItemCard(item));
    });
}

function createItemCard(item) {
    const col = document.createElement("div");
    col.className = "col-md-3 col-sm-6";

    const description = item.detail
        ? item.detail.length > 60
            ? item.detail.substring(0, 60) + "..."
            : item.detail
        : "";

    col.innerHTML = `
        <a href="${window.APP_BASE_URL}/items/${item.item_id}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow bg-dark text-white rounded-4 overflow-hidden">

                <img src="${item.file_path ? window.APP_BASE_URL + "/public/" + item.file_path.replace(/^\/+/, "") : "https://placehold.co/600x300/343a40/white?text=No+Image"}"
                    class="card-img-top"
                    style="height:220px;object-fit:cover;">

                <div class="card-body d-flex flex-column px-3 pt-3 pb-4">

                    <h5 class="fw-bold">
                        ${escapeHtml(item.listing_product)}
                    </h5>

                    <p class="small text-secondary">${escapeHtml(description)}</p>

                    <p>Seller: ${escapeHtml(item.username || "")}</p>

                    <div class="mt-auto">
                        <h5 class="text-primary fw-bold mb-0">
                            $${parseFloat(item.price).toFixed(2)}
                        </h5>
                    </div>

                </div>
            </div>
        </a>
    `;

    return col;
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text || "";
    return div.innerHTML;
}

document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("liveSearchInput");
    const resultsContainer = document.getElementById("searchResults");
    const defaultContainer = document.getElementById("defaultProducts");
    const loadingSpinner = document.getElementById("loadingSpinner"); // added

    if (!input || !resultsContainer || !defaultContainer) return;

    const search = debounce(async () => {
        const value = input.value.trim();

        if (!value) {
            resultsContainer.innerHTML = "";
            defaultContainer.style.display = "";
            if (loadingSpinner) loadingSpinner.style.display = "none"; // hide
            return;
        }

        if (loadingSpinner) loadingSpinner.style.display = "inline-block"; // show

        const items = await fetchItems(value);

        if (loadingSpinner) loadingSpinner.style.display = "none"; // hide

        renderItems(items);
    }, 300);

    input.addEventListener("input", search);

    input.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            input.value = "";
            resultsContainer.innerHTML = "";
            defaultContainer.style.display = "";
            if (loadingSpinner) loadingSpinner.style.display = "none"; // hide
        }
    });
});
