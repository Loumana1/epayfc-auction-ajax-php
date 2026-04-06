document.addEventListener("DOMContentLoaded", function (){
    const input = document.getElementById("search-input");
    if(!input) return;

    const urlParams = new URLSearchParams(window.Location.search);
    const initialQuery = urlParams.get("q") || "";
    if(initialQuery) {
        input.value = initialQuery ;
        filterCards(initialQuery);
    }

    input.addEventListener("input", function () {
        const query = this.value.trim();
        filterCards(query);

        //mettre a jour l url sans recharge la page
        const url = new URL(window.location.href);
        if (query) {
            url.searchParams.set("q", query);
        } else {
            url.searchParams.delete("q");
        }
        // replaceState au lieu de pushState pour ne pas polluer l historique
        window.history.replaceState({} ,"",url);
    });

    function filterCards(query) {
        const q = query.toLowerCase();
        const cards = document.querySelectorAll(".item-card");

        cards.forEach(card => {
            const title = (card.dataset.title || "").toLowerCase();
            const seller = (card.dataset.seller || "").toLowerCase();
            const description = (card.dataset.description || "").toLowerCase();

            const match = !q
            || title.includes(q)
            || seller.includes(q)
            || description.includes(q);

            card.style.display = match ? "" : "none";       
        })
    }
    });