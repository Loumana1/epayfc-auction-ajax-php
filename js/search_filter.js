document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    if (!searchInput) return;

    let debounceTimer;
    const baseUrl = window.location.pathname.replace(/\/index\/.*$/, '/index');

    searchInput.addEventListener("input", function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        debounceTimer = setTimeout(() => {
            fetch(`${baseUrl}?ajax=1&query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    history.replaceState({ query }, "", `${baseUrl}/${data.encoded_state}`);
                    const itemCards = document.querySelectorAll(".item-card");
                    itemCards.forEach(card => {
                        const itemId = parseInt(card.getAttribute("data-id"));

                        if (data.matches.includes(itemId)) {
                            card.style.display = "";
                            const link = card.querySelector("a");
                            if (link) {
                                link.href = `open_item/index/${itemId}/0/${data.encoded_state}`;
                            } else {
                                card.onclick = () => {
                                    window.location = `open_item/index/${itemId}/0/${data.encoded_state}`;
                                };
                            }
                        } else {
                            card.style.display = "none";
                        }
                    });
                    document.querySelectorAll(".items-section").forEach(section => {
                        const visibleCards = section.querySelectorAll(".item-card:not([style*='display: none'])");
                        section.style.display = visibleCards.length > 0 ? "" : "none";
                    });
                })
                .catch(err => console.error("Search error:", err));
        }, 300);
    });
});