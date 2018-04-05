<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Project: <?php echo $bill->project->name ?> <span>> Bill # <?php echo $bill->invoice_no ?></span> <span>> Receive New Payment</span></h1>
	</div>
	
</div>

<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Receive Payment Form</h2>				
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
						
						<form action="payment/receive" method="post" class="smart-form" id="frmpaymentbill">
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
	                                    <label class="control-label" for="received_amount">PAID</label>
	                                </section>
	                                <section class="col col-3">
	                                    <label class="input"> 
	                                    	<input type="text" name="received_amount" value="<?php echo $bill->received_amount?>" id="received_amount" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
	                                <section class="col col-1">
	                                	Tk
	                                </section>
								</div>

								<div class="row">
									<section class="col col-2 has-warning">
	                                    <label class="control-label" for="due_amount"><strong>DUE</strong></label>
	                                </section>
	                                <section class="col col-3 has-warning">
	                                    <label class="input"> 
	                                    	<input type="text" name="due_amount" value="<?php echo $bill->due_amount?>" id="due_amount" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section>
	                                <section class="col col-1">
	                                	Tk
	                                </section>

	                                <section class="col col-2">
										<label for="security_perc" class="control-label">Security %</label>
									</section>
									<section class="col col-4">	
										<label class="input">
                                            <input id="security_perc" type="text" name="security_perc" value="<?php echo $bill->security_perc ?>" placeholder="Securoty Percentage" readonly>
                                        </label>
									</section>
								</div>								
							</fieldset>

							<fieldset>
								<legend>Receive Payment Below</legend>
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
										<label for="amount" class="control-label">Amount Receiving</label>
									</section>
									<section class="col col-3">	
										<label class="input">
	                                        <input id="amount" type="text" name="amount" value="" placeholder="Amount You are Receiving now">
	                                    </label>
									</section>
									<section class="col col-1">
	                                	Tk
	                                </section>
	                            </div>
	                            <div class="row">		
	                            	<section class="col col-2">
										<label for="new_due" class="control-label">Due would be</label>
									</section>
									<section class="col col-3">	
										<label class="input">
	                                        <input id="new_due" type="text" name="new_due" value="" placeholder="Amount would be due now" readonly>
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
							</fieldset>
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <input type="hidden" name="bill_id" id="bill_id" value="<?php echo $bill->id ?>" />
                            <input type="hidden" name="project_id" id="project_id" value="<?php echo $bill->project_id ?>" />
                            <input type="hidden" name="item_id" id="item_id" value="<?php echo $bill->item_id ?>" />
							<input type="hidden" name="src_type" id="src_type" value="bill" />
							
                            <div class="form-actions">	          
                            	<input type="button" name="cancel" class="btn-lg btn-back" id="cancel_changes" value="Cancel" onclick="history.go(-1)" />                  
	                            <input type="button" name="submit" class="btn-lg btn-success" id="receive_payment" value="Receive Payment Now" />
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

	$('#amount').change(function(){
		var paid = parseFloat($('#amount').val());
		var due_amount = parseFloat($('#due_amount').val());
		var new_due_amount = 0;

		if (paid > due_amount) {
			$.bigBox({
				title : "Validation Error!",
				content : "Amount Receiving can't be greater then Due Amount! ",
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

    $("#receive_payment").click(function(){
		if (confirm('Are you sure want to process This Payment?')) {			
			var $btn = $(this);
		    $btn.val('processing...');
		    $btn.attr({disabled: true});
			
			var bill_id = $('#bill_id').val();
			var project_id = $('#project_id').val();
			var item_id = $('#item_id').val();
			
			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>payment/receive/"+bill_id,
				type : "post",
				dataType : "json",
				data : $("#frmpaymentbill").serialize(),
				success : function(data) {
					$btn.attr({disabled: false});
					$btn.val('Receive Payment Now');
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
						window.open('sale/bill_print/'+bill_id, '_blank', 'toolbar=no,scrollbars=yes,resizable=yes,width=1020,height=780');
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
					$btn.val('Receive Payment Now');
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