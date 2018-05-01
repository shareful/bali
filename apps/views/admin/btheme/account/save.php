
<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Account Entry Form</h2>				
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
						
						<form action="account/save" method="post" class="smart-form" id="frmAccount">
							<fieldset>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="account_code">Account Code</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="code" value="<?php if (count($account) > 0) { echo $account->code; }  ?>" id="account_code" class="span5" placeholder="Account Code" readonly>
	                                    </label>
	                                </section> 
									<section class="col col-2">
										<label for="account_name" class="control-label">Account Name</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<input type="text" name="name" value="<?php if (count($account) > 0) { echo $account->name; } ?>" id="account_name" class="span5" placeholder="Account Name">											
										</label>
									</section>
														
								</div>

								<div class="row">
									<section class="col col-2">
										<label class="label">Have Sub Account</label>
									</section>
									<section class="col col-4">
										<label class="select">
											<select name="have_sub" id="have_sub" class="span5 chzn-select" data-placeholder="Select One">
												<option value="Yes" <?php if(count($account) > 0 && $account->have_sub == 'Yes') { echo 'selected'; }?>>Yes</option>
												<option value="No" <?php if (count($account) > 0 && $account->have_sub != 'Yes') { echo 'selected'; }?>>No</option>
											</select> <i></i>
											
										</label>
									</section>	
									<section class="col col-2">
										<label for="notes" class="control-label">Notes</label>
									</section>
									<section class="col col-4">	
										<label class="textarea">
											<textarea type="text" name="notes" id="notes" class="textarea" placeholder="Account Notes"><?php if (count($account) > 0) { echo $account->notes; } ?></textarea>		
										</label>
									</section>	
								</div>
							</fieldset>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <?php if (count($account) > 0) { ?>
                            <input type="hidden" name="acc_id" value="<?php echo $account->acc_id; ?>" />
                            <?php } ?>
	                        <div class="form-actions">
	                            <input type="button" name="cancel" class="btn-lg btn-back" id="cancel_changes" value="Cancel" onclick="history.go(-1)" />
	                            <input type="button" name="submit" class="btn-lg btn-success" id="save_changes" value="Save changes" />
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
	
	$('#account_name').change(function(){
		var name = $(this).val();
		name = name.replace(/ /g, '');
		console.log(name);
		$('#account_code').val(name);
	});

	$("#save_changes").click(function(){
		var $btn = $(this);
	    $btn.val('loading');
	    $btn.attr({disabled: true});
		
		$.ajax({
			url : "<?php echo $this->config->item('base_url') ?>account/save",
			type : "post",
			dataType : "json",
			data : $("#frmAccount").serialize(),
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
					$("form#frmAccount").trigger("reset");
					location.hash = 'account';
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

	$('#code').focus();
	/*$('#create').datepicker({
        dateFormat : 'yy-mm-dd',
        prevText : '<i class="fa fa-chevron-left"></i>',
        nextText : '<i class="fa fa-chevron-right"></i>'
    });*/ 

</script>