
<?php
	$admin_theme = $this->config->item('admin_theme');
	$theme = $this->config->item('theme');
?>
<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<header> 
					<span class="widget-icon"> <i class="fa fa-cube"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> Items By Project</h6>
				</header>
				<div class="form-actions">
	                <div class="span12" style="text-align: left;">
	                	<section class="col col-12">
							<label class="select">
								<b>By Project</b> 
								<select name="project_id" id="project_id" class="span5 select12" data-placeholder="">									
									<?php foreach($project_list as $key=>$project_name) {?>
										<option value="<?php  echo $key ;?>" <?php echo $key==$project_id ? 'selected="selected"' : '' ?>><?php echo $project_name; ?></option>
									<?php } ?>	
									</select> 
							</label> 
						</section>      
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
											<th data-class="expand">Item Name</th>
											<th data-class="expand">Project</th>
											<th data-class="expand">Stock Received</th>
											<th data-class="expand">Stock Billed</th>
											<th data-class="expand">Pending to Bill</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="stock_list_body">
										<?php $this->load->view($admin_theme.'/stock/list_only', array('items' => $items, 'project_id'=> $project_id)); ?>
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
	var relaodStock=function(id){
		// if (confirm('Are you sure want to delete the Item?')) {			
			$.ajax({
				url : "<?php echo $this->config->item('base_url') ?>stock/by_project/"+id,
				type : "get",
				// dataType : "json",
				// data : 'id='+id,
				success : function(data) {
					$('#stock_list_body').html(data);
				},
				error: function(){
					$.bigBox({
						title : "Error!",
						content : 'An error occured. Check your connection.',
						color : "#C46A69",
						icon : "fa fa-warning shake animated",
						number : "",
						timeout : 6000
					});
				}
			});
		// }
	}

	//Here we only run
	runAllForms();
	
	// PAGE RELATED SCRIPTS
		

	/*$('#date').datepicker({
		dateFormat : 'yy-mm-dd',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>'
	});	*/

	pageSetUp();
	var pagefunction = function() {	
		$('#project_id').change(function(){			
			relaodStock($(this).val());
		});

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