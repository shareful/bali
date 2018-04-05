
<?php
	$theme = $this->config->item('theme');

	$pending_to_adjust = $advance->amount-$advance->amount_adjusted;
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Advance #: <?php echo $advance->code ?> <span>> Adjust to Purchase Bills</span></h1>
	</div>	
</div>

<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Adjust Bills From Advance</h2>				
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
						
						<form action="advancegiven/save" method="post" class="smart-form" id="fromadvancegiven">
							<fieldset>
								<legend>Advance Info</legend>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="code">Code #</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="code" value="<?php echo $advance->code ?>" id="code" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section> 

	                                <section class="col col-2">
	                                    <label class="control-label" for="advance_amount">Advance Amount</label>
	                                </section>
	                                <section class="col col-3">
	                                    <label class="input"> 
	                                    	<input type="text" value="<?php echo $advance->amount ?>" id="advance_amount" class="span5" readonly="readonly">
	                                    </label>
	                                </section> 
	                                <section class="col col-1">
	                                	<label class="control-label">Tk</label>
	                                </section>
	                            </div>

	                            <div class="row">
	                            	<section class="col col-2">
	                                    <label class="control-label" for="amount_adjusted">Already Adjusted</label>
	                                </section>
	                                <section class="col col-3">
	                                    <label class="input"> 
	                                    	<input type="text" value="<?php echo $advance->amount_adjusted ?>" id="amount_adjusted" class="span5" readonly="readonly">
	                                    </label>
	                                </section> 
	                                <section class="col col-1">
	                                	<label class="control-label">Tk</label>
	                                </section>

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
								<legend>Bills to Adjust</legend>

								<div class="table-responsive" id="sales_details2">
									<table class="table table-striped table-bordered table-hover display compact" cellspacing="0"  width="90%">
										<thead>
											<tr>
		                                        <th>Bill #</th>
		                                        <th>Amount Due <small>(without security)</small></th>
		                                        <th>Payment Now</th>                    
		                                    </tr>										
										</thead>
										<tbody id="itemGrid">
		                                	<?php	
												$c = 1;
												$pending_to_adjust_left = $pending_to_adjust;
												$total_payment_now = 0;

												foreach ($bills as $bill) { 
													$sec_due = false;
													$payment_due = false;

													$sec_amt = ($bill->total_amount - $bill->payable_amount);
													$due_amt = ($bill->total_amount - $bill->paid_amount);
													$amount_to_pay = 0;

													if ($due_amt == 0) {
														# do nothing...
													} else if ($due_amt > $sec_amt) {
														// Security and Payment both are due
														if ($sec_amt > 0) {
															$sec_due = true;
														}
														$payment_due = true;
													} else if ($sec_amt > 0) {
														// Payment are clear but security due
														$sec_due = true;
													}

													$payable_due = $bill->payable_amount - $bill->paid_amount;
													if ($payable_due < 0) {
														$payable_due = 0;
													}

													$security_due = $payable_due > 0 ? $sec_amt :  ($sec_amt - ($bill->paid_amount - $due_amt));

													if ($payable_due <= 0) {
														continue;
													} else {
														if ($payable_due > $pending_to_adjust_left) {
															$amount_to_pay = $pending_to_adjust_left;
															$pending_to_adjust_left = 0;
														} else {
															$amount_to_pay = $payable_due;
															$pending_to_adjust_left = $pending_to_adjust_left - $amount_to_pay;
														}
													}

													$total_payment_now += $amount_to_pay;

													$payable_due = number_format($payable_due, 2, '.', '');
													$security_due = number_format($security_due, 2, '.', '');
													$amount_to_pay = number_format($amount_to_pay, 2, '.', '');
											?>
												<tr id="row-bills-<?php echo $bill->id;?>">
				                                	<td>
				                                		<a href="purchase/bill_print/<?php echo $bill->id ?>" target="_blank"><?php echo $bill->project->code.'-'.$bill->supplier->code.'-'.$bill->item->code.'-'.$bill->code; ?></a>
				                                	</td>
				                                	<td><?php echo $payable_due; ?></td>
				                                	<td>
				                                		<input type="text" name="pay_amount[]" value="<?php echo $amount_to_pay; ?>" id="amount_adjusted_<?php echo $bill->id ?>" class="span5 amount_to_pay">
				                                		<input type="hidden" name="bill_id[]" value="<?php echo $bill->id ?>" >
				                                		<input type="hidden" name="payment_due[]" id="payable_due_<?php echo $bill->id ?>" value="<?php echo $payable_due ?>" >
				                                	</td>
												</tr>
											<?php 
												$c++;
											} 
											?>
		                                </tbody>
		                                <tfoot>
											<tr>
		                                        <th colspan="2">Total Payment Now Tk</th>
		                                        <th id="total_payment_now"><?php echo number_format($total_payment_now, 2, '.', ''); ?></th>                    
		                                    </tr>
		                                    <tr><td colspan="3"></td></tr>
		                                </tfoot>										
									</table>
								</div>
							</fieldset>
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <div class="form-actions">	          
                            	<input type="button" name="cancel" class="btn-lg btn-back" id="cancel_changes" value="Cancel" onclick="history.go(-1)" />                  
	                            <input type="button" name="submit" class="btn-lg btn-success" id="make_payment" value="Payment Adjust Now From Advance" />
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

	// var total_payment_now = <?php echo $total_payment_now ?>;
	var pending_to_adjust = <?php echo $pending_to_adjust ?>;
	// var pending_to_adjust_left = <?php echo $pending_to_adjust_left ?>;

	$('.amount_to_pay').change(function(){
		var amt = parseFloat($(this).val());
		if (isNaN(amt)) {
			amt = 0;
		}
		
		var that = $(this);
		var pending_to_adjust_left = pending_to_adjust;

		var total_payment_now = 0;
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
				
			total_payment_now += val;
		});

		$('#total_payment_now').html(total_payment_now.toFixed(2));

		if (input_err) {
			$.bigBox({
				title : "Validation Error!",
				content : 'One payment amount is greater then due amount. check red box input and correct the amount to pay.' ,
				color : "#C46A69",
				icon : "fa fa-warning shake animated",
				number : "",
				timeout : 4000
			});	
		}

		if (total_payment_now > pending_to_adjust ) {
			$.bigBox({
				title : "Validation Error!",
				content : 'Total Payment exceeded the pending to adjust amount of '+pending_to_adjust+' Tk' ,
				color : "#C46A69",
				icon : "fa fa-warning shake animated",
				number : "",
				timeout : 4000
			});	

			that.val('0');

			total_payment_now = total_payment_now - amt;
			$('#total_payment_now').html(total_payment_now.toFixed(2));
		}


	});
</script>