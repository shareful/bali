<?php
	$null_var1 = null;
	$null_var2 = null;
	$bill = purchase_bill_cal_info($bill, $null_var1, $null_var2);
?>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Project: <?php echo $bill->project->name ?> <span>> Bill # <?php echo $bill->invoice_no ?></span> <span>> Make New Payment</span></h1>
	</div>
	
</div>

<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Make Payment Form</h2>				
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
						
						<form action="payment/make" method="post" class="smart-form" id="frmpaymentbill">
							<fieldset>
								<legend>Bill Info</legend>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="code">Bill #</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="code" value="<?php echo $bill->invoice_no?>" id="code" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section> 

	                                <section class="col col-2">
										<label for="bill_date" class="control-label">Bill Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input id="bill_date" type="text" name="bill_date" value="<?php echo date("m-d-Y", strtotime($bill->bill_date)); ?>" readonly>
                                        </label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="project">Project</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="project" value="<?php echo '('.$bill->project->code.')'.$bill->project->name ?>" id="project" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
	                                <section class="col col-2">
	                                    <label class="control-label" for="project">Item</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="item" value="<?php echo '('.$bill->item->code.')'.$bill->item->name ?>" id="item" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
								</div>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="total_amount">Bill Total</label>
	                                </section>
	                                <section class="col col-3">
	                                    <label class="input"> 
	                                    	<input type="text" name="total_amount" value="<?php echo $bill->total_amount?>" id="total_amount" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
	                                <section class="col col-1">
	                                	Tk
	                                </section>

	                                <section class="col col-2">
	                                    <label class="control-label" for="paid_amount">PAID</label>
	                                </section>
	                                <section class="col col-3">
	                                    <label class="input"> 
	                                    	<input type="text" name="paid_amount" value="<?php echo $bill->paid_amount?>" id="paid_amount" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
	                                <section class="col col-1">
	                                	Tk
	                                </section>
								</div>

								<div class="row">
									<section class="col col-2 has-warning">
	                                    <label class="control-label" for="payable_due_amt"><strong>DUE</strong></label>
	                                </section>
	                                <section class="col col-3 has-warning">
	                                    <label class="input"> 
	                                    	<input type="text" name="payable_due_amt" value="<?php echo $bill->payable_due_amt?>" id="payable_due_amt" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
	                                <section class="col col-1">
	                                	Tk
	                                </section>

	                                <section class="col col-2">
										<label for="security_perc" class="control-label">Security </label>
									</section>
									<section class="col col-1">	
										<label class="input">
                                            <input id="security_perc" type="text" name="security_perc" value="<?php echo $bill->security_perc ?> %" placeholder="Securoty Percentage" readonly>
                                        </label>
									</section>
									<section class="col col-2">	
										<label class="input">
                                            <input id="security_amount" type="text" name="security_amount" value="<?php echo $bill->security_amount ?>" placeholder="Securoty Percentage" readonly>
                                        </label>
									</section>
									<section class="col col-1">
										<label class="control-label">Tk </label>
									</section>
								</div>	

								<div class="row">
									<section class="col col-2 has-warning">
	                                    <label class="control-label" for="security_due_amt"><strong>SECURITY DUE</strong> <small><small><small></small></small></small></label>
	                                </section>
	                                <section class="col col-3 has-warning">
	                                    <label class="input"> 
	                                    	<input type="text" name="security_due_amt" value="<?php echo $bill->security_due_amt?>" id="security_due_amt" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
	                                <section class="col col-1">
	                                	Tk
	                                </section>

	                                <section class="col col-2 has-warning">
	                                    <label class="control-label" for="bill_due_amt"><strong>TOTAL DUE</strong> <small><small><small></small></small></small></label>
	                                </section>
	                                <section class="col col-3 has-warning">
	                                    <label class="input"> 
	                                    	<input type="text" name="bill_due_amt" value="<?php echo $bill->bill_due_amt?>" id="bill_due_amt" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
	                                <section class="col col-1">
	                                	Tk
	                                </section>
								</div>							
							</fieldset>

							<fieldset>
								<legend>Make Payment Below</legend>
								<div class="row">
									<section class="col col-2">
										<label for="trans_date" class="control-label">Payment Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
	                                        <input id="trans_date" type="text" name="trans_date" value="<?php echo date("m-d-Y"); ?>">
	                                    </label>
									</section>

									<section class="col col-2">
										<label for="amount" class="control-label">Amount Paying</label>
									</section>
									<section class="col col-3">	
										<label class="input">
	                                        <input id="amount" type="text" name="amount" value="" placeholder="Amount You are Paying now">
	                                    </label>
									</section>
									<section class="col col-1">
	                                	Tk
	                                </section>
	                            </div>
	                            <div class="row">		
	                            	<section class="col col-2">
										<label for="new_due" class="control-label">Total Due would be</label>
									</section>
									<section class="col col-3">	
										<label class="input">
	                                        <input id="new_due" type="text" name="new_due" value="" placeholder="Amount You are Paying now" readonly>
	                                    </label>
									</section>
									<section class="col col-1">
	                                	Tk
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
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <input type="hidden" name="bill_id" id="bill_id" value="<?php echo $bill->id ?>" />
                            <input type="hidden" name="project_id" id="project_id" value="<?php echo $bill->project_id ?>" />
                            <input type="hidden" name="item_id" id="item_id" value="<?php echo $bill->item_id ?>" />
							<input type="hidden" name="src_type" id="src_type" value="bill" />
							
                            <div class="form-actions">	          
                            	<input type="button" name="cancel" class="btn-lg btn-back" id="cancel_changes" value="Cancel" onclick="history.go(-1)" />                  
	                            <input type="button" name="submit" class="btn-lg btn-success" id="make_payment" value="Make Payment Now" />
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

<script type="text/javascript">
	runAllForms();

	$('#trans_date').focus();
	$('#trans_date').datepicker({
        dateFormat : 'mm-dd-yy',
        prevText : '<i class="fa fa-chevron-left"></i>',
        nextText : '<i class="fa fa-chevron-right"></i>'
    }); 

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

	$('#amount').change(function(){
		var paid = parseFloat($('#amount').val());
		var due_amount = parseFloat($('#bill_due_amt').val());
		var new_bill_due_amt = 0;

		if (paid > bill_due_amt) {
			$.bigBox({
				title : "Validation Error!",
				content : "Amount Paying can't be greater then Due Amount! ",
				color : "#C46A69",
				icon : "fa fa-warning shake animated",
				number : "",
				timeout : 3000
			});	

			$('#new_due').val('');
			return;
		}

		if (paid <= 0) {
			$('#new_due').val('');
			return;
		}

		new_due_amount = (due_amount - paid).toFixed(2);
		$('#new_due').val(new_due_amount);
	});

    $("#make_payment").click(function(){
		if (confirm('Are you sure want to process This Payment?')) {			
			var $btn = $(this);
		    $btn.val('processing...');
		    $btn.attr({disabled: true});
			
			var bill_id = $('#bill_id').val();
			var project_id = $('#project_id').val();
			var item_id = $('#item_id').val();
			
			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>payment/make/"+bill_id,
				type : "post",
				dataType : "json",
				data : $("#frmpaymentbill").serialize(),
				success : function(data) {
					$btn.attr({disabled: false});
					$btn.val('Make Payment Now');
					if (data.success == 'true') {
						$.bigBox({
							title : "Success",
							content : data.msg,
							color : "#739E73",
							timeout: 8000,
							icon : "fa fa-check",
							number : ""
						});
						$("form#frmpaymentbill").trigger("reset");
						window.open('purchase/bill_print/'+bill_id, '_blank', 'toolbar=no,scrollbars=yes,resizable=yes,width=1020,height=780');
						location.hash = 'purchase/index/'+project_id+'/'+item_id;
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
					$btn.val('Make Payment Now');
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