var count = 2;
    var limits = 500;

    "use strict";
    //Add bid input field
    function addBidItem(e) {
        var material_description = $('#material_description').val();
        var unit_list = $('#unit_list').val();
        var company = $('#company').val();
        var reason_of_choosing = $('#reason_of_choosing').val();
        var remarks = $('#remarks').val();
        var vendor_company_name = $('#vendor_company_name').val();

        var total_bid_items = $('#total_bid_item').val();
        var count_bid_item = parseInt(total_bid_items); // count_bid_item will work like count

        count_bid_item++;

        $('#total_bid_item').val(count_bid_item);

        var t = '<td class=""><input type="text" class="form-control" name="company[]" placeholder="'+company+'" value="'+vendor_company_name+'" readonly/></td>'+
        '<td><textarea class="form-control" name="material_description[]" id="description" rows="2" placeholder="'+material_description+'" required=""></textarea>'+
        '<td class=""><input type="text" class="form-control" name="choosing_reason[]" placeholder="'+reason_of_choosing+'" required/></td>'+
        '<td class=""><input type="text" class="form-control" name="remarks[]" placeholder="'+remarks+'" required/></td>'+
        '<td><select name="unit_id[]" class="form-control" required=""><option value=""> Select Unit</option>'+unit_list+'</select> </td>'+
        '<td class=""><input type="number" onkeyup="calculate_bid(' + count_bid_item + ');" onchange="calculate_bid(' + count_bid_item + ');" id="quantity_' + count_bid_item + '" class="form-control text-end" name="quantity[]" placeholder="0.00"  required  min="0"/></td>'+
        '<td class=""><input type="number" id="price_per_unit_' + count_bid_item + '" onkeyup="calculate_bid(' + count_bid_item + ');" onchange="calculate_bid(' + count_bid_item + ');" class="form-control text-end" name="unit_price[]" placeholder="0.00" required/></td>'+
        '<td class=""><input type="text" id="total_price_' + count_bid_item + '" class="form-control text-end total_item_price" name="total[]" readonly placeholder="0.00" value="0.00" required/></td>'+
        '<td> <a  id="add_bid_item" class="btn btn-info btn-sm" name="add-bid-item" onclick="addBidItem('+"bid_analysis_item"+')"><i class="fa fa-plus-square" aria-hidden="true"></i></a> <a class="btn btn-danger btn-sm"  value="" onclick="deleteBidItemRow(this)" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
        
        count_bid_item == limits ? alert("You have reached the limit of adding " + count_bid_item + " inputs") : $("tbody#bid_analysis_item").append("<tr>" + t + "</tr>");

        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
    }

    "use strict";
    function deleteBidItemRow(e) {
        var t = $("#bid_analysis_table > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
        calculate_bid_sum()
    }

    "use strict";
    function calculate_bid(sl) {
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
    function calculate_bid_sum() {
        var total = 0;
        //Total Price
        $(".total_item_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (total += parseFloat(this.value))
        });
        $("#Total").val(total.toFixed(2,2));
    }

    /*Committee list starts*/
    "use strict";
    //Add committeelist input field
    function addcommittee(e) {
        var date_lang = $('#date_lang').val();
        var committee_list = $('#committee_list').val();
        var total_committee_list = $('#total_committee_list').val();
        var count_committee_list = parseInt(total_committee_list);
        count_committee_list++;
        var assetUrl = $("#signature_path").val();
        $('#total_committee_list').val(count_committee_list);

        var t ='<td><select name="committee_id[]" class="form-control" required="" onchange="loadCommitteImage(this,'+count_committee_list+')"><option value=""> Select Committee</option>'+committee_list+'</select> </td>'+
        '<td class=""><input type="hidden" name="signature[]" id="sign_image_'+count_committee_list+'" value="">'+
        '<img src="' + assetUrl + '" alt="logo" id="output_'+count_committee_list+'" width="300" style="height: 120px !important;"></td>'+
        '<td class=""><input type="text" id="date" class="form-control datepicker_committee" name="date[]" placeholder="'+date_lang+'" required autocomplete="off"/></td>'+
        '<td class=""><a  id="add_committee_item" class="btn btn-info btn-sm" name="add-invoice-item" onClick="addcommittee('+"committee_item"+')" ><i class="fa fa-plus-square" aria-hidden="true"></i></a> '+
            '<a class="btn btn-danger btn-sm" onclick="deleteCommitteeRow(this)"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
        
        count_committee_list == limits ? alert("You have reached the limit of adding " + count_committee_list + " inputs") : $("tbody#committee_item").append("<tr>" + t + "</tr>");

        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });

        $(".datepicker_committee").datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });
    }

    "use strict";
    function deleteCommitteeRow(e) {
        var t = $("#committee_table > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
    }

    "use strict"; 
    var loadCommitteImage = function(committee,sl) {
        commitee_id = committee.value;

        var get_committee = $("#get_committee").val();

        $.ajax({
            type: "post",
            url: get_committee,
            data: {
                commitee_id: commitee_id
            },
            success: function(data)
            {
                var json = JSON.parse(data);

                if(commitee_id !=''){
                    var output = document.getElementById('output_'+sl);

                    var asset_storage = $("#asset_storage").val();

                    output.src = asset_storage + '/' + json.signature;
                    $('#sign_image_'+sl).val(json.signature);
                }else{
                  alert('Please Select Committee');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    };

    $(document).ready(function() {
        $(".datepicker_committee").datetimepicker({
            timepicker: false,
            format: 'Y-m-d'
        });
    });