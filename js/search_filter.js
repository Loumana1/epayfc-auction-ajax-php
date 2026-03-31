document.addEventListener("DOMContentLoaded", function (){
    const input = document.getElementById("search-input");
    if(!input) return;

    input.addEventListener("input", function () {
        const query = this.value.toLocaleLowerCase().trim();
        const cards = document.querySelectorAll(".item-card")

        cards.forEach(card => {
            const title = (card.dataset.title || "").toLowerCase();
            const seller = (card.dataset.seller || "").toLowerCase();
            const description = (card.dataset.description || "").toLowerCase();

            const match = title.includes(query)
            || seller.includes(query)
            || description.includes(query);

            card.style.display = match ? "" : "none";
            
        })
    })
})