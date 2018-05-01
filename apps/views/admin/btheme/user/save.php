
<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>User Entry Form</h2>				
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
						
						<form action="user/save" method="post" class="smart-form" id="frmUser">
							<fieldset>
								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="username">Username</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="username" value="<?php if (count($user) > 0) { echo $user->username; } ?>" id="username" class="span5" placeholder="Username"> 
	                                    </label>
	                                </section> 
									<section class="col col-2">
										<label for="user_name" class="control-label">Name</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<input type="text" name="name" value="<?php if (count($user) > 0) { echo $user->name; } ?>" id="user_name" class="span5" placeholder="User Full Name">											
										</label>
									</section>
														
								</div>

								<div class="row">
									<section class="col col-2">
	                                    <label class="control-label" for="password">Password</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="password" value="" id="password" class="span5" placeholder="User Password">
	                                    </label>
	                                </section> 
									<section class="col col-2">
	                                    <label class="control-label" for="password_confirmation">Confirm Password</label>
	                                </section>
	                                <section class="col col-4">
	                                    <label class="input"> 
	                                    	<input type="text" name="password_confirmation" value="" id="password_confirmation" class="span5" placeholder="Confirm Password">
	                                    </label>
	                                </section> 
								</div>

								<div class="row">
									<section class="col col-2">
											<label class="label">User Type</label>
										</section>
										<section class="col col-4">
											<label class="select">
												<select name="user_type" id="user_type" class="span5 chzn-select" data-placeholder="Select Status">
													<?php
													if ($this->session->userdata('user_type')=='sadmin') {
													?>
													<option value="sadmin" <?php if(count($user) > 0 && $user->user_type == 'sadmin') { echo 'selected'; }?>>Super Admin</option>
													<?php
													}
													?>
													<option value="admin" <?php if(count($user) > 0 && $user->user_type == 'admin') { echo 'selected'; }?>>Admin</option>
													<option value="user" <?php if (count($user) > 0 && $user->user_type == 'user') { echo 'selected'; }?>>user</option>
												</select> <i></i>
												
											</label>
										</section>	

										<section class="col col-2">
											<label class="label">Select Status</label>
										</section>
										<section class="col col-4">
											<label class="select">
												<select name="status" id="status" class="span5 chzn-select" data-placeholder="Select Status">
													<option value="Active" <?php if(count($user) > 0 && $user->status == 'Active') { echo 'selected'; }?>>Active</option>
													<option value="Inactive" <?php if (count($user) > 0 && $user->status == 'Inactive') { echo 'selected'; }?>>Inactive</option>
												</select> <i></i>
												
											</label>
										</section>												
								</div>
								<?php 
								if ($this->session->userdata('user_type') == 'sadmin' AND ( (count($user) > 0 && $user->user_type != 'sadmin' ) OR count($user) == 0) ) {
								?>
								<div class="row" id="project_wrap">
									<section class="col col-3">
											<label class="label">Select Projects to grant access</label>
									</section>
									<section class="col col-9">
										<div class="inline-group">
											<?php
											foreach ($projects as $project_id => $name) {
												if (isset($user_project_ids)) {
													if (in_array($project_id, $user_project_ids)) {
														echo '<label class="checkbox">
														<input type="checkbox" checked="checked" name="project[]" value="'.$project_id.'">
														<i></i>'.$name.'</label>';
													} else {
														echo '<label class="checkbox">
														<input type="checkbox" name="project[]" value="'.$project_id.'">
														<i></i>'.$name.'</label>';
													}
												} else {
													echo '<label class="checkbox">
													<input type="checkbox" name="project[]" value="'.$project_id.'">
													<i></i>'.$name.'</label>';
												}
											}
											?>
										</div>
									</section>
								</div>
								<?php
								}
								?>
							</fieldset>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <?php if (count($user) > 0) { ?>
                            <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
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
	
	$('#user_type').change(function(){
		if ($(this).val() == 'sadmin') {
			$('#project_wrap').hide();
		} else {
			$('#project_wrap').show();
		}
	});

	$("#save_changes").click(function(){
		var $btn = $(this);
	    $btn.val('loading');
	    $btn.attr({disabled: true});
		
		$.ajax({
			url : "<?php echo $this->config->item('base_url') ?>user/save",
			type : "post",
			dataType : "json",
			data : $("#frmUser").serialize(),
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
					$("form#frmUser").trigger("reset");
					location.hash = 'user';
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