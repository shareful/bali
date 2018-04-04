
<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Project Entry Form</h2>				
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
						
						<form action="project/save" method="post" class="smart-form" id="frmProject">
							<fieldset>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="code">Project Code</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="code" value="<?php if (count($project) > 0) { echo $project->code; } else { echo $code; } ?>" id="code" class="span5" placeholder="Project Code"> Digit Only
	                                    </label>
	                                </section> 
									<section class="col col-2">
										<label for="project_name" class="control-label">Project Name</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<input type="text" name="name" value="<?php if (count($project) > 0) { echo $project->name; } ?>" id="project_name" class="span5" placeholder="Project Company Name">											
										</label>
									</section>
														
								</div>

								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="address">Address</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="textarea"> 
	                                    	<textarea type="text" name="address" id="address" class="textarea" placeholder="Project Address"><?php if (count($project) > 0) { echo $project->address; } ?></textarea>
	                                    </label>
	                                </section> 
									<section class="col col-2">
										<label for="notes" class="control-label">Notes</label>
									</section>
									<section class="col col-4">	
										<label class="textarea">
											<textarea type="text" name="notes" id="notes" class="textarea" placeholder="Project Notes"><?php if (count($project) > 0) { echo $project->notes; } ?></textarea>		
										</label>
									</section>	
								</div>

								<div class="row">
									<section class="col col-2">
											<label class="label">Select Status</label>
										</section>
										<section class="col col-4">
											<label class="select">
												<select name="status" id="status" class="span5 chzn-select" data-placeholder="Select Status">
													<option value="Active" <?php if(count($project) > 0 && $project->status == 'Active') { echo 'selected'; }?>>Active</option>
													<option value="Inactive" <?php if (count($project) > 0 && $project->status == 'Inactive') { echo 'selected'; }?>>Inactive</option>
												</select> <i></i>
												
											</label>
										</section>												
								</div>
							</fieldset>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <?php if (count($project) > 0) { ?>
                            <input type="hidden" name="project_id" value="<?php echo $project->project_id; ?>" />
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
	
	$("#save_changes").click(function(){
		var $btn = $(this);
	    $btn.val('loading');
	    $btn.attr({disabled: true});
		
		$.ajax({
			url : "<?php echo $this->config->item('base_url') ?>project/save",
			type : "post",
			dataType : "json",
			data : $("#frmProject").serialize(),
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
					$("form#frmProject").trigger("reset");
					location.hash = 'project';
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