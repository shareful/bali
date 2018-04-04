
<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Purchase Entry Form</h2>				
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form action="purchase/save" method="post" class="smart-form" id="frmpurchase">
							<fieldset>
								<legend>General Info</legend>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="code">Purchase Code</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="code" value="<?php if (count($purchase) > 0) { echo $purchase->code; } else { echo $code; } ?>" id="code" class="span5" placeholder="purchase Code">
	                                    </label>
	                                </section> 

	                                <section class="col col-2">
	                                    <label class="control-label" for="code">Ref No</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="ref_no" value="<?php if (count($purchase) > 0) { echo $purchase->ref_no; }  ?>" id="ref_no" class="span5" placeholder="Ref No">
	                                    </label>
	                                </section>

	                                
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="purchase_date" class="control-label">Purchase Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input id="purchase_date" type="text" name="purchase_date" value="<?php echo date("Y-m-d"); ?>">
                                        </label>
									</section>
									
									<section class="col col-2">
										<label for="project_id" class="control-label">Select Project</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="project_id" id="project_id" tabindex="3" class="span5 select2">
	                                            <option value=""></option>
	                                            <?php foreach ($projects as $project) { ?>
	                                                <option value="<?php echo $project->project_id; ?>"><?php echo $project->code . ' - ' . $project->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="supplier_id" class="control-label">Select Supplier</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="supplier_id" id="supplier_id" tabindex="3" class="span5 select2">
	                                            <option value=""></option>
	                                            <?php foreach ($suppliers as $supplier) { ?>
	                                                <option value="<?php echo $supplier->supplier_id; ?>"><?php echo $supplier->code . ' - ' . $supplier->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>
								</div>
							</fieldset>
							<fieldset>
								<legend>Add Item</legend>
								<div class="row">
									<section class="col col-2">
										<label for="item_id" class="control-label">Select Item</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="item_id" id="item_id" tabindex="3" class="span5 select2">
	                                            <option value=""></option>
	                                            <?php foreach ($items as $item) { ?>
	                                                <option value="<?php echo $item->item_id; ?>"><?php echo $item->code . ' - ' . $item->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>

									<section class="col col-2">
										<label for="stockbalance" class="control-label">Current Stock</label>
									</section>
									<section class="col col-3">	
										<label class="input">											
											<input type="text" style="text-align: right;" name="stockbalance" class="span5" id="stockbalance" value="0" readonly />
										</label>
									</section>
									<section class="col col-1">
										<label class="control-label" id="unit_name"></label>
									</section>
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="quantity" class="control-label">Quantity</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<input type="text" name="quantity" id="quantity" class="span5" placeholder="0" />
										</label>
									</section>

									<section class="col col-2">
										<label for="price" class="control-label">Price per Unit</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<input type="text" name="price" id="price" class="span5" placeholder="0" />
										</label>
									</section>
								</div>
							</fieldset>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <?php if (count($purchase) > 0) { ?>
                            <input type="hidden" name="id" value="<?php echo $purchase->id; ?>" />
                            <?php } ?>
	                        <div class="form-actions">
	                            <input type="button" name="cancel" class="btn-lg btn-back" id="cancel_changes" value="Cancel" onclick="history.go(-1)" />
	                            <input type="button" name="submit" class="btn-lg btn-success" id="add_item_grid" value="Add Item" />
	                        </div>
						</form>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->	
		</article>

	</div>
</section>

<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<header> 
					<span class="widget-icon"> <i class="fa fa-table"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> Item List</h6>
				</header>
				<div class="form-actions">
	                <div class="span12 center">
						<div class="jarviswidget-editbox">
						</div>
						<div class="widget-body no-padding">
							<div class="table-responsive" id="sales_details2">
								<table class="table table-striped table-bordered table-hover display compact" cellspacing="0"  width="100%">
									<thead>
										<tr>
	                                        <th width="10%">ID</th>
	                                        <th width="40%">Name</th>
	                                        <th width="10%">Unit Price</th>
	                                        <th width="10%">Qty.</th>
	                                        <th width="20%">Total Price</th>
	                                        <th width="10%"></th>
	                                    </tr>										
									</thead>
									<tfoot>
	                                    <tr><th colspan="4" style="text-align: right;">Total Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="tmptotal" id="tmptotal" value="0" readonly /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Security % </th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="discount" id="discount" value="0" /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Security Amount Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="subtotal01" id="subtotal01" value="0" readonly /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Payable Now Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="payamount" id="payamount" value="0" /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Paid Amount Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="returnamount" id="returnamount" value="0" readonly /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Due Amount Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="dueamount" id="dueamount" value="0" readonly /></th><th></th></tr>
	                                </tfoot>

	                                <tbody id="itemGrid">
	                                	
	                                </tbody>
									
								</table>
							</div>
						</div>
						<div class="form-actions center">
	                        <input type="button" class="btn-lg btn-success" id="sales_complete" value="Complete Purchase Entry" />
	                    </div>
					</div>
        		</div>
			</div>
		</article>
	</div>
</section>

<script type="text/javascript">
	
	runAllForms();

	var item_list = new Object();

	$('#item_id, #project_id').change(function(){
		var item_id = $('#item_id').val();
		var project_id = $('#project_id').val();
		
		// console.log(item_id);
		// console.log(project_id);

		if (!item_id || !project_id) {
			return;
		}

		$.ajax({
			url : "<?php echo $this->config->item('base_url') ?>stock/get_item_info/"+item_id+"/"+project_id,
			dataType : "json",
			success : function(data) {
				if (data.success == 'true') {
					$('#stockbalance').val(data.info.stock);
					$('#unit_name').html(data.info.unit_name);		
					
					// save item info
					item_list[data.info.item_id] = data.info;

					if (!data.info.stock || data.info.stock == 0) {
						$.bigBox({
							title : "Sold Out!",
							content : 'The Item is Sold Out. Need to purchase.',
							color : "#C46A69",
							icon : "fa fa-warning shake animated",
							number : "",
							timeout : 6000
						});	
					}			
				} else if(data.error != ""){
					$.bigBox({
						title : "Error!",
						content : data.error,
						color : "#C46A69",
						icon : "fa fa-warning shake animated",
						number : "",
						timeout : 6000
					});	
				}
			},
			error: function(){
				$.bigBox({
					title : "Error!",
					content : 'An error occured. Check your connection.',
					color : "#C46A69",
					icon : "fa fa-warning shake animated",
					number : "",
					timeout : 6000
				});
			}
		});
	});
	
	$("#save_changes").click(function(){
		var $btn = $(this);
	    $btn.val('loading');
	    $btn.attr({disabled: true});
		
		$.ajax({
			url : "<?php echo $this->config->item('base_url') ?>purchase/save",
			type : "post",
			dataType : "json",
			data : $("#frmpurchase").serialize(),
			success : function(data) {
				$btn.attr({disabled: false});
				$btn.val('Save changes');
				if (data.success == 'true') {
					$.bigBox({
						title : "Success",
						content : data.msg,
						color : "#739E73",
						timeout: 8000,
						icon : "fa fa-check",
						number : ""
					});
					$("form#frmpurchase").trigger("reset");
					location.hash = 'purchase';
				} else if(data.error != ""){
					$.bigBox({
						title : "Error!",
						content : data.error,
						color : "#C46A69",
						icon : "fa fa-warning shake animated",
						number : "",
						timeout : 6000
					});	
				}
			},
			error: function(){
				$btn.attr({disabled: false});
				$btn.val('Save changes');
				$.bigBox({
					title : "Error!",
					content : data.error,
					color : "#C46A69",
					icon : "fa fa-warning shake animated",
					number : "",
					timeout : 6000
				});
			}
		});
		
	});

	$('#add_item_grid').click(function(){
		var project_id = $('#project_id').val();
		var supplier_id = $('#supplier_id').val();
		var item_id = $('#item_id').val();
		var quantity = parseFloat($('#quantity').val());
		var price = parseFloat($('#price').val());

		if (!project_id) {
			alert('Select Project Please.');
			return;
		}

		if (!supplier_id) {
			alert('Select Supplier Please.');
			return;
		}
		
		if (!item_id) {
			alert('Select Item Please.');
			return;
		}

		if (!quantity || quantity <= 0) {
			alert('Enter Quantity you want to purchase');
			return;
		}

		if (!price || price <= 0) {
			alert('Enter Unit Price of the Item');
			return;
		}

		var item_total  = price*quantity;
		var info = item_list[item_id];

		var item_row_str = `<tr id="item_selected_${item_id}">
                                <td>${info.code}</td>
                                <td>${info.name}</td>
                                <td><input type="text" name="price[]" id="price_${item_id}" value="${price}" onchange="adjustItemTotal(${item_id})" style="width: 100px;"></td>
                                <td><input type="text" name="quantity[]" id="quantity_${item_id}" value="${quantity}" onchange="adjustItemTotal(${item_id})" style="width: 100px;"></td>
                                <td id="item_total_${item_id}">Tk ${item_total}</td>
                                <td><button class="btn btn-danger del" onclick="deleteItem(${item_id})" id="delete_customer" ><i class="fa fa-lg fa-fw fa-trash"></i></button></td>
                            </tr>`;
        $('#itemGrid').append(item_row_str);

	});

	function adjustItemTotal(item_id){
		// console.log(item_id);

		var quantity = parseFloat($('#quantity_'+item_id).val());
		var price = parseFloat($('#price_'+item_id).val());

		var item_total  = price*quantity;
		$('#item_total_'+item_id).html('Tk '+item_total);
		
		if (!quantity || quantity <= 0 ) {
			alert('Item quantity shuold be greater then Zero' );
			return;
		}

		if (!price || price <= 0 ) {
			alert('Item price shuold be greater then Zero' );
			return;
		}
	}

	$('#code').focus();
	$('#purchase_date').datepicker({
        dateFormat : 'yy-mm-dd',
        prevText : '<i class="fa fa-chevron-left"></i>',
        nextText : '<i class="fa fa-chevron-right"></i>'
    }); 

</script>