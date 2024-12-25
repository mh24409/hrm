function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$('#imageUploadForm').on('submit', (function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            toastr.success("Profile Picture Updated Successfully..!!");
        },
        error: function(data) {
            toastr.error("Profile Picture Not Updated..!!");
        }
    });
}));

$("#imageUpload").change(function() {
    readURL(this);
    $("#imageUploadForm").submit();
});