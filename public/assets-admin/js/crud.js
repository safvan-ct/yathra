class CRUD {
    static resource = null;

    static setResource(name) {
        this.resource = name;
    }

    static loadDataTable(columns, tableId = "dataTable", rowColor = false) {
        let resource = this.resource;

        let options = {
            processing: true,
            serverSide: true,
            deferRender: true,
            destroy: true,
            responsive: true,

            ajax: {
                url: `/admin/${resource}/datatable`,
                data: function (d) {
                    d.filter = $("#getFilter").length
                        ? $("#getFilter").val()
                        : null;
                },
            },

            columns: columns,

            columnDefs: [{ targets: "_all", className: "text-center" }],
        };

        if (rowColor) {
            options.createdRow = function (row, data) {
                $(row)
                    .removeClass("row-new row-opened row-closed")
                    .addClass(`row-${data.status}`);
            };
        }

        return $(`#${tableId}`).DataTable(options);
    }

    static open(id = 0, attributeId = "") {
        toastr.clear();
        let resource = this.resource;

        $("#crudTitle").text(id ? `Edit ${resource}` : `Create ${resource}`);
        $("#crudBody").html("Loading...");

        // Load fields partial from controller
        let formUrl = attributeId
            ? `/admin/${resource}/form/${id}/${attributeId}`
            : `/admin/${resource}/form/${id}`;

        $("#crudBody").load(formUrl, function () {
            let img = $("#imagePreview");

            // show only if value exists
            if (img.attr("src")) {
                img.removeClass("d-none");
            }
        });

        $("#crudModal").modal("show");

        $("#crudForm")
            .off("submit")
            .on("submit", function (e) {
                e.preventDefault();
                CRUD.save("dataTable", attributeId);
            });
    }

    static save(table = "dataTable", attributeId = "") {
        toastr.clear();
        let resource = this.resource;

        let form = document.getElementById("crudForm");
        let formData = new FormData(form);
        let id = formData.get("id");

        let createUrl = attributeId
            ? `/admin/${resource}/${attributeId}`
            : `/admin/${resource}`;
        let url = id && id > 0 ? `/admin/${resource}/${id}` : createUrl;

        formData.append("_method", id && id > 0 ? "PUT" : "POST");

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    "meta[name='csrf-token']"
                ).content,
            },
            success: function (res) {
                toastr.success(res.message || "Success");
                $("#crudModal").modal("hide");

                // Refresh Datatable safely
                $(`#${table}`).DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    // Laravel validation errors
                    $.each(xhr.responseJSON.errors, function (key, messages) {
                        toastr.error(messages[0]);
                    });
                } else {
                    toastr.error(
                        xhr.responseJSON?.message ||
                            "Server error. Please try again."
                    );
                }
            },
            complete: function () {
                hideLoader();
            },
        });
    }

    static delete(id, table = "dataTable") {
        let resource = this.resource;
        toastr.clear();

        if (!confirm("Are you sure?")) return;

        fetch(`/admin/${resource}/${id}`, {
            method: "DELETE",
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    "meta[name='csrf-token']"
                ).content,
            },
        })
            .then((r) => r.json())
            .then((res) => {
                toastr.success(res.message);
                $(`#${table}`).DataTable().ajax.reload(null, false);
            });
    }

    static toggleStatus(id, field = "is_active", dataTableId = "dataTable") {
        let resource = this.resource;
        toastr.clear();

        // if (!confirm("Are you sure you want to change status?")) return;

        showLoader();

        fetch(`/admin/${resource}/${id}/toggle-status`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    "meta[name='csrf-token']"
                ).content,
            },
            body: JSON.stringify({
                column: field,
            }),
        })
            .then((r) => r.json())
            .then((res) => {
                toastr.success(res.message);
                $(`#${dataTableId}`).DataTable().ajax.reload(null, false);
            })
            .catch(() => toastr.error("Network error"))
            .finally(() => hideLoader());
    }

    // reusable toggle column
    static columnToggleStatus(field = "is_active", table = "dataTable") {
        return {
            data: field,
            orderable: false,
            searchable: false,
            render: (data, type, row) => {
                const label = data ? "Active" : "Inactive";
                const color = data ? "text-success" : "text-danger";

                return `<button  class="btn btn-link ${color}" onclick="CRUD.toggleStatus(${row.id}, '${field}', '${table}')">${label}</button>`;
            },
        };
    }

    // reusable action buttons column
    static columnActions(
        edit = true,
        del = true,
        table = "dataTable",
        attributeId = ""
    ) {
        return {
            data: null, // action is not from DB
            orderable: false,
            searchable: false,
            render: (data, type, row) => {
                return `
                    <button class="btn btn-link" onclick="CRUD.open(${
                        row.id
                    }, ${attributeId})">Edit</button>
                    ${
                        del
                            ? `<button class="btn btn-link text-danger" onclick="CRUD.delete(${row.id}, '${table}')">Delete</button>`
                            : ""
                    }
                `;
            },
        };
    }
}
