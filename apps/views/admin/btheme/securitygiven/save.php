<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Make a New Security Payment Form</h2>				
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
						
						<form action="securitygiven/save" method="post" class="smart-form" id="fromsecuritygiven">
							<fieldset>
								<legend>Info</legend>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="code">Code #</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="code" value="<?php echo $code ?>" id="code" class="span5" placeholder="Will generate automatically" readonly="readonly">
	                                    </label>
	                                </section> 

	                                <section class="col col-2">
										<label for="trans_date" class="control-label">Transaction Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input id="trans_date" type="text" name="trans_date" value="<?php echo date("m-d-Y", now()); ?>" readonly>
                                        </label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="code">Ref No</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="ref_no" value="" id="ref_no" class="span5" placeholder="Money Receipt No">
	                                    </label>
	                                </section>

									<section class="col col-2">
	                                    <label class="control-label" for="amount">Amount Paying</label>
	                                </section>
	                                <section class="col col-3">
	                                    <label class="input"> 
	                                    	<input type="text" name="amount" value="" id="amount" class="span5">
	                                    </label>
	                                </section>
	                                <section class="col col-1">
	                                	Tk
	                                </section>	                                
								</div>

								<div class="row">
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

								<div class="row">
									<section class="col col-2">
										<label for="item_id" class="control-label">Select Item</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="item_id" id="item_id" tabindex="3" class="span5 select2">
	                                            <option value=""> None </option>
                                        	</select>
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
                            <div class="form-actions">	          
                            	<input type="button" name="cancel" class="btn-lg btn-back" id="cancel_changes" value="Cancel" onclick="history.go(-1)" />                  
	                            <input type="button" name="submit" class="btn-lg btn-success" id="make_payment" value="Make Security Payment Now" />
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
    	}
    });

    $("#make_payment").click(function(){
		// if (confirm('Are you sure want to process This Payment?')) {			
			var $btn = $(this);
		    $btn.val('processing...');
		    $btn.attr({disabled: true});
			

			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>securitygiven/save/",
				type : "post",
				dataType : "json",
				data : $("#fromsecuritygiven").serialize(),
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
						$("form#fromsecuritygiven").trigger("reset");
						// window.open('securitygiven/receipt/'+data.id, '_blank', 'toolbar=no,scrollbars=yes,resizable=yes,width=1020,height=780');
						location.hash = 'securitygiven/index';
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
		// }
		
	});
</script>	