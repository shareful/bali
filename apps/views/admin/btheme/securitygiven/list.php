
<?php
	$theme = $this->config->item('theme');
?>
<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<header> 
					<span class="widget-icon"> <i class="fa fa-table"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> Security Given List</h6>
				</header>
				<div class="form-actions">
	                <div class="span12 center">
	                	<a class="btn btn-success withpadding" href="#securitygiven/save">Make a New Security Payment</a>                            
	                </div>
	                <br>
					<div class="span12 center">
						<div class="jarviswidget-editbox">
							

						</div>
						<div class="widget-body no-padding">
							<div class="table-responsive">
								<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
									<thead>
										<tr>
											<th data-class="expand">SN</th>
											<th data-class="expand">Code</th>
											<th data-class="expand">Ref #</th>
											<th data-class="expand">Project</th>
											<th data-class="expand">Item</th>
											<th data-class="expand">Supplier Name</th>
											<th data-class="expand">Amount Tk</th>
											<th data-class="expand">Transaction Date</th>
											<th data-class="expand">Adjusted to Bill Tk</th>
											<th data-class="expand">Pending to Adjust Tk</th>
											<th data-class="expand">Notes</th>
											<th style="text-align: center;">Action</th>
										</tr>
									</thead>
									<tbody>
										
										<?php	
										$c = 1;
										foreach ($records as $record) { ?>
										<tr id="row-records-<?php echo $record->id;?>">
											<td><?php echo $c; ?></td>
											<td><?php echo $record->code; ?></td>
											<td><?php echo $record->ref_no; ?></td>
											<td><?php echo $record->project->name; ?></td>
											<td><?php echo isset($record->item) ? $record->item->code.' - '.$record->item->name : ''; ?></td>
											<td><?php echo $record->supplier->code.' - '.$record->supplier->name; ?></td>
											<td><?php echo $record->amount; ?></td>
											<td><?php echo date("m/d/Y", strtotime($record->trans_date)); ?></td>
											<td><?php echo $record->amount_adjusted; ?></td>
											<td><?php echo number_format($record->amount - $record->amount_adjusted, 2, '.', ''); ?></td>
											<td><?php echo $record->notes; ?></td>
											<td>
												
												<a class="btn btn-edit" href="#securitygiven/adjust/<?php echo $record->id;?>"><i class="fa fa-lg fa-fw fa-edit"></i> Adjust</a>
												<a class="btn btn-edit" data-toggle="modal" data-target="#remoteModal" href="securitygiven/ledger/<?php echo $record->id;?>"><i class="fa fa-lg fa-fw fa-list"></i> Adjustments </a>
											</td>
										</tr>
										<?php 
											$c++;
										} 
										?>
									</tbody>
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
	var deleteCustomer=function(id){
		if (confirm('Are you sure want to delete the Customer?')) {			
			var $btn = $(this);
			$btn.val('loading');
			$btn.attr({disabled: true});

			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>customer/delete",
				type : "post",
				dataType : "json",
				data : 'id='+id,
				success : function(data) {
					$btn.attr({disabled: false});
					$btn.val('Save changes');
					if (data.success == 'true') {
						$('#row-records-'+id).fadeOut().remove();
						$.bigBox({
							title : "Success",
							content : "Customer deleted.",
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

	//Here we only run
	runAllForms();
	
	// PAGE RELATED SCRIPTS
		

	$('#date').datepicker({
		dateFormat : 'yy-mm-dd',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>'
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