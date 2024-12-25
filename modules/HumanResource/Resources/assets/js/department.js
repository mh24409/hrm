function editDetails(uuid) {
    let editBtn = $("#editDetails-" + uuid);
    let editUrl = editBtn.data("edit-url"); // Commented out for now
    $.ajax({
        type: "GET",
        dataType: "html",
        url: editUrl,
        success: function (data) {
            $("#edit-department .modal-content").html(data);
            $("#edit-department").modal("show");
        },
    });
}
