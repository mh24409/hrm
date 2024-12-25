
$(document).ready(function () {
    "use strict";

    $("#coatree").on("click", function (event) {
        $("#addCoafrom").html('');
        $("#editCoafrom").html('');
        $("#delCoafrom").html('');
        var accountHeadId = event.target.id;


        if (accountHeadId != '') {
            var coaId = $("#" + accountHeadId).closest('li').attr("data-id");
            addCartOfAccount(coaId);

        }

        else {

        }

    })


    $("#treesearch").on("keyup", function () {
        $("#html").jstree("open_all");
        var value = this.value.toLowerCase().trim();
        $(".jstree-children,.jstree-node").show().filter(function () {
            return $(this).text().toLowerCase().trim().indexOf(value) == -1;
        }).hide();
    });



});

var baseurl = $('#url').val();
function addCartOfAccount(coaID) {
    "use strict";

    var url = baseurl + '/accounts/get/detail/' + coaID;
    accLoader();
    $.ajax({
        type: 'GET',
        url: url,
        async: false,
        success: function (data) {

            if ((data.coaDetail.head_level == 1) && (data.coaDetail.parent_id == 0)) {
                var addForm = addNewCoaFrom(data);
                $("#addCoafrom").html(addForm);
                $("#editCoafrom").html('');
                $("#delCoafrom").html('');
            }
            if ((data.coaDetail.head_level == 2) || (data.coaDetail.head_level == 3)) {

                checkUpdeate(data);
            }

            if (data.coaDetail.head_level == 4) {

                checkUpdeate(data);
            }

            // head_level higher then 5 toster message show
            if (data.coaDetail.head_level > 4) {
                toastr.error(
                    "Invalid Account Head Level.The Account Head Level is Higher Then 4"
                );
            }





        }
    });
    accLoader("hide");
}

var ObjData;
function addNewCoaFrom(ObjData) {
    var headLable = Number(ObjData.coaDetail.head_level) + 1;
    var currentHeadLabel = ObjData.coaDetail.head_level;
    var note_no = "";
    var lines = "<div class='row g-4'>";
    lines += "<div class='col-md-12'>";

    // currentHeadLabel start
    lines += "<div class='row'>";
    lines += "<label for='currentHeadLabel' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Head Label<i class='text-danger'>*</i></label>";
    lines += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    lines += "<input type='text' readonly class='form-control'  name='currentHeadLabel' value=" + currentHeadLabel + "  required >";
    lines += "</div>";
    lines += "</div>";
    // currentHeadLabel end

    //note start
    if (currentHeadLabel == 3 || currentHeadLabel == 4) {
        lines += "<div class='row'>";
        lines += "<label for='note_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Note No</label>";
        lines += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        lines += "<input type='text'  class='form-control'  name='note_no' value='" + note_no + "'>";
        lines += "</div>";
        lines += "</div>";
    }
    // note  end



    // account_name start
    lines += "<div class='row'>";
    lines += "<label for='account_name' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Ledger Name<i class='text-danger'>*</i></label>";
    lines += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    lines += "<input type='text' class='form-control' id='account_name' name='account_name'  required >";
    lines += "</div>";
    lines += "</div>";
    // account_name end

    //radio button start
    lines += "<div class='row mt-2'>";
    lines += "<label for='bed_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Status</label>";
    lines += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

    lines += "<input class='form-check-input m-1' type='radio' name='is_active' value='1' id='is_active' checked >";
    lines += "<label class='form-check-label' for='is_active'>Active</label>";
    lines += "<input class='form-check-input m-1' type='radio' name='is_active' value='0' id='is_active' >";
    lines += "<label class='form-check-label' for='is_active'>Disable</label>";

    lines += "</div>";
    lines += "</div>";
    //radio button End
    lines += "</div>";
    lines += "</div>";

    //hidden Field start
    lines += "<input type='hidden' name='head_level' value=" + headLable + " id='head_level'>";
    lines += "<input type='hidden' name='parent_id' value=" + ObjData.coaDetail.id + " id='parent_id'>";
    lines += "<input type='hidden' name='acc_type_id' value=" + ObjData.coaDetail.acc_type_id + " id='acc_type_id'>";

    //hidden Field end


    // button text
    lines += "<div class='col-md-12'>";
    lines += "<div class='row'>";
    lines += "<div class='col-md-4'></div>";
    lines += "<div class='col-md-8'>";
    lines += "<div class='form-group mt-3'><button type='submit' class='btn btn-success' >ADD</button></div>";
    lines += "</div>";
    lines += "</div>";
    lines += "</div>";
    // button text

    return lines;
}




function dailogConfirm(data) {
    var newData = data;

    $("#editCoafrom").html('');
    $("#delCoafrom").html('');
    $("#addCoafrom").html('');
    $("#dialog-confirm").html('<p>Please Confirm Your Options</p>');
    var Close = "x";
    $("#dialog-confirm").dialog({

        open: function () {
            $(".ui-dialog-titlebar-close").html(Close);
        },
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
            "Create New": function () {
                $(this).dialog("close");
                $("#dialog-confirm").html();
                addLoadFromForAll(data)
            },
            "Edit": function () {
                $(this).dialog("close");
                $("#dialog-confirm").html();
                checkUpdeate(data)
            },
            "Delete": function () {
                $(this).dialog("close");
                $("#dialog-confirm").html();
                loadDelfrom(data);
            },
            Cancel: function () {
                $(this).dialog("close");
                $("#dialog-confirm").html();
            }
        }
    });

    //end dialog confirm function here



}


function dailogfourthLable(data) {
    var EdnewData = data;
    $("#editCoafrom").html('');
    $("#delCoafrom").html('');
    $("#addCoafrom").html('');
    $("#dialog-confirm").html('<p>Please Confirm Your Options</p>');
    var Close = "x";
    $("#dialog-confirm").dialog({


        open: function () {
            $(".ui-dialog-titlebar-close").html(Close);
        },
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {

            "Edit": function () {
                $(this).dialog("close");
                $("#dialog-confirm").html('');
                checkUpdeate(data)
            },
            "Delete": function () {
                $(this).dialog("close");
                $("#dialog-confirm").html('');
                loadDelfrom(data);
            },
            Cancel: function () {
                $(this).dialog("close");
                $("#dialog-confirm").html('');
            }
        }
    });

}


var allfromLoad;
function addLoadFromForAll(allfromLoad) {

    var headLable = Number(allfromLoad.coaDetail.head_level) + 1;
    var currentHeadLabel = allfromLoad.coaDetail.head_level;
    var note_no = '';

    var addFrom = "<div class='row g-4'>";
    addFrom += "<div class='col-md-12'>";

    // currentHeadLabel start
    addFrom += "<div class='row'>";
    addFrom += "<label for='currentHeadLabel' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Head Label<i class='text-danger'>*</i></label>";
    addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    addFrom += "<input type='text' readonly class='form-control' id='currentHeadLabel' value=" + currentHeadLabel + " name='currentHeadLabel'  required >";
    addFrom += "</div>";
    addFrom += "</div>";
    // currentHeadLabel end

    //note start
    if (currentHeadLabel == 3 || currentHeadLabel == 4) {
        addFrom += "<div class='row'>";
        addFrom += "<label for='note_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Note No</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom += "<input type='text' class='form-control' id='note_no' value='" + note_no + "' name='note_no'>";
        addFrom += "</div>";
        addFrom += "</div>";
    }
    //note end


    // row start
    addFrom += "<div class='row'>";
    addFrom += "<label for='account_name' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Ledger Name <i class='text-danger'>*</i></label>";
    addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    addFrom += "<input type='text' class='form-control' id='account_name' name='account_name'  required >";
    addFrom += "</div>";
    addFrom += "</div>";
    // row end

    //radio button start
    addFrom += "<div class='row mt-2'>";
    addFrom += "<label for='bed_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Status</label>";
    addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

    addFrom += "<input class='form-check-input m-1' type='radio' name='is_active' value='1' id='is_active' checked >";
    addFrom += "<label class='form-check-label' for='is_active'>Active</label>";
    addFrom += "<input class='form-check-input m-1' type='radio' name='is_active' value='0' id='is_active' >";
    addFrom += "<label class='form-check-label' for='is_active'>Disable</label>";

    addFrom += "</div>";
    addFrom += "</div>";
    //radio button End


    //hidden Field start
    addFrom += "<input type='hidden' name='head_level' value=" + headLable + " id='head_level'>";
    addFrom += "<input type='hidden' name='parent_id' value=" + allfromLoad.coaDetail.id + " id='parent_id'>";
    addFrom += "<input type='hidden' name='acc_type_id' value=" + allfromLoad.coaDetail.acc_type_id + " id='acc_type_id'>";

    //hidden Field end

    //Head Lable 2 and account head type Assect

    if ((Number(allfromLoad.coaDetail.head_level) == 2) && (Number(allfromLoad.coaDetail.acc_type_id) == 1)) {

        //Assect radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Asset Type</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        addFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_stock' id='is_stock required >";
        addFrom += "<label class='form-check-label' for='is_stock'>Is Stock</label>";
        addFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_fixed_asset' id='is_fixed_asset'   >";
        addFrom += "<label class='form-check-label' for='is_fixed_asset'>Is Fixed Asset</label>";

        addFrom += "</div>";
        addFrom += "</div>";

        //Assect radio button End



    }


    if ((Number(allfromLoad.coaDetail.head_level) == 2) && ((Number(allfromLoad.coaDetail.acc_type_id) == 4) || (Number(allfromLoad.coaDetail.acc_type_id) == 5))) {

        //Assect radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Asset Type</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";
        addFrom += "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_fixed_asset' id='is_fixed_asset' >";
        addFrom += "<label class='form-check-label' for='is_fixed_asset'>Is Fixed Asset</label>";
        addFrom += "</div>";
        addFrom += "</div>";


    }


    if ((Number(allfromLoad.coaDetail.head_level) == 3) && (Number(allfromLoad.coaDetail.acc_type_id) == 1)) {

        //Assect radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Asset Type</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";


        addFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_stock' id = 'asset_is_stock' required >";
        addFrom += "<label class='form-check-label' for='asset_is_stock'>Is Stock</label>";

        addFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'asset_is_fixed_asset'  >";
        addFrom += "<label class='form-check-label' for='asset_is_fixed_asset'>Is Fixed Asset</label>";

        addFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'asset_is_subtype' >";
        addFrom += "<label class='form-check-label' for='asset_is_subtype'>Is Sub Type</label>";

        addFrom += "<input class='form-check-input mr-3 m-1' type='radio' name='asset_type' value='is_cash' id = 'asset_is_cash' >";
        addFrom += "<label class='form-check-label' for='asset_is_cash'>Is Cash Nature</label>";

        addFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_bank'  id = 'asset_is_bank' >";
        addFrom += "<label class='form-check-label' for='asset_is_bank'>Is Bank Nature</label>";


        addFrom += "</div>";
        addFrom += "</div>";

        //Assect radio button End

        addFrom += "<div class='row mt-2 d-none' id='fixedAssetField'>";
        //Fixed Asset Code start
        addFrom += "<div class='row'>";
        addFrom += "<label for='asset_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Fixed Asset Code </label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom += "<input type='text' class='form-control' id='asset_code' name='asset_code' >";
        addFrom += "</div>";
        addFrom += "</div>";

        addFrom += "<div class='row'>";
        addFrom += "<label for='depreciation_rate' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Depraciation Rate % </label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom += "<input type='text' class='form-control' id='depreciation_rate' name='depreciation_rate' >";
        addFrom += "</div>";
        addFrom += "</div>";

        //Fixed Asset Code End
        addFrom += "</div>";



        addFrom += "<div class='row mt-2 d-none' id='SubtypeAssetField'>";
        // Account subtype Dropdown start
        addFrom += "<div class='col-md-12 mt-3'>";
        addFrom += "<div class='row'>";
        addFrom += "<div class='col-md-4'>Sub Type</div>";
        addFrom += "<div class='col-md-8'>";

        addFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
        var subtypeAcc = $("#accsubType").val();
        var acSubtype = JSON.parse(subtypeAcc);
        $.each(acSubtype, function (key, value) {
            addFrom += "<option value = '" + value.id + "'>" + value.subtype_name + "</option>";
        });
        addFrom += "</select>";
        addFrom += "</div>";
        addFrom += "</div>";
        addFrom += "</div>";
        // Account subtype Dropdown end
        addFrom += "</div>";

    }

    if ((Number(allfromLoad.coaDetail.head_level) == 3) && ((Number(allfromLoad.coaDetail.acc_type_id) == 2) || (Number(allfromLoad.coaDetail.acc_type_id) == 3))) {
        //Assect radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Asset Type</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        addFrom += "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_subtype'  id = 'expense_is_subtype' >";
        addFrom += "<label class='form-check-label' for='asset_type'>Is Sub Type</label>";
        addFrom += "</div>";
        addFrom += "</div>";


        addFrom += "<div class='row mt-2 d-none' id='SubtypeAssetFieldExpense'>";
        // Account subtype Dropdown start
        addFrom += "<div class='col-md-12 mt-3'>";
        addFrom += "<div class='row'>";
        addFrom += "<div class='col-md-4'>Sub Type</div>";
        addFrom += "<div class='col-md-8'>";

        addFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
        var subtypeAccExpense = $("#accsubType").val();
        var acSubtypeExpense = JSON.parse(subtypeAccExpense);
        $.each(acSubtypeExpense, function (key, evalue) {
            addFrom += "<option value = '" + evalue.id + "'>" + evalue.subtype_name + "</option>";
        });
        addFrom += "</select>";
        addFrom += "</div>";
        addFrom += "</div>";
        addFrom += "</div>";
        // Account subtype Dropdown end
        addFrom += "</div>";

        //Assect radio button End
    }



    if ((Number(allfromLoad.coaDetail.head_level) == 3) && ((Number(allfromLoad.coaDetail.acc_type_id) == 4) || (Number(allfromLoad.coaDetail.acc_type_id) == 5))) {

        //Assect radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Asset Type</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        addFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'le_is_fixed_asset'  >";
        addFrom += "<label class='form-check-label' for='le_is_fixed_asset'>Is Fixed Asset</label>";

        addFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'le_is_subtype' >";
        addFrom += "<label class='form-check-label' for='le_is_subtype'>Is Sub Type</label>";

        addFrom += "</div>";
        addFrom += "</div>";
        //Assect radio button End



        addFrom += "<div class='row mt-2 d-none' id='lefixedAssetField'>";
        //Fixed Asset Code start
        addFrom += "<div class='row'>";
        addFrom += "<label for='dep_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Depraciation Code </label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom += "<input type='text' class='form-control' id='dep_code' name='dep_code' >";
        addFrom += "</div>";
        addFrom += "</div>";

        //Fixed Asset Code End
        addFrom += "</div>";


        addFrom += "<div class='row mt-2 d-none' id='leSubtypeAssetField'>";
        // Account subtype Dropdown start
        addFrom += "<div class='col-md-12 mt-3'>";
        addFrom += "<div class='row'>";
        addFrom += "<div class='col-md-4'>Sub Type</div>";
        addFrom += "<div class='col-md-8'>";

        addFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
        var lesubtypeAcc = $("#accsubType").val();
        var acSubtypele = JSON.parse(lesubtypeAcc);
        $.each(acSubtypele, function (key, levalue) {
            addFrom += "<option value = '" + levalue.id + "'>" + levalue.subtype_name + "</option>";
        });
        addFrom += "</select>";
        addFrom += "</div>";
        addFrom += "</div>";
        addFrom += "</div>";
        // Account subtype Dropdown end
        addFrom += "</div>";


    }


    addFrom += "</div>";
    addFrom += "</div>";
    // button text
    addFrom += "<div class='col-md-12'>";
    addFrom += "<div class='row'>";
    addFrom += "<div class='col-md-4'></div>";
    addFrom += "<div class='col-md-8'>";
    addFrom += "<div class='form-group mt-3'><button type='submit' class='btn btn-success' >ADD</button></div>";
    addFrom += "</div>";
    addFrom += "</div>";
    addFrom += "</div>";
    // button text


    $("#addCoafrom").html('');
    $("#editCoafrom").html('');
    $("#delCoafrom").html('');

    $("#addCoafrom").html(addFrom);

}




function loadDelfrom(data) {
    var delform = "<div class='row g-4'>";
    delform += "<div class='col-md-12'>";
    // row start
    delform += "<div class='row'>";
    delform += "<label for='account_name' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Ledger Name <i class='text-danger'>*</i></label>";
    delform += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    delform += "<input type='text' class='form-control' id='account_name' name='account_name' value = '" + data.coaDetail.account_name + "'  readonly >";
    delform += "</div>";
    delform += "</div>";
    // row end
    delform += "</div>";
    delform += "</div>";

    delform += "<input type='hidden' name='id' value=" + data.coaDetail.id + " id='head_level'>";

    // button text
    delform += "<div class='col-md-12'>";
    delform += "<div class='row'>";
    delform += "<div class='col-md-4'></div>";
    delform += "<div class='col-md-8'>";
    delform += "<div class='form-group mt-3'><button type='submit' class='btn btn-danger' >Confirm Delete</button></div>";
    delform += "</div>";
    delform += "</div>";
    delform += "</div>";
    // button text

    $("#addCoafrom").html('');
    $("#editCoafrom").html('');
    $("#delCoafrom").html('');

    $("#delCoafrom").html(delform);
}

function checkUpdeate(data) {
    var editcoaId = data.coaDetail.id

    var editurl = baseurl + '/accounts/' + editcoaId + '/edit';

    $.ajax({
        type: 'GET',
        url: editurl,
        async: false,
        success: function (data) {

            $("#addCoafrom").html('');
            $("#editCoafrom").html('');
            $("#delCoafrom").html('');
            var fromupdate = loadUpdatefrom(data)
            $("#editCoafrom").html(fromupdate);




        }
    });
}

var UpdateCoaData;
function loadUpdatefrom(UpdateCoaData) {
    var currentHeadLabel = UpdateCoaData.coaDetail.head_level;
    var note_no = UpdateCoaData.coaDetail.note_no == null ? '' : UpdateCoaData.coaDetail.note_no;

    var updateFrom = "<div class='row g-4'>";
    updateFrom += "<div class='col-md-12'>";
    // row start
    updateFrom += "<div class='row'>";
    updateFrom += "<label for='currentHeadLabel' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Head Label<i class='text-danger'>*</i></label>";
    updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    updateFrom += "<input type='text' readonly class='form-control' id='currentHeadLabel' name='currentHeadLabel' value = '" + currentHeadLabel + "'>";
    updateFrom += "</div>";
    updateFrom += "</div>";
    // row end

    //note start
    if (currentHeadLabel == 3 || currentHeadLabel == 4) {
        updateFrom += "<div class='row'>";
        updateFrom += "<label for='note_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Note No</label>";
        updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        updateFrom += "<input type='text' class='form-control' id='note_no' name='note_no' value = '" + note_no + "'>";
        updateFrom += "</div>";
        updateFrom += "</div>";
    }
    //note end

    // row start
    updateFrom += "<div class='row'>";
    updateFrom += "<label for='account_name' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Ledger Name<i class='text-danger'>*</i></label>";
    updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    updateFrom += "<input type='text' class='form-control' id='account_name' name='account_name' value = '" + UpdateCoaData.coaDetail.account_name + "' required>";
    updateFrom += "</div>";
    updateFrom += "</div>";
    // row end

    //Parent Head Name
    updateFrom += "</div>";
    updateFrom += "</div>";


    updateFrom += "<div class='col-md-12 mt-3'>";
    updateFrom += "<div class='row'>";
    updateFrom += "<div class='col-md-4'>Parent Name</div>";
    updateFrom += "<div class='col-md-8'>";

    updateFrom += "<select id='parent_id' name='parent_id' class='form-select'>";
    $.each(UpdateCoaData.coaDropDown, function (key, value) {
        if (value.id == UpdateCoaData.coaDetail.parent_id) {
            updateFrom += "<option value = '" + value.id + "'selected>" + value.account_name + "</option>";
        }
        else {
            updateFrom += "<option value = '" + value.id + "'>" + value.account_name + "</option>";
        }


    });
    updateFrom += "</select>";
    updateFrom += "</div>";
    updateFrom += "</div>";
    updateFrom += "</div>";

    //Parent Head Name



    //radio button start
    updateFrom += "<div class='col-md-12 mt-3'>";
    updateFrom += "<div class='row mt-2'>";
    updateFrom += "<label for='bed_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Status</label>";
    updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

    updateFrom += "<input class='form-check-input m-1' type='radio' name='is_active' value='1' id='is_active' checked >";
    updateFrom += "<label class='form-check-label' for='is_active'>Active</label>";
    updateFrom += "<input class='form-check-input m-1' type='radio' name='is_active' value='0' id='is_active' >";
    updateFrom += "<label class='form-check-label' for='is_active'>Disable</label>";

    updateFrom += "</div>";
    updateFrom += "</div>";
    updateFrom += "</div>";
    //radio button End



    updateFrom += "<input type='hidden' name='id' value=" + UpdateCoaData.coaDetail.id + " id='head_level'>";


    if ((Number(UpdateCoaData.coaDetail.head_level) == 3) && (Number(UpdateCoaData.coaDetail.acc_type_id) == 1)) {
        //Assect radio button start
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";
        updateFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Asset Type</label>";
        updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        if (UpdateCoaData.coaDetail.is_stock == 1) {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_stock' checked required id='is_stock' >";
        } else {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_stock' required id='is_stock'>";
        }


        updateFrom += "<label class='form-check-label' for='is_stock'>Is Stock</label>";

        if (UpdateCoaData.coaDetail.is_fixed_asset_schedule == 1) {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_fixed_asset' checked id='is_fixed_asset' >";
        } else {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_fixed_asset' id='is_fixed_asset'>";
        }

        updateFrom += "<label class='form-check-label' for='is_fixed_asset'>Is Fixed Asset</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";
        //Assect radio button End

    }



    if ((Number(UpdateCoaData.coaDetail.head_level) == 3) && ((Number(UpdateCoaData.coaDetail.acc_type_id) == 4) || (Number(UpdateCoaData.coaDetail.acc_type_id) == 5))) {
        //Assect radio button start
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";
        updateFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Asset Type</label>";
        updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";



        if (UpdateCoaData.coaDetail.is_fixed_asset_schedule == 1) {
            updateFrom += "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_fixed_asset' checked  id='is_fixed_asset'>";
        } else {
            updateFrom += "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_fixed_asset' id='is_fixed_asset'>";
        }

        updateFrom += "<label class='form-check-label' for='is_fixed_asset'>Is Fixed Asset</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";
        //Assect radio button End

    }


    if ((Number(UpdateCoaData.coaDetail.head_level) == 4) && (Number(UpdateCoaData.coaDetail.acc_type_id) == 1)) {

        //Assect radio button start
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";

        updateFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-4 col-xl-4 fw-semibold required'>Asset Type</label>";
        updateFrom += "<div class='col-sm-8 col-md-8 col-xl-8 mt-2'>";

        if (UpdateCoaData.coaDetail.is_stock == 1) {
            updateFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_stock' id = 'editasset_is_stock' checked required >";
        } else {
            updateFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_stock' id = 'editasset_is_stock' required >";
        }
        updateFrom += "<label class='form-check-label' for='editasset_is_stock'>Is Stock</label>";

        if (UpdateCoaData.coaDetail.is_fixed_asset_schedule == 1) {
            updateFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'editasset_is_fixed_asset' checked >";
        } else {
            updateFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'editasset_is_fixed_asset'  >";
        }
        updateFrom += "<label class='form-check-label' for='editasset_is_fixed_asset'>Is Fixed Asset</label>";


        if (UpdateCoaData.coaDetail.is_subtype == 1) {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'editasset_is_subtype' checked >";
        } else {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'editasset_is_subtype' >";
        }
        updateFrom += "<label class='form-check-label' for='editasset_is_subtype'>Is Sub Type</label>";

        if (UpdateCoaData.coaDetail.is_cash_nature == 1) {
            updateFrom += "<input class='form-check-input mr-3 m-1' type='radio' name='asset_type' value='is_cash' id = 'editasset_is_cash' checked >";
        } else {
            updateFrom += "<input class='form-check-input mr-3 m-1' type='radio' name='asset_type' value='is_cash' id = 'editasset_is_cash' >";
        }
        updateFrom += "<label class='form-check-label' for='editasset_is_cash'>Is Cash Nature</label>";

        if (UpdateCoaData.coaDetail.is_bank_nature == 1) {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_bank'  id = 'editasset_is_bank' checked>";
        } else {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_bank'  id = 'editasset_is_bank' >";
        }
        updateFrom += "<label class='form-check-label' for='editasset_is_bank'>Is Bank Nature</label>";


        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";

        //Assect radio button End


        if ((UpdateCoaData.coaDetail.asset_code != null) || (UpdateCoaData.coaDetail.depreciation_rate != null)) {

            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2' id='editfixedAssetField'>";
            //Fixed Asset Code start
            updateFrom += "<div class='row'>";
            updateFrom += "<label for='asset_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Fixed Asset Code </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
            if (UpdateCoaData.coaDetail.asset_code != null) {
                updateFrom += "<input type='text' class='form-control' id='asset_code' name='asset_code' value='" + UpdateCoaData.coaDetail.asset_code + "' >";
            } else {
                updateFrom += "<input type='text' class='form-control' id='asset_code' name='asset_code' >";
            }

            updateFrom += "</div>";
            updateFrom += "</div>";

            updateFrom += "<div class='row'>";
            updateFrom += "<label for='depreciation_rate' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Depraciation Rate % </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
            if (UpdateCoaData.coaDetail.depreciation_rate != null) {
                updateFrom += "<input type='text' class='form-control' id='depreciation_rate' name='depreciation_rate' value='" + UpdateCoaData.coaDetail.depreciation_rate + "' >";
            } else {
                updateFrom += "<input type='text' class='form-control' id='depreciation_rate' name='depreciation_rate' >";
            }

            updateFrom += "</div>";
            updateFrom += "</div>";

            //Fixed Asset Code End
            updateFrom += "</div>";
            updateFrom += "</div>";
        }

        else {

            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 d-none' id='editfixedAssetField'>";
            //Fixed Asset Code start
            updateFrom += "<div class='row'>";
            updateFrom += "<label for='asset_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Fixed Asset Code </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";

            updateFrom += "<input type='text' class='form-control' id='asset_code' name='asset_code' >";

            updateFrom += "</div>";
            updateFrom += "</div>";

            updateFrom += "<div class='row'>";
            updateFrom += "<label for='depreciation_rate' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Depraciation Rate % </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";

            updateFrom += "<input type='text' class='form-control' id='depreciation_rate' name='depreciation_rate' >";

            updateFrom += "</div>";
            updateFrom += "</div>";

            //Fixed Asset Code End
            updateFrom += "</div>";
            updateFrom += "</div>";
        }

        if (UpdateCoaData.coaDetail.subtype_id != null) {


            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 ' id='editSubtypeAssetField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom += "<div class='col-md-4'>Sub Type</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
            var editsubtypeAcc = $("#accsubType").val();
            var editacSubtype = JSON.parse(editsubtypeAcc);
            $.each(editacSubtype, function (key, editvalue) {
                if (UpdateCoaData.coaDetail.subtype_id == editvalue.id) {
                    updateFrom += "<option value = '" + editvalue.id + "' selected>" + editvalue.subtype_name + "</option>";
                }
                else {
                    updateFrom += "<option value = '" + editvalue.id + "'>" + editvalue.subtype_name + "</option>";
                }

            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";

        }

        else {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 d-none' id='editSubtypeAssetField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom += "<div class='col-md-4'>Sub Type</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
            var editsubtypeAcc = $("#accsubType").val();
            var editacSubtype = JSON.parse(editsubtypeAcc);
            $.each(editacSubtype, function (key, editvalue) {
                updateFrom += "<option value = '" + editvalue.id + "'>" + editvalue.subtype_name + "</option>";
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";
        }

    }

    if ((Number(UpdateCoaData.coaDetail.head_level) == 4) && ((Number(UpdateCoaData.coaDetail.acc_type_id) == 2) || (Number(UpdateCoaData.coaDetail.acc_type_id) == 3))) {

        // Check box Sub Type
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";

        updateFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-4 col-xl-4 fw-semibold required'>Asset Type</label>";
        updateFrom += "<div class='col-sm-8 col-md-8 col-xl-8 mt-2'>";

        if (UpdateCoaData.coaDetail.is_subtype == 1) {
            updateFrom += "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_subtype'  id = 'editexpin_is_subtype' checked >";
        } else {
            updateFrom += "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_subtype'  id = 'editexpin_is_subtype' >";
        }
        updateFrom += "<label class='form-check-label' for='asset_type'>Is Sub Type</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";
        // Check box Sub Type

        if (UpdateCoaData.coaDetail.subtype_id != null) {

            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 ' id='editSubtypeExpIncField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom += "<div class='col-md-4'>Sub Type</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
            var editIEsubtypeAcc = $("#accsubType").val();
            var editEIacSubtype = JSON.parse(editIEsubtypeAcc);
            $.each(editEIacSubtype, function (key, editIEvalue) {
                if (UpdateCoaData.coaDetail.subtype_id == editIEvalue.id) {
                    updateFrom += "<option value = '" + editIEvalue.id + "' selected>" + editIEvalue.subtype_name + "</option>";
                }
                else {
                    updateFrom += "<option value = '" + editIEvalue.id + "'>" + editIEvalue.subtype_name + "</option>";
                }

            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";

        }

        else {

            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 d-none' id='editSubtypeExpIncField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom += "<div class='col-md-4'>Sub Type</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
            var editIEsubtypeAcc = $("#accsubType").val();
            var editEIacSubtype = JSON.parse(editIEsubtypeAcc);
            $.each(editEIacSubtype, function (key, editIEvalue) {
                updateFrom += "<option value = '" + editIEvalue.id + "'>" + editIEvalue.subtype_name + "</option>";
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";

        }

    }


    if ((Number(UpdateCoaData.coaDetail.head_level) == 4) && ((Number(UpdateCoaData.coaDetail.acc_type_id) == 4) || (Number(UpdateCoaData.coaDetail.acc_type_id) == 5))) {

        //Assect radio button start
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";

        updateFrom += "<label for='asset_type' class='col-form-label col-sm-4 col-md-4 col-xl-4 fw-semibold required'>Asset Type</label>";
        updateFrom += "<div class='col-sm-8 col-md-8 col-xl-8 mt-2'>";


        if (UpdateCoaData.coaDetail.is_fixed_asset_schedule == 1) {
            updateFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'editlieq_is_fixed_asset' checked >";
        } else {
            updateFrom += "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'editlieq_is_fixed_asset'  >";
        }
        updateFrom += "<label class='form-check-label' for='editlieq_is_fixed_asset'>Is Fixed Asset</label>";


        if (UpdateCoaData.coaDetail.is_subtype == 1) {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'editlieq_is_subtype' checked >";
        } else {
            updateFrom += "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'editlieq_is_subtype' >";
        }
        updateFrom += "<label class='form-check-label' for='editlieq_is_subtype'>Is Sub Type</label>";


        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";

        //Assect radio button End

        if (UpdateCoaData.coaDetail.is_fixed_asset_schedule == 1) {

            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2' id='editfixedAssetLEField'>";
            //Fixed Asset Code start
            updateFrom += "<div class='row'>";
            updateFrom += "<label for='dep_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Depraciation Code </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
            if (UpdateCoaData.coaDetail.dep_code != null) {
                updateFrom += "<input type='text' class='form-control' id='dep_code' name='dep_code' value='" + UpdateCoaData.coaDetail.dep_code + "' >";
            } else {
                updateFrom += "<input type='text' class='form-control' id='dep_code' name='dep_code' >";
            }

            updateFrom += "</div>";
            updateFrom += "</div>";

            //Fixed Asset Code End
            updateFrom += "</div>";
            updateFrom += "</div>";
        }

        else {

            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 d-none' id='editfixedAssetLEField'>";
            //Fixed Asset Code start
            updateFrom += "<div class='row'>";
            updateFrom += "<label for='dep_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Depraciation Code </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
            updateFrom += "<input type='text' class='form-control' id='dep_code' name='dep_code' >";
            updateFrom += "</div>";
            updateFrom += "</div>";

            //Fixed Asset Code End
            updateFrom += "</div>";
            updateFrom += "</div>";
        }







        if (UpdateCoaData.coaDetail.subtype_id != null) {

            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 ' id='editSubtypeLibEqField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom += "<div class='col-md-4'>Sub Type</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
            var editELsubtypeAcc = $("#accsubType").val();
            var editELacSubtype = JSON.parse(editELsubtypeAcc);
            $.each(editELacSubtype, function (key, editELvalue) {
                if (UpdateCoaData.coaDetail.subtype_id == editELvalue.id) {
                    updateFrom += "<option value = '" + editELvalue.id + "' selected>" + editELvalue.subtype_name + "</option>";
                }
                else {
                    updateFrom += "<option value = '" + editELvalue.id + "'>" + editELvalue.subtype_name + "</option>";
                }

            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";

        }

        else {

            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 d-none' id='editSubtypeLibEqField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom += "<div class='col-md-4'>Sub Type</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom += "<select id='subtype_id' name='subtype_id' class='form-select'>";
            var editELsubtypeAcc = $("#accsubType").val();
            var editELacSubtype = JSON.parse(editELsubtypeAcc);
            $.each(editELacSubtype, function (key, editELvalue) {
                updateFrom += "<option value = '" + editELvalue.id + "'>" + editELvalue.subtype_name + "</option>";
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";

        }



    }




    var stringifiedObj = JSON.stringify(UpdateCoaData);
    //  button text
    updateFrom += "<div class='col-md-12'>";
    updateFrom += "<div class='row'>";
    if (Number(UpdateCoaData.coaDetail.head_level) == 4) {
        updateFrom +=
            "<div class='form-group mt-3'><button type='submit' class='btn btn-primary'>Update</button><a class='btn btn-danger mx-2' onclick='loadDelfrom(" +
            stringifiedObj +
            ")'>Delete</a></div>";
    } else {
        updateFrom +=
            "<div class='form-group mt-3'><button type='submit' class='btn btn-primary'>Update</button><a class='btn btn-secondary mx-2' onclick='addLoadFromForAll(" +
            stringifiedObj +
            ")'>Create</a><a class='btn btn-danger mx-2' onclick='loadDelfrom(" +
            stringifiedObj +
            ")'>Delete</a></div>";
    }
    updateFrom += "</div>";
    updateFrom += "</div>";
    //  button text

    $("#addCoafrom").html('');
    $("#editCoafrom").html('');
    $("#delCoafrom").html('');

    return updateFrom
}



// Asset 4 lable add

$(document).on('click', '#asset_is_fixed_asset', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#fixedAssetField").removeClass("d-none");
        $("#SubtypeAssetField").addClass("d-none");
    }
});

$(document).on('click', '#asset_is_stock', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#SubtypeAssetField").addClass("d-none");
    }

});


$(document).on('click', '#asset_is_subtype', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#SubtypeAssetField").removeClass("d-none");

    }
});

$(document).on('click', '#asset_is_cash', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#SubtypeAssetField").addClass("d-none");
    }

});

$(document).on('click', '#asset_is_bank', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#SubtypeAssetField").addClass("d-none");
    }

});

// Asset 4 lable add

// Expense 4 lable add

$(document).on('click', '#expense_is_subtype', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#SubtypeAssetFieldExpense").removeClass("d-none");

    }
    if (isChecked == false) {
        $("#SubtypeAssetFieldExpense").addClass("d-none");

    }
});

// Expense 4 lable add

// Liabilities & Share Holder  4 lable add

$(document).on('click', '#le_is_fixed_asset', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#lefixedAssetField").removeClass("d-none");
        $("#leSubtypeAssetField").addClass("d-none");
    }
});

$(document).on('click', '#le_is_subtype', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#lefixedAssetField").addClass("d-none");
        $("#leSubtypeAssetField").removeClass("d-none");
    }
});

// Liabilities & Share Holder  4 lable add

// Asset 4th Lable Edit

$(document).on('click', '#editasset_is_stock', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#editfixedAssetField").addClass("d-none");
        $("#editSubtypeAssetField").addClass("d-none");
    }
});


$(document).on('click', '#editasset_is_cash', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#editfixedAssetField").addClass("d-none");
        $("#editSubtypeAssetField").addClass("d-none");
    }
});


$(document).on('click', '#editasset_is_bank', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#editfixedAssetField").addClass("d-none");
        $("#editSubtypeAssetField").addClass("d-none");
    }
});


$(document).on('click', '#editasset_is_fixed_asset', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#editfixedAssetField").removeClass("d-none");
        $("#editSubtypeAssetField").addClass("d-none");
    }
});

$(document).on('click', '#editasset_is_subtype', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#editfixedAssetField").addClass("d-none");
        $("#editSubtypeAssetField").removeClass("d-none");
    }
});


// Asset 4th Lable Edit

// Income Expense 4th Lable Edit

$(document).on('click', '#editexpin_is_subtype', function () {

    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#editSubtypeExpIncField").removeClass("d-none");
    }
    if (isChecked == false) {
        $("#editSubtypeExpIncField").addClass("d-none");
    }
});
// Income Expense 4th Lable Edit

// Liablity & Equity 4th Lable Edit

$(document).on('click', '#editlieq_is_fixed_asset', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#editSubtypeLibEqField").addClass("d-none");
        $("#editfixedAssetLEField").removeClass("d-none");
    }
});


$(document).on('click', '#editlieq_is_subtype', function () {
    var isChecked = $(this).is(':checked');
    if (isChecked == true) {
        $("#editfixedAssetLEField").addClass("d-none");
        $("#editSubtypeLibEqField").removeClass("d-none");
    }
});

// Liablity & Equity 4th Lable Edit


function accLoader($show = "show") {
    if ($show == "show") {
        $("#coa-loader").show();
        $("#coa-form").hide();
    } else {
        // add 500 milisecond delay to loader hide
        setTimeout(function () {
            $("#coa-loader").hide();
            $("#coa-form").show();
        }, 500);
    }
}
