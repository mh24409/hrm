$(".mobile_code").intlTelInput({
    initialCountry: "bd",
    separateDialCode: true,
});


$(".add-button").click(function() {
    var index = $('.academic-col').length;
    var count = index + 1;

    var academic_information_text = $('#get-academic-information-text').text();
    var exam_title_text = $('#get-exam-title-text').text();
    var institute_name_text = $('#get-institute-name-text').text();
    var result_text = $('#get-result-text').text();
    var graduation_year_text = $('#get-graduation-year-text').text();
    var attachment_text = $('#get-attachment-text').text();
    var html = `<div class="col-lg-4 col-md-4 col-12 academic-col" id="academic-col-${count}">
    <div class="card academic-card mb-4 rounded-0">
        <div class="card-header text-light employee-show-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semi-bold">${academic_information_text} - ${count}</h6>
                <span class="text-end remove-button"><i class="fa fa-circle-minus"></i></span>
            </div>
        </div>

        <div class="card-body employee-show-card-body">
            <div class="row align-items-center employee-border">
                <div class="col employee-padding">
                    <h6 class="mb-0 fs-14" id="get-exam-title-text">${exam_title_text}</h6>
                </div>
                <div class="col-auto employee-padding">
                    <input type="text" name="exam_title[]" id="exam_title_${count}" placeholder="Enter Exam Title" class="form-control fs-13 text-muted employee-form">
                </div>
            </div>
            <div class="row align-items-center employee-border">
                <div class="col employee-padding">
                    <h6 class="mb-0 fs-14" id="get-institute-name-text">${institute_name_text}</h6>
                </div>
                <div class="col-auto employee-padding">
                    <input type="text" name="institute_name[]" id="institute_name_${count}" placeholder="Enter Institute Name" class="form-control fs-13 text-muted employee-form">
                </div>
            </div>
            <div class="row align-items-center employee-border">
                <div class="col employee-padding">
                    <h6 class="mb-0 fs-14" id="get-result-text">${result_text}</h6>
                </div>
                <div class="col-auto employee-padding">
                    <input type="text" name="result[]" id="result_${count}" placeholder="Enter Result" class="form-control fs-13 text-muted employee-form">
                </div>
            </div>
            <div class="row align-items-center employee-border">
                <div class="col employee-padding">
                    <h6 class="mb-0 fs-14" id="get-graduation-year-text">${graduation_year_text}</h6>
                </div>
                <div class="col-auto employee-padding">
                    <input type="number" name="graduation_year[]" id="graduation_year_${count}" placeholder="Enter Graduation Year" class="form-control fs-13 text-muted employee-form">
                </div>
            </div>
            <div class="row align-items-center employee-border">
                <div class="col-5 employee-padding">
                    <h6 class="mb-0 fs-14" id="get-attachment-text">${attachment_text} <span id="top-tooltip" title="PDF, JPEG, JPG format only allow & Size maximum 10 MB" aria-label="PDF, JPEG, JPG format only allow & Size maximum 10 MB"><i class="fa fa-info-circle" aria-hidden="true"></i></span></h6>
                </div>
                <div class="col-7 employee-padding">
                    <input type="file" name="academic_attachment[]" onchange="academicAttachment(${count})"  id="academic_attachment_${count}"
                        class="custom-input-file attachment"/>
                    <label for="academic_attachment_${count}" class="fs-13 text-end">
                        <i class="fa fa-upload"></i>
                        <span>Choose a file</span>
                    </label>
                    <span id="academic_attachment_error_${count}" class="academic_attachment_error"></span>
                </div>
            </div>
        </div>
    </div>
    </div>`;

    $(html).insertAfter('#academic-col-'+ (count -1));

    $('.custom-input-file').each(function() {
        var $input = $(this),
            $label = $input.next('label'),
            labelVal = $label.html();

        $input.on('change', function(element) {
            var fileName = '';
            if (element.target.value) fileName = element.target.value.split('\\').pop();
            fileName ? $label.find('span').html(fileName) : $label.html(labelVal);
        });
    });


});



$(document).on('click', '.remove-button', function(events) {
    $(this).parents('.academic-col').remove();
});

$(".add-row").click(function() {
    var index = $('.document-row').length+1;
    var count = index + 1;
    var document_title_text = $('.get_document_title_text').attr('placeholder');
    var expire_date_text = $('.get_expire_date_text').attr('placeholder');
    var is_notify_text = $('.get_is_notify_text').text();


    var html = `<div class="row align-items-center employee-border document-row">
            <div class="col-3 employee-padding">
                <input type="text" name="document_title[]" id="document_title_${count}"
                    placeholder="${document_title_text}"
                    class="form-control fs-13 text-muted employee-form text-start get_document_title_text">
            </div>
            <div class="col-3 employee-padding">
                <input type="file" name="document_attachment[]" onchange="document_attachment(${count})" id="document_attachment_${count}"
                    class="custom-input-file text-start" />
                <label for="document_attachment_${count}" class="fs-13 text-end">
                    <i class="fa fa-upload"></i>
                    <span>Choose a file</span>
                </label>
                <span id="document_attachment_error_${count}" class="document_attachment_error"></span>
            </div>
            <div class="col-3 employee-padding">
                <input type="text" name="document_expire[]" id="document_expire_${count}"
                    placeholder="${expire_date_text}"
                    class="form-control fs-13 text-muted employee-form date_picker text-center get_expire_date_text" autocomplete="off">
            </div>
            <div class="col-2 skin-flat employee-padding">
                <div class="i-check employee-form fs-13">
                    <input tabindex="13" type="checkbox" name="is_notify[]" id="is_notify_${count}"
                        id="flat-checkbox-${count}">
                    <label for="flat-checkbox-${count}"
                        class="fs-13 text-muted employee-form">${is_notify_text}</label>

                </div>
            </div>
            <div class="col-1 employee-padding text-end">
                <button class="btn btn-danger btn-sm document-delete-button" type="button"><i class="fa fa-close"></i></button>
            </div>
        </div>`;

    $('.document-add-row').append(html);
    $(".date_picker").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        showAnim: "slideDown",
    });
    $('.skin-flat .i-check input').iCheck({
        checkboxClass: 'icheckbox_flat-red',
        radioClass: 'iradio_flat-red'
    });

    $('.custom-input-file').each(function() {
        var $input = $(this),
            $label = $input.next('label'),
            labelVal = $label.html();

        $input.on('change', function(element) {
            var fileName = '';
            if (element.target.value) fileName = element.target.value.split('\\').pop();
            fileName ? $label.find('span').html(fileName) : $label.html(labelVal);
        });
    });

});

$(document).on('click', '.remove-button', function(events) {
    $(this).parents('.academic-col').remove();
});

$(document).on('click', '.document-delete-button', function(events) {
    $(this).parents('.document-row').remove();
});


function getSkillType(){
    var baseurl = $("#base_url").val();
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: baseurl + '/hr/employee/get-skill-type/',
        type: 'get',
        dataType: 'json',
        data: {
            _token: csrf,
        },
        success: function(data) {
            $('#skillTypeID').html('');
            var edit_page = $('.employee-edit').data('edit_page');

            $('#skillTypeID').append($('<option selected disabled>Select Skill Type</option>'));
            $.each(data, function (i, item) {
                $('#skillTypeID').append($('<option>', {
                    value: item.name,
                    text : item.name,
                }));
            });
        }
    });
}

function skillTypeAdd(){
    var url = $('#formSkillType').attr('action');
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: $('#formSkillType').serialize(),
        success: function(data) {
            $('#formSkillType').trigger('reset');
            toastr.success(data.success);
            $('#skillTypeModal').modal('hide');
        }
    });

    getSkillType();
}

function deleteSkillTypeOption(id){

    var baseurl = $("#base_url").val();
    var csrf = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: baseurl + '/hr/employee/delete-skill-type/'+id,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf,
        },
        success: function(data) {
            toastr.success(data.success);
            getSkillType();
            $('#skillTypeID').trigger('change');
        }
    });
}

function getCertificateType(){
    var baseurl = $("#base_url").val();
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: baseurl + '/hr/employee/get-certificate-type/',
        type: 'get',
        dataType: 'json',
        data: {
            _token: csrf,
        },
        success: function(data) {
            $('#certificateTypeID').html('');
            $('#certificateTypeID').append($('<option selected disabled>Select Certificate Type</option>'));
            $.each(data, function (i, item) {
                $('#certificateTypeID').append($('<option>', {
                    value: item.name,
                    text : item.name,
                    selected: item.name == data.name
                }));
            });
        }
    });
}

function certificateTypeAdd(){
    var url = $('#formCertificateType').attr('action');
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: $('#formCertificateType').serialize(),
        success: function(data) {
            $('#formCertificateType').trigger('reset');
            toastr.success(data.success);
            $('#certificateTypeModal').modal('hide');
        }
    });

    getCertificateType();
}

function deleteCertificateTypeOption(id){

    var baseurl = $("#base_url").val();
    var csrf = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: baseurl + '/hr/employee/delete-certificate-type/'+id,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf,
        },
        success: function(data) {
            toastr.success(data.success);
            getCertificateType();
            $('#certificateTypeID').trigger('change');
        }
    });
}

$(document).ready(function() {
    getSkillType();
    getCertificateType();
})

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

$("#imageUpload").change(function() {
    readURL(this);
    $("#imageUploadForm").submit();
});

// attachment validation
$("#identification_attachment").change(function() {
    $("#identification_attachment_error").html("");
	$(".attachment").css("border-color","#F0F0F0");
	var file_size = $('#identification_attachment')[0].files[0].size;
	if(file_size>10485760) {
        $("#identification_attachment").val('');
        toastr.success('File size must be less than 10 MB');
		$(".attachment").css("border-color","#F0F0F0");
		return false;
	}
	return true;
});
$("#skill_attachment").change(function() {
    $("#skill_attachment_error").html("");
	$(".attachment").css("border-color","#F0F0F0");
	var file_size = $('#skill_attachment')[0].files[0].size;
	if(file_size>10485760) {
        $("#identification_attachment").val('');
        toastr.success('File size must be less than 10 MB');
		return false;
	}
	return true;
});

function document_attachment(count) {
    $("#document_attachment_error_"+count).html("");
	$(".attachment").css("border-color","#F0F0F0");
	var file_size = $('#document_attachment_'+count)[0].files[0].size;
	if(file_size>10485760) {
        $("#identification_attachment").val('');
        toastr.success('File size must be less than 10 MB');
		return false;
	}
	return true;
}
function academicAttachment(count) {
    $("#academic_attachment_error_"+count).html("");
	$(".attachment").css("border-color","#F0F0F0");
	var file_size = $('#academic_attachment_'+count)[0].files[0].size;
	if(file_size>10485760) {
        $("#identification_attachment").val('');
        toastr.success('File size must be less than 10 MB');
		return false;
	}
	return true;
}

