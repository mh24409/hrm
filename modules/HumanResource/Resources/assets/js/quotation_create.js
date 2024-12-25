var count = 2;
    var limits = 500;

    "use strict";
    var total_quote_items = $('#total_quote_items').val();

    var count_quote_item = parseInt(total_quote_items); // count_quote_item will work like count

    //Add Quote input field
    function addQuoteItem(e) {

        count_quote_item++;

        var description_material_placeholder = $('#description_material_placeholder').val();
        var unit_list = $('#unit_list').val();
        var tr = '<td><textarea class="form-control" name="material_description[]" id="material_description" rows="2" placeholder="'+description_material_placeholder+'" required=""></textarea></td>'+
        '<td><select name="unit_id[]" class="form-control" required=""><option value=""> Select option</option>'+unit_list+'</select> </td>'+
        '<td class=""><input type="number" onkeyup="calculate_quote(' + count_quote_item + ');" onchange="calculate_quote(' + count_quote_item + ');" id="quantity_' + count_quote_item + '" class="form-control text-end" name="quantity[]" step="any" placeholder="0.00"  required  min="0"/></td>'+
        '<td class=""><input type="number" id="price_per_unit_' + count_quote_item + '" onkeyup="calculate_quote(' + count_quote_item + ');" onchange="calculate_quote(' + count_quote_item + ');" class="form-control text-end" name="unit_price[]" step="any" placeholder="0.00" required/></td>'+
        '<td class=""><input type="text" id="total_price_' + count_quote_item + '" class="form-control text-end total_item_price" name="total[]" readonly placeholder="0.00" value="0.00" required/></td>'+
        '<td> <a  id="add_purchase_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addQuoteItem('+"quote_item"+')"><i class="fa fa-plus-square" aria-hidden="true"></i></a> <a class="btn btn-danger btn-sm"  value="" onclick="deleteQuoteRow(this)" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
        count_quote_item == limits ? alert("You have reached the limit of adding " + count_quote_item + " inputs") : $("tbody#quote_item").append("<tr>" + tr + "</tr>");
        
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
    }

    "use strict";
    function deleteQuoteRow(e) {
        var t = $("#quotation_table > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }

        calculate_quote_sum()
    }

    "use strict";
    function calculate_quote(sl) {
    
        var gr_tot = 0;
        var item_qty    = $("#quantity_"+sl).val();
        var price_unit = $("#price_per_unit_"+sl).val();

        var total_price     = item_qty * price_unit;
        $("#total_price_"+sl).val(total_price.toFixed(2));

        //Total Item Price
        $(".total_item_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        $("#Total").val(gr_tot.toFixed(2,2));
    }

    //Calculate summation
    "use strict";
    function calculate_quote_sum() {
        var total = 0;
        //Total Price
        $(".total_item_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (total += parseFloat(this.value))
        });
        $("#Total").val(total.toFixed(2,2));
    }

    // Function to preview image
    $(document).on('change', '#signature', function(){
        var file = $(this)[0].files[0];
        var reader = new FileReader();
        reader.onload = function(e){
            $('#output').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    });