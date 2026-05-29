document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    if (!searchInput) return;

    const categorySelect = document.getElementById("category-select");

    const base = document.querySelector("base");
    if (!base) return;
    const baseHref = base.getAttribute("href");
    const controller = location.pathname.includes("my_items") ? "my_items" : "browser";
    const listBase = baseHref + controller + "/index";

    function getCategoryId() {
        return categorySelect ? parseInt(categorySelect.value, 10) : 0;
    }

    const path = location.pathname;
    const rePath = new RegExp("/" + controller + "/index(/([^/]+))?/?$");
    const pathMatch = path.match(rePath);
    const pathToken = pathMatch && pathMatch[2] ? pathMatch[2] : null;

    function setListPageUrlToBase() {
        history.replaceState(null, "", listBase);
    }

    function applyMatchesToCards(data) {
        const cartes = document.querySelectorAll(".item-card");
        for (let i = 0; i < cartes.length; i++) {
            const id = parseInt(cartes[i].getAttribute("data-id"), 10);
            if (data.matches.indexOf(id) === -1) {
                cartes[i].style.display = "none";
            } else {
                cartes[i].style.display = "";
            }
        }
    }

    function setOpenItemLinksOnCards(encodedState) {
        if (!encodedState) return;
        const cartes = document.querySelectorAll(".item-card");
        for (let j = 0; j < cartes.length; j++) {
            const id = cartes[j].getAttribute("data-id");
            const a = cartes[j].querySelector("a[href^='open_item/']");
            if (a) {
                a.href = "open_item/index/" + id + "/" + encodedState + "/0";
            } else {
                cartes[j].setAttribute("onclick", "location.href='open_item/index/" + id + "/" + encodedState + "/0'");
            }
        }
    }

    function replaceMyItemsBelowSearch(nPage) {
        const curPage = document.querySelector(".my-items-page");
        if (!curPage || !nPage) return false;
        const sbar = curPage.querySelector(".search-bar");
        const sbarF = nPage.querySelector(".search-bar");
        if (!sbar || !sbarF) return false;
        let n = sbar.nextElementSibling;
        while (n) {
            const t = n.nextElementSibling;
            n.remove();
            n = t;
        }
        n = sbarF.nextElementSibling;
        while (n) {
            const t = n.nextElementSibling;
            curPage.appendChild(n);
            n = t;
        }
        return true;
    }

    function replaceListPageFromHtml(html) {
        const doc = (new DOMParser()).parseFromString(html, "text/html");
        if (replaceMyItemsBelowSearch(doc)) {
            return;
        }
        const newMy = doc.querySelector(".my-items-page");
        const curMy = document.querySelector(".my-items-page");
        if (newMy && curMy) {
            curMy.innerHTML = newMy.innerHTML;
            return;
        }
        [".participating .item-list", ".available .item-list", ".items-section .item-list"].forEach(
            function (s) {
                const listeActuelle = document.querySelector(s);
                const nouvelleListe = doc.querySelector(s);
                if (listeActuelle && nouvelleListe) {
                    listeActuelle.innerHTML = nouvelleListe.innerHTML;
                }
            }
        );
    }

    function buildAjaxUrl(query, categoryId) {
        let url = listBase + "?ajax=1&query=" + encodeURIComponent(query);
        if (categoryId > 0) {
            url += "&category=" + categoryId;
        }
        return url;
    }

    function doFilter() {
        const query = searchInput.value.trim();
        const categoryId = getCategoryId();
        setListPageUrlToBase();

        if (query === "" && categoryId === 0) {
            fetch(listBase)
                .then(function (r) { return r.text(); })
                .then(replaceListPageFromHtml);
            return;
        }

        fetch(buildAjaxUrl(query, categoryId))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                applyMatchesToCards(data);
                setOpenItemLinksOnCards(data.encoded_state);
            });
    }

    (function initFromServerState() {
        const q = searchInput.value.trim();
        const categoryId = getCategoryId();
        if ((q !== "" || categoryId > 0) && !pathToken) {
            fetch(buildAjaxUrl(q, categoryId))
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    applyMatchesToCards(data);
                    setOpenItemLinksOnCards(data.encoded_state);
                });
            return;
        }
        if (q === "" && categoryId === 0 && pathToken) {
            setListPageUrlToBase();
            fetch(listBase)
                .then(function (r) { return r.text(); })
                .then(replaceListPageFromHtml);
        }
    })();

    searchInput.addEventListener("input", doFilter);

    if (categorySelect) {
        categorySelect.addEventListener("change", doFilter);
    }

    addEventListener("pageshow", function () {
        const q = searchInput.value.trim();
        const categoryId = getCategoryId();
        if (q === "" && categoryId === 0) return;
        fetch(buildAjaxUrl(q, categoryId))
            .then(function (r) { return r.json(); })
            .then(applyMatchesToCards);
    });
});
