var count = 2;
    var limits = 500;
   
    "use strict";
    function addpruduct(e) {
        var dm_placeholder_title = $('#description_material_placeholder').val();
        var unit_list = $('#unit_list').val();

        var t = '<td><textarea class="form-control" name="material_description[]" id="description" rows="2" placeholder="'+dm_placeholder_title+'" required=""></textarea></td>'+
        '<td><select name="unit_id[]" class="form-control" required=""><option value=""> Select Option</option>'+unit_list+'</select> </td>'+
        '<td class=""><input type="number"  class="form-control text-end" name="quantity[]" step="any" placeholder="0.00"  required  min="0"/></td>'+
        '<td> <a  id="add_purchase_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addpruduct('+"request_item"+')"><i class="fa fa-plus-square" aria-hidden="true"></i></a> <a class="btn btn-danger btn-sm"  value="" onclick="deleteRow(this)" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
        count == limits ? alert("You have reached the limit of adding " + count + " inputs") : $("tbody#request_item").append("<tr>" + t + "</tr>");
        count++;

        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
    }

    "use strict";
    function deleteRow(e) {
        var t = $("#request_table > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
    }