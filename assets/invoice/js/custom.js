$(document).ready(function() {
	// My POS.cc Custom js
	//finish sales add item
	$(document).on('click', '#sales_add_item', function(event) {
		var sales_no = $('#sales_no').val();
		var sales_date = $('#sales_date').val();
		var customer_id = $('#customer_id').val();
		var item_id = $('#item_id').val();
		var quantity = $('#quantity').val();
		var price = $('#price').val();
		var notes = $('#notes').val();
		if(! sales_no ){
			alert('Please give a sales no.');
			return false;
		}
		if(! item_id ){
			alert('Please select an Item.');
			return false;
		}
		if(! customer_id ){
			alert('Please select a Customer.');
			return false;
		}
		if(! quantity ){
			alert('Quantity must be greated than zero.');
			return false;
		}

		$.ajax({
			type: "POST",
			url: "inventory/sales_save",
			data: {sales_no: sales_no, sales_date: sales_date, customer_id: customer_id, item_id: item_id, quantity: quantity, price: price, notes: notes},
			success: function(msg) {
				//alert(msg);
				if( msg == "insufficient"){
					alert("Insufficiant Stock!");
				} else {
					$("#sales_details").children().remove();
					$("#sales_details").html(msg);
					$("#sample_1").dataTable({
						"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
						"sPaginationType": "bootstrap",
						"oLanguage": {
							"sLengthMenu": "_MENU_ records per page",
							"oPaginate": {
								"sPrevious": "Prev",
								"sNext": "Next"
							}
						},
						"aoColumnDefs": [{
							'bSortable': false,
							'aTargets': [0]
						}]
					});
				}

			}
		});
		$('#sales_no').attr('disabled', true);
		$('#sales_date').attr('disabled', true);
		$('#quantity').val('');
		$('#price').val('');
		$('#quantity').focus();
	});
	//finish sales item delete
	$(document).on('click', '.sales_item_delete', function(event) {
		if (!confirm("Are you sure you want to delete this item?")) {
			return false;
		}
		var sales_no = $('#sales_no').val();
		var item_id = $(this).prev().val();
		$.ajax({
			type: "POST",
			url: "inventory/sales_item_delete",
			data: {sales_no: sales_no, item_id: item_id},
			success: function(msg) {
				$("#sales_details").children().remove();
				$("#sales_details").html(msg);
				$("#datatables").dataTable();
			}
		});
	});
	//finish sales complete
	$(document).on('click', '#sales_complete', function(event) {
		$('#sales_complete').val('Procesing...');		
		$('#sales_complete').attr('disabled','disabled');		
		var oTable1 = $('#itemGrid').dataTable();        
        var sData1 = oTable1.fnGetData();

        var oTable2 = $('#itemGrid2').dataTable();        
        var sData2 = oTable2.fnGetData();

		var sales_no = $('#sales_no').val();
		var discount_amount = $('#discount').val();
		var subtotal01 = $('#subtotal01').val();
		var subtotal02 = $('#subtotal02').val();
		var billamount = $('#billamount').val();
		var paid_amount = $('#payamount').val();
		var returnamount = $('#returnamount').val();
		var dueamount = $('#dueamount').val();
		var customer_id = $('#customer_id').val();
		var emp_id = $('#emp_id').val();
		var sales_date = $('#sales_date').val();
		var notes = $('#notes').val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "inventory/sales_complete",
			data: {sales_no: sales_no, paid_amount: paid_amount, sales_date: sales_date, customer_id: customer_id, emp_id: emp_id, notes: notes, rows1: sData1, rows2: sData2, subtotal01: subtotal01, subtotal02: subtotal02, billamount: billamount, returnamount: returnamount, dueamount: dueamount, discount_amount: discount_amount},
			success: function(data) {
				if(data.errmsg.length > 0){
					alert(data.errmsg);
					$('#sales_complete').val('Complete Sales Entry');
					$('#sales_complete').removeAttr('disabled');
				} else if(data.next.length > 0){
					window.location.replace(data.next);
				}
			}
		});
	});
	//finish sales return add item
	$(document).on('click', '#sales_return_add_item', function(event) {
		var sales_return_no = $('#sales_return_no').val();
		var sales_return_date = $('#sales_return_date').val();
		var customer_id = $('#customer_id').val();
		var item_id = $('#item_id').val();
		var quantity = $('#quantity').val();
		var price = $('#price').val();
		var notes = $('#notes').val();
		if(! sales_return_no ){
			alert('Please give a sales return no.');
			return false;
		}
		if(! item_id ){
			alert('Please select an Item.');
			return false;
		}
		if(! customer_id ){
			alert('Please select a Customer.');
			return false;
		}
		if(! quantity ){
			alert('Quantity must be greated than zero.');
			return false;
		}

		$.ajax({
			type: "POST",
			url: "inventory/sales_return_save",
			data: {sales_return_no: sales_return_no, sales_return_date: sales_return_date, customer_id: customer_id, item_id: item_id, quantity: quantity, price: price, notes: notes},
			success: function(msg) {
				//alert(msg);
				$("#sales_return_details").children().remove();
				$("#sales_return_details").html(msg);
				$("#sample_1").dataTable();

			}
		});
		$('#sales_return_no').attr('disabled', true);
		$('#sales_return_date').attr('disabled', true);
		$('#quantity').val('');
		$('#price').val('');
		$('#quantity').focus();
	});
	//finish sales return item delete
	$(document).on('click', '.sales_return_item_delete', function(event) {
		if (!confirm("Are you sure you want to delete this item?")) {
			return false;
		}
		var sales_return_no = $('#sales_return_no').val();
		var item_id = $(this).prev().val();
		$.ajax({
			type: "POST",
			url: "inventory/sales_return_item_delete",
			data: {sales_return_no: sales_return_no, item_id: item_id},
			success: function(msg) {
				$("#sales_return_details").children().remove();
				$("#sales_return_details").html(msg);
				$("#datatables").dataTable();
			}
		});
	});
	//finish sales return complete
	$(document).on('click', '#sales_return_complete', function(event) {
		var sales_return_no = $('#sales_return_no').val();
		var paid_amount = $('#paid_amount').val();
		var customer_id = $('#customer_id').val();
		var emp_id = $('#emp_id').val();
		var sales_return_date = $('#sales_return_date').val();
		var notes = $('#notes').val();
		$.ajax({
			type: "POST",
			url: "inventory/sales_return_complete",
			data: {sales_return_no: sales_return_no, paid_amount: paid_amount, sales_return_date: sales_return_date, customer_id: customer_id, emp_id: emp_id, notes: notes},
			success: function(msg) {
				window.location.replace(msg);
			}
		});
	});

	//finish purchase add item
	$(document).on('click', '#purchase_add_item', function(event) {
		var purchase_no = $('#purchase_no').val();
		var purchase_date = $('#purchase_date').val();
		var supplier_id = $('#supplier_id').val();
		var item_id = $('#item_id').val();
		var quantity = $('#quantity').val();
		var price = $('#price').val();
		var notes = $('#notes').val();
		if(! purchase_no ){
			alert('Please give a purchase no.');
			return false;
		}
		if(! item_id ){
			alert('Please select an Item.');
			return false;
		}
		if(! supplier_id ){
			alert('Please select a supplier.');
			return false;
		}
		if(! quantity ){
			alert('Quantity must be greated than zero.');
			return false;
		}
		$.ajax({
			type: "POST",
			url: "inventory/purchase_save",
			data: {purchase_no: purchase_no, purchase_date: purchase_date, supplier_id: supplier_id, item_id: item_id, quantity: quantity, price: price, notes: notes},
			success: function(msg) {
				$("#purchase_details").children().remove();
				$("#purchase_details").html(msg);
				$("#datatables").dataTable();
			}
		});
		$('#purchase_no').attr('disabled', true);
		$('#purchase_date').attr('disabled', true);
		$('#quantity').val('');
		$('#price').val('');
		$('#quantity').focus();
	});
	//finish purchase item delete
	$(document).on('click', '.purchase_item_delete', function(event) {
		if (!confirm("Are you sure you want to delete this item?")) {
			return false;
		}
		var purchase_no = $('#purchase_no').val();
		var item_id = $(this).prev().val();
		$.ajax({
			type: "POST",
			url: "inventory/purchase_item_delete",
			data: {purchase_no: purchase_no, item_id: item_id},
			success: function(msg) {
				$("#purchase_details").children().remove();
				$("#purchase_details").html(msg);
				$("#datatables").dataTable();
			}
		});
	});
	//finish purchase complete
	$(document).on('click', '#purchase_complete', function(event) {
		var purchase_no = $('#purchase_no').val();
		var paid_amount = $('#paid_amount').val();
		var supplier_id = $('#supplier_id').val();
		var purchase_date = $('#purchase_date').val();
		var notes = $('#notes').val();
		$.ajax({
			type: "POST",
			url: "inventory/purchase_complete",
			data: {purchase_no: purchase_no, paid_amount: paid_amount, purchase_date: purchase_date, supplier_id: supplier_id, notes: notes},
			success: function(msg) {
				window.location.replace(msg);
			}
		});
	});
	//finish purchase return add item
	$(document).on('click', '#purchase_return_add_item', function(event) {
		var purchase_return_no = $('#purchase_return_no').val();
		var purchase_return_date = $('#purchase_return_date').val();
		var supplier_id = $('#supplier_id').val();
		var item_id = $('#item_id').val();
		var quantity = $('#quantity').val();
		var price = $('#price').val();
		var notes = $('#notes').val();
		if(! purchase_return_no ){
			alert('Please give a purchase return no.');
			return false;
		}
		if(! item_id ){
			alert('Please select an Item.');
			return false;
		}
		if(! supplier_id ){
			alert('Please select a supplier.');
			return false;
		}
		if(! quantity ){
			alert('Quantity must be greated than zero.');
			return false;
		}
		$.ajax({
			type: "POST",
			url: "inventory/purchase_return_save",
			data: {purchase_return_no: purchase_return_no, purchase_return_date: purchase_return_date, supplier_id: supplier_id, item_id: item_id, quantity: quantity, price: price, notes: notes},
			success: function(msg) {
				$("#purchase_return_details").children().remove();
				$("#purchase_return_details").html(msg);
				$("#datatables").dataTable();
			}
		});
		$('#purchase_return_no').attr('disabled', true);
		$('#purchase_return_date').attr('disabled', true);
		$('#quantity').val('');
		$('#price').val('');
		$('#quantity').focus();
	});
	//finish purchase return item delete
	$(document).on('click', '.purchase_return_item_delete', function(event) {
		if (!confirm("Are you sure you want to delete this item?")) {
			return false;
		}
		var purchase_return_no = $('#purchase_return_no').val();
		var item_id = $(this).prev().val();
		$.ajax({
			type: "POST",
			url: "inventory/purchase_return_item_delete",
			data: {purchase_return_no: purchase_return_no, item_id: item_id},
			success: function(msg) {
				$("#purchase_return_details").children().remove();
				$("#purchase_return_details").html(msg);
				$("#datatables").dataTable();
			}
		});
	});
	//finish purchase return complete
	$(document).on('click', '#purchase_return_complete', function(event) {
		var purchase_return_no = $('#purchase_return_no').val();
		var paid_amount = $('#paid_amount').val();
		var supplier_id = $('#supplier_id').val();
		var purchase_return_date = $('#purchase_return_date').val();
		var notes = $('#notes').val();
		$.ajax({
			type: "POST",
			url: "inventory/purchase_return_complete",
			data: {purchase_return_no: purchase_return_no, paid_amount: paid_amount, purchase_return_date: purchase_return_date, supplier_id: supplier_id, notes: notes},
			success: function(msg) {
				window.location.replace(msg);
			}
		});
	});
	//journal debit voucher add
	$(document).on('click', '#debit_add', function(event) {
		var journal_no = $('#journal_no').val();
		var journal_date = $('#journal_date').val();
		var journal_memo = $('#journal_memo').val();

		var debit_chart_id = $('#debit_chart_id').val();
		var debit_amount = $('#debit_amount').val();
		var debit_memo = $('#debit_memo').val();

		$.ajax({
			type: "POST",
			url: "accounts/debit_add",
			data: {journal_no: journal_no, journal_date: journal_date, journal_memo: journal_memo, debit_chart_id: debit_chart_id, debit_amount: debit_amount, debit_memo: debit_memo},
			success: function(msg) {
				$("#debit_details").children().remove();
				$("#debit_details").html(msg);
				var str = $('#debit_total').text();
				var total_debit = str.replace(",", "");
				$('#credit_amount').val(total_debit);
			}
		});
		$('#journal_no').attr('disabled', true);
		$('#journal_date').attr('disabled', true);
		$('#debit_amount').val('');
		$('#debit_memo').val('');
		$('#debit_amount').focus();
	});
	//journal credit voucher add
	$(document).on('click', '#credit_add', function(event) {
		var journal_no = $('#journal_no').val();
		var journal_date = $('#journal_date').val();
		var journal_memo = $('#journal_memo').val();

		var credit_chart_id = $('#credit_chart_id').val();
		var credit_amount = $('#credit_amount').val();
		var credit_memo = $('#credit_memo').val();

		$.ajax({
			type: "POST",
			url: "accounts/credit_add",
			data: {journal_no: journal_no, journal_date: journal_date, journal_memo: journal_memo, credit_chart_id: credit_chart_id, credit_amount: credit_amount, credit_memo: credit_memo},
			success: function(msg) {
				$("#credit_details").children().remove();
				$("#credit_details").html(msg);
				var debit = $('#debit_total').text();
				var total_debit = debit.replace(",", "");
				var credit = $('#credit_total').text();
				var total_credit = credit.replace(",", "");
				var rest_credit = total_debit - total_credit;
				$('#credit_amount').val(rest_credit);
			}
		});
		$('#journal_no').attr('disabled', true);
		$('#journal_date').attr('disabled', true);
		$('#credit_amount').val('');
		$('#credit_memo').val('');
		$('#credit_amount').focus();
	});
	//debit voucher delete
	$(document).on('click', '.debit_voucher_delete', function(event) {
		if (confirm("Are you sure you want to delete this item?")) {
			var journal_no = $('#journal_no').val();
			var voucher_id = $(this).prev().val();
			$.ajax({
				type: "POST",
				url: "accounts/delete_voucher/debit",
				data: {journal_no: journal_no, voucher_id: voucher_id},
				success: function(msg) {
					$("#debit_details").children().remove();
					$("#debit_details").html(msg);
				}
			});
		}
	});
	//credit voucher delete
	$(document).on('click', '.credit_voucher_delete', function(event) {
		if (confirm("Are you sure you want to delete this item?")) {
			var journal_no = $('#journal_no').val();
			var voucher_id = $(this).prev().val();
			$.ajax({
				type: "POST",
				url: "accounts/delete_voucher/credit",
				data: {journal_no: journal_no, voucher_id: voucher_id},
				success: function(msg) {
					$("#credit_details").children().remove();
					$("#credit_details").html(msg);
				}
			});
		}
	});
	//journal complete
	$(document).on('click', '#journal_complete', function(event) {
		var debit_total = $('#debit_total').text();
		var debit = Number(debit_total.replace(/[^0-9\.]+/g, ""));
		var credit_total = $('#credit_total').text();
		var credit = Number(credit_total.replace(/[^0-9\.]+/g, ""));
		var balance;
		if (debit !== credit) {
			if (debit > credit) {
				balance = debit - credit;
				alert('Credit voucher still need ' + balance + ' amount.');
				$("#credit_amount").focus();
			} else {
				balance = credit - debit;
				alert('Debit voucher still need ' + balance + ' amount.');
				$("#debit_amount").focus();
			}
			return false;
		}
		var journal_no = $('#journal_no').val();
		var journal_date = $('#journal_date').val();
		var journal_memo = $('#journal_memo').val();
		$.ajax({
			type: "POST",
			url: "accounts/journal_complete",
			data: {journal_no: journal_no, journal_date: journal_date, journal_memo: journal_memo},
			success: function(msg) {
				window.location.replace(msg);
			}
		});
	});
	// Add Supplier from Purchase
	$(document).on('click', '#add_supplier', function(event) {
		var code = $('#code').val();
		var name = $('#name').val();
		var address = $('#address').val();
		var contact_person = $('#contact_person').val();
		var phone_no = $('#phone_no').val();
		var notes = $('#notes').val();
		var status = 'Active';

		$.ajax({
			type: "POST",
			url: "inventory/add_new_supplier",
			data: {code: code, name: name, address: address, contact_person: contact_person, phone_no: phone_no, notes: notes, status: status},
			success: function(msg) {
				$("#supplier_id").select2("destroy");
				$("#supplier_id option").remove();
				$("#supplier_id").append(msg);
				$("#supplier_id").select2();
			}
		});
		$('#my_popup').popup('hide');
	});

	// Add Customer from Purchase
	$(document).on('click', '#add_customer', function(event) {
		var code = $('#code').val();
		var name = $('#name').val();
		var address = $('#address').val();
		var mobile = $('#mobile').val();
		var country = $('#country').val();
		var email = $('#email').val();
		var notes = $('#notes').val();
		var status = $('#status').val();

		$.ajax({
			type: "POST",
			url: "inventory/add_new_customer",
			data: {code: code, name: name, address: address, mobile: mobile, country: country, email: email, notes: notes, status: status},
			success: function(msg) {
				$("#customer_id").select2("destroy");
				$("#customer_id option").remove();
				$("#customer_id").append(msg);
				$("#customer_id").select2();
			}
		});
		$('#my_popup').popup('hide');
	});

	$(document).on('submit', '#form-modal', function(event) {
		event.preventDefault();

		var code = $('#code').val();sil
		var name = $('#name').val();
		var company = $('#company').val();
		var address = $('#address').val();
		var phone = $('#phone').val();
		var email = $('#email').val();
		var web = $('#web').val();

		$.ajax({
			type: "POST",
			url: "invoice/add_customer",
			data: {code: code, name: name, company: company, address: address, phone: phone, email: email, web: web},
			success: function(msg) {
				$("#customer_id").html(msg);
			}
		});
		$('#myModal').modal('hide');

		// select2
		$.ajax({
			type: "POST",
			url: "invoice/latest_customer",
			success: function(msg) {
				alert("Customer Added.");
				$('#customer_id').select2("val", msg);
			}
		});

	});

	// datatables
	$('#datatables').dataTable({
		"iDisplayLength": 50,
		"bStateSave": true,
		"iCookieDuration": 60 * 60 * 24 * 30, // 30 days
		"bSort": false,
		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		}
	});

	// datepicker
	$('[data-form=datepicker]').datepicker({autoclose: true, format: 'dd/mm/yyyy'});
	//$('.timepicker').timepicker({showMeridian: false, defaultTime: false});

	//delete confirmation
	$(".del").click(function() {
		if (!confirm("Are you sure you want to delete this item?")) {
			return false;
		}
	});

});