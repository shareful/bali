
<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Sale Bill Entry Form</h2>				
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
						
						<form action="sale/save" method="post" class="smart-form" id="frmsalebill">
							<fieldset>
								<legend>General Info</legend>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="code">Bill #</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="code" value="" id="code" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section> 

	                                <section class="col col-2">
	                                    <label class="control-label" for="code">Ref Bill #</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="ref_no" value="" id="ref_no" class="span5" placeholder="Ref Bill No">
	                                    </label>
	                                </section>	                                
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="bill_date" class="control-label">Bill Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input id="bill_date" type="text" name="bill_date" value="<?php echo date("m-d-Y"); ?>">
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
	                                                <option value="<?php echo $project->project_id; ?>"  <?php echo $project->project_id==$project_id ? 'selected="selected"' : '' ?>><?php echo $project->code . ' - ' . $project->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="customer_id" class="control-label">Select Customer</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="customer_id" id="customer_id" tabindex="3" class="span5 select2">
	                                            <option value=""></option>
	                                            <?php foreach ($customers as $customer) { ?>
	                                                <option value="<?php echo $customer->customer_id; ?>"  <?php echo $customer->customer_id==$customer_id ? 'selected="selected"' : '' ?>><?php echo $customer->code . ' - ' . $customer->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>

									<section class="col col-2">
										<label for="notes" class="control-label">Notes</label>
									</section>
									<section class="col col-4">	
										<label class="textarea">
											<textarea type="text" name="notes" id="notes" class="textarea" placeholder="Notes"></textarea>		
										</label>
									</section>										
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="acc_id" class="control-label">Account</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="acc_id" id="acc_id" tabindex="3" class="span5 select2">
	                                            <option value="">Select One</option>
	                                            <?php foreach ($accounts as $key=>$acc) { ?>
	                                                <option value="<?php echo $acc->acc_id; ?>"><?php echo $acc->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>

									<section class="col col-2 sub_acc_wrap" style="display: none;">
										<label for="sub_acc_id" class="control-label">Sub Account</label>
									</section>
									<section class="col col-4 sub_acc_wrap" style="display: none;">	
										<label class="input">
											<select name="sub_acc_id" id="sub_acc_id" tabindex="3" class="span5 select2">
	                                            <option value="">Select One</option>
                                        	</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-2 sub_acc_wrap" style="display: none;">
	                                    <label class="control-label" for="check_trans_no">Check / Trans. No</label>
	                                </section>
	                                <section class="col col-4 sub_acc_wrap" style="display: none;">
	                                    <label class="input"> 
	                                    	<input type="text" name="check_trans_no" value="" id="check_trans_no" class="span5" placeholder="Check or Transaction No">
	                                    </label>
	                                </section>
								</div>
							</fieldset>
							<fieldset>
								<legend>Item Info</legend>
								<div class="row">
									<section class="col col-2">
										<label for="item_id" class="control-label">Select Item</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="item_id" id="item_id" tabindex="3" class="span5 select2">
	                                            <option value=""></option>
	                                            <?php foreach ($items as $item) { ?>
	                                                <option value="<?php echo $item->item_id; ?>"  <?php echo $item->item_id==$item_id ? 'selected="selected"' : '' ?>><?php echo $item->code . ' - ' . $item->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>

									<section class="col col-2">
										<label for="stockbalance" class="control-label">Current Stock</label>
									</section>
									<section class="col col-3">	
										<label class="input">											
											<input type="text" style="text-align: right;" name="stockbalance" class="span5" id="stockbalance" value="<?php echo isset($itemstock->stock) ? $itemstock->stock : 0 ;?>" readonly />
										</label>
									</section>
									<section class="col col-1">
										<label class="control-label unit_name" id="unit_name"><?php echo isset($item->unit_name) ? $item->unit_name : '' ;?></label>
									</section>
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="stockbilledbalance" class="control-label">Billed so far</label>
									</section>
									<section class="col col-3">	
										<label class="input">											
											<input type="text" style="text-align: right;" name="stockbilledbalance" class="span5" id="stockbilledbalance" value="<?php echo isset($itembilled->billed) ? $itembilled->billed : 0 ;?>" readonly />
										</label>
									</section>
									<section class="col col-1">
										<label class="control-label unit_name" id="unit_name_billed"><?php echo isset($itembilled->unit_name) ? $itembilled->unit_name : '' ;?></label>
									</section>	

									<section class="col col-2">
										<label for="stockbilledbalance" class="control-label">Available to Bill</label>
									</section>
									<section class="col col-3">	
										<label class="input">		
											<?php
											$stock = isset($itemstock->stock) ? $itemstock->stock : 0;
											$billed = isset($itembilled->billed) ? $itembilled->billed : 0;
											$available_to_bill = number_format($stock - $billed, 2, '.', '');
											?>									
											<input type="text" style="text-align: right;" name="availbletobill" class="span5" id="availbletobill" value="<?php echo $available_to_bill ;?>" readonly />
										</label>
									</section>
									<section class="col col-1">
										<label class="control-label unit_name" id="unit_name_aavailble"><?php echo isset($itembilled->unit_name) ? $itembilled->unit_name : '' ;?></label>
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
                            <input type="hidden" name="total_amount" id="total_amount_hidden" value="0" />
							<input type="hidden" name="security_perc" id="security_perc_hidden" value="0" />
							<input type="hidden" name="security_amount" id="security_amount_hidden" value="0" />
							<input type="hidden" name="receivable_amount" id="receivable_amount_hidden" value="0" />
							<input type="hidden" name="received_amount" id="received_amount_hidden" value="0" />
							<input type="hidden" name="due_amount" id="due_amount_hidden" value="0" />

                            <!-- <div class="form-actions">	                            
	                            <input type="button" name="submit" class="btn-lg btn-success" id="add_item_grid" value="Add Item" />
	                        </div> -->
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
					<span class="widget-icon"> <i class="fa fa-dollar"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> Bill Amount</h6>
				</header>
				<div class="form-actions">
	                <div class="span12 center">
						<div class="jarviswidget-editbox">
						</div>
						<div class="widget-body no-padding">
							<div class="table-responsive" id="sales_details2">
								<table class="table table-striped table-bordered table-hover display compact" cellspacing="0"  width="100%">
									<tfoot>
	                                    <tr><th colspan="4" style="text-align: right;">Total Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="total_amount" id="total_amount" value="0" readonly /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Security % </th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="security_perc" id="security_perc" value="0" /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Security Amount Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="security_amount" id="security_amount" value="0" readonly /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Receivable Now <small>(exclude sec. amt.)</small> Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="receivable_amount" id="receivable_amount" value="0" /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Received Amount Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="received_amount" id="received_amount" value="0" /></th><th></th></tr>
	                                    <tr><th colspan="4" style="text-align: right;">Due Amount <small>(include sec. amt.)</small> Tk</th><th><input type="text" style="float: right; text-align: right; width: 150px;" name="due_amount" id="due_amount" value="0" readonly /></th><th></th></tr>
	                                </tfoot>

	                                <tbody id="itemGrid">
	                                	
	                                </tbody>
									
								</table>
							</div>
						</div>
						<div class="form-actions center">
							<input type="button" name="cancel" class="btn-lg btn-back" id="cancel_changes" value="Cancel" onclick="history.go(-1)" />
	                        <input type="button" class="btn-lg btn-success" id="bill_complete" value="Complete Sale Bill" />
	                    </div>
					</div>
        		</div>
			</div>
		</article>
	</div>
</section>

<script type="text/javascript">
	
	runAllForms();

	$("#bill_complete").attr({disabled: true});
	// var item_list = new Object();

	<?php
	if (!$itembilled->billed) {
		$itembilled->billed = 0;
	}
	if ($itemstock->stock > $itembilled->billed) {
	?>
		$.bigBox({
			title : "Quantity you can bill",
			content : '<?php echo ($itemstock->stock - $itembilled->billed) ?> <?php echo $itemstock->unit_name ?> is pending to bill.',
			color : "#3276B1",
			icon : "fa fa-bell shake animated",
			number : "",
			timeout : 10000
		});	
	<?php
	}
	?>

	$("#acc_id").change(function(){
    	var acc_id = $(this).val();
    	if (acc_id) {
	    	$.ajax({
					url : "<?php echo $this->config->item('base_url') ?>account/subacc_options/"+acc_id,
					type : "get",
					dataType : "json",
					success : function(data) {
						if (data.html && data.html != '') {
							$("#sub_acc_id").html(data.html);
							$("#sub_acc_id").select2("val", "");
							$('.sub_acc_wrap').show();
						} else {
							$('.sub_acc_wrap').hide();
						}
					},
					error: function(){
						$.bigBox({
							title : "Error!",
							content : 'Sub account list fetching failed. Check your connection or contact with administrator.',
							color : "#C46A69",
							icon : "fa fa-warning shake animated",
							number : "",
							timeout : 6000
						});
					}
				});
    	} else {
    		$("#sub_acc_id").html('<option value=""> Select One </option>');
    		$("#sub_acc_id").select2("val", "");
    		$('.sub_acc_wrap').hide();
    	}
    });

    $("#project_id").change(function(){
    	var project_id = $(this).val();
    	if (project_id) {
	    	$.ajax({
					url : "<?php echo $this->config->item('base_url') ?>project/items_options/"+project_id,
					type : "get",
					dataType : "json",
					success : function(data) {
						if (data.html) {
							$("#item_id").html(data.html);
							$("#item_id").select2("val", "");
							$("#stockbalance").val("");
							$("#quantity").val("");
							$("#price").val("");
							$("#stockbilledbalance").val("");
							$("#availbletobill").val("");
							$('#quantity, #price').trigger('change');
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
							content : 'Items list fetching failed. Check your connection or contact with administrator.',
							color : "#C46A69",
							icon : "fa fa-warning shake animated",
							number : "",
							timeout : 6000
						});
					}
				});
    	} else {
    		$("#item_id").html('<option value=""> None </option>');
    		$("#item_id").select2("val", "");
    		$("#stockbalance").val("");
			$("#quantity").val("");
			$("#price").val("");
			$("#stockbilledbalance").val("");
			$("#availbletobill").val("");
			$('#quantity, #price').trigger('change');
    	}
    });

	$('#item_id, #customer_id').change(function(){
		var item_id = $('#item_id').val();
		var project_id = $('#project_id').val();
		var customer_id = $('#customer_id').val();

		if (item_id && project_id && customer_id) {
			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>sale/get_bill_no/"+item_id+"/"+project_id+"/"+customer_id,
				dataType : "json",
				success : function(data) {
					if (data.success == 'true') {
						$('#code').val(data.code);
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
		}
	});

	$('#item_id').change(function(){
		var item_id = $('#item_id').val();
		var project_id = $('#project_id').val();
		
		// console.log(item_id);
		// console.log(project_id);

		if (!item_id || !project_id) {
			return;
		}

		$.ajax({
			url : "<?php echo $this->config->item('base_url') ?>stock/get_item_info/"+item_id+"/"+project_id+"/"+1,
			dataType : "json",
			success : function(data) {
				if (data.success == 'true') {
					$('#stockbalance').val(data.info.stock);
					$('#stockbilledbalance').val(data.info.billed);
					$('#availbletobill').val((data.info.stock - data.info.billed).toFixed(2));
					$('.unit_name').html(data.info.unit_name);		
					// $('#unit_name_billed').html(data.info.unit_name);		
					
					// save item info
					// item_list[data.info.item_id] = data.info;

					if (data.info.billed < data.info.stock) {
						var can_bill = (data.info.stock - data.info.billed).toFixed(2);
						$.bigBox({
							title : "Quantity you can bill",
							content : can_bill+' '+data.info.unit_name+' is pending to bill.',
							color : "#3276B1",
							icon : "fa fa-bell shake animated",
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
	
	
	$('#quantity, #price, #security_perc, #received_amount').change(function(){
		var quantity = parseFloat($('#quantity').val());
		var price = parseFloat($('#price').val());

		
		if (!quantity || quantity <= 0) {
			// alert('Enter Quantity you want to bill');
			$('#quantity').focus()
			quantity = 0;
		}

		if (!price || price <= 0) {
			// alert('Enter Unit Price of the Item');
			$('#price').focus()
			price = 0;
		}

		var total_amount  = (price*quantity).toFixed(2);

		$('#total_amount').val(total_amount);

		calculateOthers();		
	});

	function calculateOthers(){
		var total_amount = $('#total_amount').val();
		if (!total_amount || total_amount < 0) {
			$('#total_amount').val('0');
			total_amount = 0;
		}
		// console.log('total:'+total_amount);

		var security_perc = $('#security_perc').val();
		if (!security_perc || security_perc < 0) {
			$('#security_perc').val('0');
			security_perc = 0;
		}
		// console.log('sec_per:'+security_perc);


		var security_amount = (total_amount*security_perc/100).toFixed(2);
		// console.log('sec_amount:'+security_amount);
		$('#security_amount').val(security_amount);

		var receivable_amount = total_amount - security_amount;
		$('#receivable_amount').val(receivable_amount);

		var received_amount = $('#received_amount').val();
		if (!received_amount || received_amount <0) {
			$('#received_amount').val('0');
			received_amount = 0;
		}
		// console.log('received_amount:'+received_amount);

		var due_amount = total_amount - received_amount;
		// console.log('due_amount:'+due_amount);
		$('#due_amount').val(due_amount);		
		

		$('#total_amount_hidden').val(total_amount);		
		$('#security_perc_hidden').val(security_perc);		
		$('#security_amount_hidden').val(security_amount);		
		$('#receivable_amount_hidden').val(receivable_amount);		
		$('#received_amount_hidden').val(received_amount);		
		$('#due_amount_hidden').val(due_amount);	

		if (total_amount > 0) {
			$("#bill_complete").attr({disabled: false});
		} else {
			$("#bill_complete").attr({disabled: true});
		}
	}

	$('#code').focus();
	$('#bill_date').datepicker({
        dateFormat : 'mm-dd-yy',
        prevText : '<i class="fa fa-chevron-left"></i>',
        nextText : '<i class="fa fa-chevron-right"></i>'
    }); 

	$("#bill_complete").click(function(){
		if (confirm('Are you sure want to process This Sale Bill?')) {			
			var $btn = $(this);
		    $btn.val('processing...');
		    $btn.attr({disabled: true});
			
			var item_id = $('#item_id').val();
			var project_id = $('#project_id').val();

			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>sale/new_bill/"+project_id+"/"+item_id,
				type : "post",
				dataType : "json",
				data : $("#frmsalebill").serialize(),
				success : function(data) {
					$btn.attr({disabled: false});
					$btn.val('Complete Sale Bill');
					if (data.success == 'true') {
						$.bigBox({
							title : "Success",
							content : data.msg,
							color : "#739E73",
							timeout: 8000,
							icon : "fa fa-check",
							number : ""
						});
						$("form#frmsalebill").trigger("reset");
						window.open('sale/bill_print/'+data.id, '_blank', 'toolbar=no,scrollbars=yes,resizable=yes,width=1020,height=780');
						location.hash = 'sale/index/'+project_id+'/'+item_id;
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
					$btn.val('Complete Sale Bill');
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
		}
		
	});
</script>