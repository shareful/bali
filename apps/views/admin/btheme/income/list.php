
<?php
	$admin_theme = $this->config->item('admin_theme');
	$theme = $this->config->item('theme');
?>
<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<header> 
					<span class="widget-icon"> <i class="fa fa-table"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> Income List</h6>
				</header>
				<div class="form-actions">
					<div class="span12" style="text-align: left;">
						<form action="income/index" method="post" class="smart-form" id="frmreport">
							<fieldset>
								
								<div class="row">
									<section class="col col-2">
										<label for="from_date" class="control-label">From Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input id="from_date" type="text" name="from_date" value="">
                                        </label>
									</section>

									<section class="col col-2">
										<label for="to_date" class="control-label">To Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input id="to_date" type="text" name="to_date" value="<?php echo date("m-d-Y"); ?>">
                                        </label>
									</section>				
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="project_id" class="control-label">Select Project</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="project_id" id="project_id" tabindex="3" class="span5 select2">
	                                            <option value=""> All </option>
	                                            <?php foreach ($projects as $project) { ?>
	                                                <option value="<?php echo $project->project_id; ?>" <?php echo (isset($params['project_id']) AND $params['project_id'] == $project->project_id) ? 'selected' : ''; ?>><?php echo $project->code . ' - ' . $project->name; ?></option>
	                                            <?php } ?>
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
	                                                <option value="<?php echo $acc->acc_id; ?>" <?php echo (isset($params['acc_id']) AND $params['acc_id'] == $acc->acc_id) ? 'selected' : ''; ?>><?php echo $acc->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>

									<section class="col col-2 sub_acc_wrap" style="<?php echo !isset($subaccounts) ? 'display: none;' : '' ?>">
										<label for="sub_acc_id" class="control-label">Sub Account</label>
									</section>
									<section class="col col-4 sub_acc_wrap" style="<?php echo !isset($subaccounts) ? 'display: none;' : '' ?>">	
										<label class="input">
											<select name="sub_acc_id" id="sub_acc_id" tabindex="3" class="span5 select2">
	                                            <option value="">Select One</option>
	                                            <?php 
	                                            if (isset($subaccounts) and !empty($subaccounts)) {
		                                            foreach ($subaccounts as $subaccount) { ?>
		                                                <option value="<?php echo $subaccount->sub_acc_id; ?>" <?php echo (isset($params['sub_acc_id']) AND $params['sub_acc_id'] == $subaccount->sub_acc_id) ? 'selected' : ''; ?>><?php echo $subaccount->name; ?></option>
	                                            <?php 
	                                            	}
		                                        } 
		                                        ?>
                                        	</select>
										</label>
									</section>
								</div>

							</fieldset>

							<!-- <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> -->
                            <div class="form-actions">
	                            <input type="button" name="submit" class="btn btn-success withpadding" id="filter_report" value="Submit" />
	                        </div>
	                        <div style="clear: both; padding-bottom: 10px;"></div>
						</form>	
	                </div>
	                <div class="span12 center" style="text-align: left;">
	                	<a class="btn btn-success withpadding" href="#income/save">New Income</a>
	                </div>
	                <br>
					<div class="span12 center">
						<div class="jarviswidget-editbox">
							

						</div>
						<div class="widget-body no-padding">
							<div class="table-responsive">
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
									<?php $this->load->view($admin_theme.'/income/list_data', array('incomes' => $incomes )); ?>
								</table>
							</div>
						</div>
					</div>
        		</div>
			</div>
		</article>
	</div>
</section>					

<!-- Dynamic Modal -->  
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">  
	<div class="modal-dialog">  
		<div class="modal-content">
			<!-- content will be filled here from "ajax/modal-content/model-content-1.html" -->
		</div>  
	</div>  
</div>  
<!-- /.modal --> 

<script type="text/javascript">
	var deleteIncome=function(id){
		if (confirm('Are you sure want to delete the Income?')) {			
			var $btn = $(this);
			$btn.val('loading');
			$btn.attr({disabled: true});

			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>income/delete",
				type : "post",
				dataType : "json",
				data : 'id='+id,
				success : function(data) {
					$btn.attr({disabled: false});
					$btn.val('Save changes');
					if (data.success == 'true') {
						$('#row-incomes-'+id).fadeOut().remove();
						$.bigBox({
							title : "Success",
							content : "Income deleted.",
							color : "#739E73",
							timeout: 8000,
							icon : "fa fa-check",
							number : ""
						});
						
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
		}
	}

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

	//Here we only run
	runAllForms();
	
	// PAGE RELATED SCRIPTS
		

	$('#from_date').datepicker({
		dateFormat : 'mm-dd-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>'
	});	

	$('#to_date').datepicker({
		dateFormat : 'mm-dd-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>'
	});	

	$("#filter_report").click(function(){		
			location.hash = 'income/index?'+$("#frmreport").serialize();		
	});

	pageSetUp();
	var pagefunction = function() {	
	
		$('#dt_basic').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"oLanguage": {
				"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
			},	
			"autoWidth" : true,
			
		});
		/* END BASIC */
	}
	// load related plugins
	
	loadScript("assets/<?php echo $theme; ?>/js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("assets/<?php echo $theme; ?>/js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("assets/<?php echo $theme; ?>/js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("assets/<?php echo $theme; ?>/js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("assets/<?php echo $theme; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});

</script>

<style type="text/css">
<!--
.modal-dialog {
    margin: 30px auto;
    width: 80%;
}

.close {
    opacity: 1;
	color: #fff;
}

button.close {
    background-color: #a90329;
    padding: 6px 10px 5px;
}

-->
</style>