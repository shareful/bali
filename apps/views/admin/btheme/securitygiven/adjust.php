
<?php
	$theme = $this->config->item('theme');

	$pending_to_adjust = $security->amount-$security->amount_adjusted;
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Security #: <?php echo $security->code ?> <span>> Adjust to Purchase Bills</span></h1>
	</div>	
</div>

<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Adjust Bills From Security</h2>				
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
						
						<form action="securitygiven/save" method="post" class="smart-form" id="fromsecurityadjust">
							<fieldset>
								<legend>Security Info</legend>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="code">Code #</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="code" value="<?php echo $security->code ?>" id="code" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section> 

	                                <section class="col col-2">
	                                    <label class="control-label">Project</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" value="<?php echo '('.$security->project->code.') '.$security->project->name ?>" class="span5" readonly="readonly">
	                                    </label>
	                                </section> 	                                
	                            </div>

	                            <div class="row">
	                                <section class="col col-2">
	                                    <label class="control-label">Supplier</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" value="<?php echo '('.$security->supplier->code.') '.$security->supplier->name ?>" class="span5" readonly="readonly">
	                                    </label>
	                                </section> 

	                                <section class="col col-2">
	                                    <label class="control-label">Item</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" value="<?php echo isset($security->item) ? '('.$security->item->code.') '.$security->item->name : ''; ?>" class="span5" readonly="readonly">
	                                    </label>
	                                </section> 
	                            </div>

	                            <div class="row">
	                            	<section class="col col-2">
	                                    <label class="control-label" for="security_amount">Security Amount</label>
	                                </section>
	                                <section class="col col-3">
	                                    <label class="input"> 
	                                    	<input type="text" value="<?php echo $security->amount ?>" id="security_amount" class="span5" readonly="readonly">
	                                    </label>
	                                </section> 
	                                <section class="col col-1">
	                                	<label class="control-label">Tk</label>
	                                </section>

	                            	<section class="col col-2">
	                                    <label class="control-label" for="amount_adjusted">Already Adjusted</label>
	                                </section>
	                                <section class="col col-3">
	                                    <label class="input"> 
	                                    	<input type="text" value="<?php echo $security->amount_adjusted ?>" id="amount_adjusted" class="span5" readonly="readonly">
	                                    </label>
	                                </section> 
	                                <section class="col col-1">
	                                	<label class="control-label">Tk</label>
	                                </section>	                                
								</div>		

								<div class="row">
									<section class="col col-2 has-warning">
	                                    <label class="control-label" for="pending_to_adjust">Pending to Adjust</label>
	                                </section>
	                                <section class="col col-3 has-warning">
	                                    <label class="input"> 
	                                    	<input type="text" value="<?php echo number_format(($pending_to_adjust), 2, '.', ''); ?>" id="pending_to_adjust" class="span5" readonly="readonly">
	                                    </label>
	                                </section> 
	                                <section class="col col-1">
	                                	<label class="control-label">Tk</label>
	                                </section>	                                
								</div>					
							</fieldset>

							<fieldset>
								<legend>Bills to Adjust <small><small><small>(Bills would be appear bellow which are paid but security amount due only)</small></small></small></legend>

								<div class="table-responsive" id="sales_details2">
									<table class="table table-striped table-bordered table-hover display compact" cellspacing="0"  width="90%">
										<thead>
											<tr>
		                                        <th>Bill #</th>
		                                        <th>Security Amount Due</th>
		                                        <th>Adjust Now</th>                    
		                                    </tr>										
										</thead>
										<tbody id="itemGrid">
		                                	<?php	
												$c = 1;
												$pending_to_adjust_left = $pending_to_adjust;
												$total_adjust_now = 0;
												$nul_var = null;

												foreach ($bills as $bill) { 
													$bill = purchase_bill_cal_info($bill, $nul_var, $pending_to_adjust_left);

													$total_adjust_now += $bill->sec_amount_can_pay;

													// $payable_due_amt = number_format($bill->payable_due_amt, 2, '.', '');
													$security_due_amt = number_format($bill->security_due_amt, 2, '.', '');
													$sec_amount_can_pay = number_format($bill->sec_amount_can_pay, 2, '.', '');
											?>
												<tr id="row-bills-<?php echo $bill->id;?>">
				                                	<td>
				                                		<a href="purchase/bill_print/<?php echo $bill->id ?>" target="_blank"><?php echo $bill->project->code.'-'.$bill->supplier->code.'-'.$bill->item->code.'-'.$bill->code; ?></a>
				                                	</td>
				                                	<td><?php echo $security_due_amt; ?></td>
				                                	<td>
				                                		<input type="text" name="adjust_amount[]" value="<?php echo $sec_amount_can_pay; ?>" id="amount_adjusted_<?php echo $bill->id ?>" class="span5 amount_to_pay">
				                                		<input type="hidden" name="bill_id[]" value="<?php echo $bill->id ?>" >
				                                		<input type="hidden" name="payment_due[]" id="payable_due_<?php echo $bill->id ?>" value="<?php echo $security_due_amt ?>" >
				                                	</td>
												</tr>
											<?php 
												$c++;
											} 
											?>
		                                </tbody>
		                                <tfoot>
											<tr>
		                                        <th colspan="2" style="text-align: right;">Total Adjust Now Tk</th>
		                                        <th id="total_adjust_now"><?php echo number_format($total_adjust_now, 2, '.', ''); ?></th>                    
		                                    </tr>
		                                    <tr><td colspan="3"></td></tr>
		                                </tfoot>										
									</table>
								</div>
							</fieldset>
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <div class="form-actions">	          
                            	<input type="button" name="cancel" class="btn-lg btn-back" id="cancel_changes" value="Cancel" onclick="history.go(-1)" />                  
	                            <input type="button" name="submit" class="btn-lg btn-success" id="make_payment" value="Security Payment Adjust Now" <?php echo count($bills) == 0 ? 'disabled' : '' ;?> />
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

	// var total_adjust_now = <?php echo $total_adjust_now ?>;
	var pending_to_adjust = <?php echo $pending_to_adjust ?>;
	// var pending_to_adjust_left = <?php echo $pending_to_adjust_left ?>;

	$('.amount_to_pay').change(function(){
		var amt = parseFloat($(this).val());
		if (isNaN(amt)) {
			amt = 0;
		}
		
		var that = $(this);
		var pending_to_adjust_left = pending_to_adjust;

		var total_adjust_now = 0;
		var input_err = 0;
		$('.amount_to_pay').each(function(index, value){
			var val = parseFloat($(this).val());
			if (isNaN(val)) {
				val = 0;
			}
			var bill_id = $(this).attr('id');
			bill_id = bill_id.replace("amount_adjusted_","");
			var payment_due = parseFloat($('#payable_due_'+bill_id).val());
			if (isNaN(payment_due)) {
				payment_due = 0;
			}
			
			if (val > payment_due) {
				$(this).addClass('red-input');
				input_err = 1;
			} else {
				$(this).removeClass('red-input');
			}
				
			total_adjust_now += val;
		});

		$('#total_adjust_now').html(total_adjust_now.toFixed(2));

		if (input_err) {
			$.bigBox({
				title : "Validation Error!",
				content : 'One adjust amount is greater then due amount. check red box input and correct the amount to adjust.' ,
				color : "#C46A69",
				icon : "fa fa-warning shake animated",
				number : "",
				timeout : 4000
			});	
		}

		if (total_adjust_now > pending_to_adjust ) {
			$.bigBox({
				title : "Validation Error!",
				content : 'Total Adjust exceeded the pending to adjust amount of '+pending_to_adjust+' Tk' ,
				color : "#C46A69",
				icon : "fa fa-warning shake animated",
				number : "",
				timeout : 4000
			});	

			that.val('0');

			total_adjust_now = total_adjust_now - amt;
			$('#total_adjust_now').html(total_adjust_now.toFixed(2));
		}
	});

	$("#make_payment").click(function(){
		// if (confirm('Are you sure want to process This Payment?')) {			
			var $btn = $(this);
		    $btn.val('Processing...');
		    $btn.attr({disabled: true});
			

			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>securitygiven/adjust/<?php echo $security->id ?>",
				type : "post",
				dataType : "json",
				data : $("#fromsecurityadjust").serialize(),
				success : function(data) {
					$btn.attr({disabled: false});
					$btn.val('Security Payment Adjust Now');
					if (data.success == 'true') {
						$.bigBox({
							title : "Success",
							content : data.msg,
							color : "#739E73",
							timeout: 8000,
							icon : "fa fa-check",
							number : ""
						});
						$("form#fromsecurityadjust").trigger("reset");
						// window.open('securitygiven/receipt/'+data.id, '_blank', 'toolbar=no,scrollbars=yes,resizable=yes,width=1020,height=780');
						location.hash = 'securitygiven/index';
					} else if(data.error != ""){
						$.bigBox({
							title : "Error!",
							content : data.error,
							color : "#C46A69",
							icon : "fa fa-warning shake animated",
							number : "",
							// timeout : 6000
						});	
					}
				},
				error: function(){
					$btn.attr({disabled: false});
					$btn.val('Security Payment Adjust Now');
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
		// }
		
	});
</script>