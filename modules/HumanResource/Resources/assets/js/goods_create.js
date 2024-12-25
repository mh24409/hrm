var count = 2;
        var limits = 500;

        "use strict"

        function good_receive_purchase_item() {
            var purchase_order = $('#purchase_order option:selected').val();

            var get_purchase_items = $("#get_purchase_items").val();

            $.ajax({
                type: "post",
                url: get_purchase_items,
                data: {
                    purchase_order_id: purchase_order
                },
                success: function(html) {
                    if (purchase_order != '') {
                        $('#good_received_table').html(html);
                    } else {
                        alert('Please Select Quotation');
                    }

                    $("select.form-control:not(.dont-select-me)").select2({
                        placeholder: "Select option",
                        allowClear: true
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

            /*Getting Purchase info to fill vendor_name and vendor_id inside good received*/
            var data = getPurchaseData(purchase_order);
            var jsondata = JSON.parse(data);

            if (jsondata) {
                $('#vendor_name').val(jsondata.company_name);
                $('#vendor_id').val(jsondata.vendor_id);
            }
        }

        "use strict"

        function getPurchaseData(id) {

            var get_purchase_info = $("#get_purchase_info").val();

            var result = "";
            $.ajax({
                type: "post",
                url: get_purchase_info,
                data: {
                    purchase_order_id: id
                },
                async: false,
                success: function(data) {
                    result = data;
                }
            });
            return result;
        }

        "use strict";

        function goodrecvpaymentypeselectExpns() {

            var hdcode = $('#paytype').val();
            var base_url = $("#base_url").val();
            var csrf_test_name = $('[name="csrf_test_name"]').val();
            var paymentcode = '#headcode';

            $.ajax({
                type: "POST",
                url: base_url + "procurements/Procurement_good_received/retrieve_paytypedata",
                data: {
                    paytype: hdcode,
                    csrf_test_name: csrf_test_name
                },
                cache: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    $(paymentcode).html(obj.headcode);
                }
            });

        }


        "use strict";
        //Add purchase order input fields
        function addGoodRecvItem(e) {
            var description_material = $('#material_description').val();
            var unit_list = $('#unit_list').val();
            var company = $('#company').val();
            var vendor_company_name = $('#vendor_company_name').val();
            var total_good_rcv_items = $('#total_good_rcv_items').val();
            var count_good_rcv_item = parseInt(total_good_rcv_items); // count_bid_item will work like count
            count_good_rcv_item++;

            $('#total_good_rcv_items').val(count_good_rcv_item);

            var t =
                '<td><textarea class="form-control" name="material_description[]" id="description" rows="2" placeholder="' +
                description_material + '" required=""></textarea>' +
                '<td><select name="unit_id[]" class="form-control" required=""><option value=""> Select Unit</option>' +
                unit_list + '</select> </td>' +
                '<td class=""><input type="number" id="quantity_' + count_good_rcv_item +
                '" onkeyup="calculate_good_receive(' + count_good_rcv_item + ');" onchange="calculate_good_receive(' +
                count_good_rcv_item +
                ');" class="form-control text-end" name="quantity[]" placeholder="0.00"  required  min="0"/></td>' +
                '<td class=""><input type="number" id="price_per_unit_' + count_good_rcv_item +
                '" onkeyup="calculate_good_receive(' + count_good_rcv_item + ');" onchange="calculate_good_receive(' +
                count_good_rcv_item +
                ');" class="form-control text-end sub_total_item_price" name="unit_price[]" placeholder="0.00" required/></td>' +
                '<td class=""><input type="text" id="total_price_' + count_good_rcv_item +
                '" class="form-control text-end total_item_price" name="total[]" readonly placeholder="0.00" value="0.00" required/></td>' +
                '<td><a class="btn btn-danger btn-sm"  value="" onclick="deleteGoodRecvItemRow(this)" ><i class="fa fa-close" aria-hidden="true"></i></a></td>';

            count_good_rcv_item == limits ? alert("You have reached the limit of adding " + count_good_rcv_item +
                " inputs") : $("tbody#good_received_item").append("<tr>" + t + "</tr>");

            $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });
        }

        "use strict";

        function deleteGoodRecvItemRow(e) {
            var t = $("#good_received_table > tbody > tr").length;
            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }
            calculateGoodRecvSum()
        }

        //Calculate Good Received items
        "use strict";

        function calculate_good_receive(sl) {
            var gr_tot = 0;
            var dis = 0;
            var item_qty = $("#quantity_" + sl).val();
            var price_unit = $("#price_per_unit_" + sl).val();
            var total_price = item_qty * price_unit;
            $("#total_price_" + sl).val(total_price.toFixed(2));
            $(".discount").each(function() {
                isNaN(this.value) || 0 == this.value.length || (dis += parseFloat(this.value))
            });
            //Grand Total Item Price
            $(".total_item_price").each(function() {
                isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
            });
            $("#Total").val(gr_tot.toFixed(2, 2));
            var grandtotal = gr_tot - dis;
            $("#grandTotal").val(grandtotal.toFixed(2, 2));
        }

        //Calculate summation
        "use strict";

        function calculateGoodRecvSum() {

            var total = 0;
            var dis = 0;

            $(".discount").each(function() {
                isNaN(this.value) || 0 == this.value.length || (dis += parseFloat(this.value))
            });

            //Grand Total Item Price
            $(".total_item_price").each(function() {
                isNaN(this.value) || 0 == this.value.length || (total += parseFloat(this.value))
            });

            $("#Total").val(total.toFixed(2, 2));

            var grandtotal = total - dis;
            $("#grandTotal").val(grandtotal.toFixed(2, 2));
        }

        // Function to preview image
        $(document).on('change', '#received_by_signature', function() {
            var file = $(this)[0].files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#output').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });