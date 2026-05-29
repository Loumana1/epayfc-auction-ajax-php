document.addEventListener("DOMContentLoaded", function () {
    var base = document.querySelector("base");
    if (!base) return;
    var baseHref = base.getAttribute("href");

    var list = document.getElementById("categories-list");
    if (!list) return;

    list.closest(".manage-categories-page").classList.add("js-mode");
    document.querySelectorAll(".category-row .field-error").forEach(function (el) { el.remove(); });

    // Utilitaires

    function debounce(fn, delay) {
        var t;
        return function () { var a = arguments; clearTimeout(t); t = setTimeout(function () { fn.apply(null, a); }, delay); };
    }

    function escHtml(s) {
        return s.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;");
    }

    function validateSync(name) {
        if (!name.length) return null;
        if (name.length < 3)  return "Name must be at least 3 characters.";
        if (name.length > 25) return "Name must be at most 25 characters.";
        return null;
    }

    function post(url, data) {
        var fd = new FormData();
        Object.keys(data).forEach(function (k) {
            if (Array.isArray(data[k])) data[k].forEach(function (v) { fd.append(k, v); });
            else fd.append(k, data[k]);
        });
        return fetch(baseHref + url, { method: "POST", body: fd }).then(function (r) { return r.json(); });
    }

    function setError(input, errEl, msg) {
        if (msg) { errEl.textContent = msg; errEl.style.display = ""; input.classList.add("input-error"); }
        else      { errEl.textContent = ""; errEl.style.display = "none"; input.classList.remove("input-error"); }
    }

    // Drag & drop

    var dragSrc = null;

    function makeDraggable(row) {
        var handle = row.querySelector(".drag-handle");
        if (!handle) return;
        handle.addEventListener("mousedown", function () { row.setAttribute("draggable", "true"); });
        handle.addEventListener("mouseup",   function () { row.setAttribute("draggable", "false"); });

        row.addEventListener("dragstart", function (e) {
            dragSrc = row; row.classList.add("dragging"); e.dataTransfer.effectAllowed = "move";
        });
        row.addEventListener("dragend", function () {
            row.classList.remove("dragging"); row.setAttribute("draggable", "false");
            list.querySelectorAll(".category-row").forEach(function (r) { r.classList.remove("drag-over"); });
            post("category/update_priorities_service", { "ids[]": getRowIds() });
        });
        row.addEventListener("dragover", function (e) {
            e.preventDefault(); e.dataTransfer.dropEffect = "move";
            if (dragSrc && dragSrc !== row) {
                list.querySelectorAll(".category-row").forEach(function (r) { r.classList.remove("drag-over"); });
                row.classList.add("drag-over");
            }
        });
        row.addEventListener("drop", function (e) {
            e.preventDefault();
            if (!dragSrc || dragSrc === row) return;
            var rows = Array.from(list.querySelectorAll(".category-row"));
            list.insertBefore(dragSrc, rows.indexOf(dragSrc) < rows.indexOf(row) ? row.nextSibling : row);
            row.classList.remove("drag-over");
            updateUpDownButtons();
        });
    }

    function getRowIds() {
        return Array.from(list.querySelectorAll(".category-row")).map(function (r) { return r.getAttribute("data-id"); });
    }

    // Validation et sauvegarde du nom

    function wireEditInput(input, row, id) {
        var checkDup = debounce(function (name, errEl) {
            post("category/check_name_service", { name: name }).then(function (data) {
                setError(input, errEl, data.valid ? null : data.errors[0]);
            });
        }, 350);

        function getErrEl() { return row.querySelector(".inline-error"); }

        input.addEventListener("keydown", function (e) { if (e.key === "Enter") { e.preventDefault(); input.blur(); } });

        input.addEventListener("input", function () {
            var name = input.value.trim();
            var err = validateSync(name);
            setError(input, getErrEl(), err);
            if (!err && name.length >= 3) checkDup(name, getErrEl());
        });

        input.addEventListener("blur", function () {
            var name = input.value.trim();
            if (validateSync(name)) return;
            post("category/check_name_service", { name: name }).then(function (data) {
                if (!data.valid) { setError(input, getErrEl(), data.errors[0]); return; }
                setError(input, getErrEl(), null);
                post("category/update_name_service", { id: id, name: name }).then(function (res) {
                    if (res.success) {
                        var form = row.querySelector(".name-form");
                        form.querySelector(".cat-label").textContent = name;
                        form.classList.remove("editing");
                        var btn = row.querySelector(".btn-edit");
                        if (btn) btn.textContent = "Edit";
                    } else {
                        setError(input, getErrEl(), res.errors[0]);
                    }
                });
            });
        });
    }

    function wireEditBtn(btn, row) {
        btn.addEventListener("click", function () {
            var form = row.querySelector(".name-form");
            var input = form.querySelector(".category-name-input");
            form.classList.toggle("editing");
            if (form.classList.contains("editing")) {
                input.focus(); input.select(); btn.textContent = "Cancel";
            } else {
                input.value = form.querySelector(".cat-label").textContent;
                var err = row.querySelector(".inline-error");
                if (err) err.textContent = "";
                input.classList.remove("input-error");
                btn.textContent = "Edit";
            }
        });
    }

    // Suppression via modal

    var deleteModal = document.getElementById("deleteModal");
    var modalCatName = document.getElementById("modal-cat-name");
    var confirmDelBtn = document.getElementById("modal-confirm-delete");
    var pendingDeleteId = null;

    function wireDeleteBtn(btn, row) {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            pendingDeleteId = row.getAttribute("data-id");
            var label = row.querySelector(".cat-label");
            if (modalCatName) modalCatName.textContent = label ? label.textContent : "";
            if (typeof bootstrap !== "undefined") new bootstrap.Modal(deleteModal).show();
        });
    }

    if (confirmDelBtn) {
        confirmDelBtn.addEventListener("click", function () {
            if (!pendingDeleteId) return;
            var id = pendingDeleteId; pendingDeleteId = null;
            post("category/delete_service", { id: id }).then(function (data) {
                if (!data.success) return;
                var row = list.querySelector(".category-row[data-id='" + id + "']");
                if (row) row.remove();
                bootstrap.Modal.getInstance(deleteModal).hide();
                updateUpDownButtons();
            });
        });
    }

    // Init d'une ligne (drag, edit, delete)

    function wireRow(row) {
        makeDraggable(row);
        var input = row.querySelector(".category-name-input");
        var id    = row.getAttribute("data-id");
        var editBtn = row.querySelector(".btn-edit");
        var delBtn  = row.querySelector(".btn-delete:not([disabled])");

        // Ajouter l'élément d'erreur inline s'il n'existe pas
        if (input && !row.querySelector(".inline-error")) {
            var errSpan = document.createElement("span");
            errSpan.className = "inline-error";
            errSpan.style.display = "none";
            input.parentNode.appendChild(errSpan);
        }

        if (input)  wireEditInput(input, row, id);
        if (editBtn) wireEditBtn(editBtn, row);
        if (delBtn)  wireDeleteBtn(delBtn, row);
    }

    list.querySelectorAll(".category-row").forEach(wireRow);

    // Ajouter une ligne dynamiquement après création

    function appendNewRow(id, name) {
        var row = document.createElement("div");
        row.className = "category-row";
        row.setAttribute("data-id", id);
        row.setAttribute("data-count", "0");
        row.innerHTML =
            '<span class="drag-handle">&#8942;&#8942;</span>' +
            '<form id="save-form-' + id + '" method="post" action="category/save/' + id + '" class="name-form">' +
            '  <div class="name-field-wrap">' +
            '    <input type="text" name="name" value="' + escHtml(name) + '" class="category-name-input" maxlength="25">' +
            '    <span class="inline-error" style="display:none"></span>' +
            '  </div>' +
            '  <span class="cat-label">' + escHtml(name) + '</span>' +
            '  <span class="cat-count">(0)</span>' +
            '</form>' +
            '<div class="row-actions">' +
            '  <form method="post" action="category/move_up/' + id + '"><button type="submit" class="btn-move" disabled>Up</button></form>' +
            '  <form method="post" action="category/move_down/' + id + '"><button type="submit" class="btn-move">Down</button></form>' +
            '  <button type="submit" form="save-form-' + id + '" class="btn-save">Save</button>' +
            '  <button class="btn-edit">Edit</button>' +
            '  <a href="category/delete_confirm/' + id + '" class="btn-delete">Delete</a>' +
            '</div>';
        list.appendChild(row);
        wireRow(row);
        updateUpDownButtons();
    }

    // Bouton + pour ajouter une catégorie

    var addForm = document.querySelector(".add-form");
    if (addForm) {
        addForm.style.display = "none";
        var jsAddBar = document.createElement("div");
        jsAddBar.className = "js-add-bar";
        jsAddBar.innerHTML = '<button class="btn-add-toggle" id="js-add-btn">+</button>';
        list.parentNode.insertBefore(jsAddBar, addForm.nextSibling);

        var jsAddRow = null;

        document.getElementById("js-add-btn").addEventListener("click", function () {
            if (jsAddRow) return;
            jsAddRow = document.createElement("div");
            jsAddRow.className = "category-row js-add-row";
            jsAddRow.innerHTML =
                '<div class="name-field-wrap" style="flex:1">' +
                '  <input type="text" class="category-name-input" placeholder="Category name" maxlength="25">' +
                '  <span class="inline-error" style="display:none"></span>' +
                '</div>' +
                '<div class="row-actions">' +
                '  <button class="btn-cancel-add" id="js-add-cancel">Cancel</button>' +
                '</div>';
            list.appendChild(jsAddRow);

            var input     = jsAddRow.querySelector(".category-name-input");
            var errSpan   = jsAddRow.querySelector(".inline-error");
            var cancelBtn = document.getElementById("js-add-cancel");
            var saving    = false;

            var checkDup = debounce(function (name) {
                post("category/check_name_service", { name: name }).then(function (data) {
                    setError(input, errSpan, data.valid ? null : data.errors[0]);
                });
            }, 350);

            input.focus();

            input.addEventListener("input", function () {
                var name = input.value.trim();
                var err = validateSync(name);
                setError(input, errSpan, err);
                if (!err && name.length >= 3) checkDup(name);
            });

            input.addEventListener("keydown", function (e) {
                if (e.key === "Escape") { cancelBtn.click(); }
            });

            input.addEventListener("blur", function () {
                if (saving) return;
                var name = input.value.trim();
                var syncErr = validateSync(name);
                if (syncErr) { setError(input, errSpan, syncErr); return; }
                post("category/check_name_service", { name: name }).then(function (data) {
                    if (!data.valid) { setError(input, errSpan, data.errors[0]); return; }
                    saving = true;
                    post("category/add_service", { name: name }).then(function (res) {
                        saving = false;
                        if (!res.success) { setError(input, errSpan, res.errors[0]); return; }
                        jsAddRow.remove(); jsAddRow = null;
                        appendNewRow(res.id, res.name);
                    });
                });
            });

            cancelBtn.addEventListener("mousedown", function () { saving = true; });
            cancelBtn.addEventListener("click", function () { saving = false; jsAddRow.remove(); jsAddRow = null; });
        });
    }

    // Activer/désactiver les boutons Up/Down selon la position

    function updateUpDownButtons() {
        var rows = list.querySelectorAll(".category-row");
        rows.forEach(function (row, i) {
            var up   = row.querySelector('form[action*="move_up"] button');
            var down = row.querySelector('form[action*="move_down"] button');
            if (up)   up.disabled   = (i === 0);
            if (down) down.disabled = (i === rows.length - 1);
        });
    }

    updateUpDownButtons();
});
