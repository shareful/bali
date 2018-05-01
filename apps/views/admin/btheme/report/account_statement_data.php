<?php
	$theme = $this->config->item('theme');
	$nul_var1 = null;
	$nul_var2 = null;
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-bank"></i> Account Statement
			<small><small><small> > 
			<?php 
			if (isset($from_date)) {
				echo ' From date '.date("m/d/Y", strtotime($from_date));
			} else {
				echo 'From Beginning date ';
			}

			if (isset($to_date)) {
				echo ' To '.date("m/d/Y", strtotime($to_date));
			}
			?>
			</small></small></small>
		</h1>
	</div>
</div>
<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<header> 
					<span class="widget-icon"> <i class="fa fa-table"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> 
						<?php echo $account->name . (isset($subaccount) ? ' - ' . $subaccount->name .' # '.$subaccount->code : '' ) ;?>
						</h6>
				</header>
				<div class="form-actions">
	                <div class="span12 center">
						<div class="jarviswidget-editbox"></div>
						<div class="widget-body no-padding">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th data-class="expand">SN</th>
											<th data-class="expand">Trans Date</th>
											<th data-class="expand">Trans Type</th>
											<th data-class="expand">Ref/Invoice #</th>
											<th data-class="expand">Amount</th>
											<th data-class="expand">Check / Trans No.</th>
											<th data-class="expand">Notes</th>
										</tr>
										<?php
										if (isset($opening_balance)) {
										?>
										<tr>
											<th colspan="4" style="text-align: right;">BALANCE FORWARDED</th>
											<th style="text-align: right;"><?php echo number_format($opening_balance, 2, '.', '') ;?></th>
											<th></th>
										</tr>
										<?php
										}
										?>
									</thead>
									<tbody>
										<?php
										$c = 1;
										$total = 0;
										if (isset($opening_balance)){
											$total += $opening_balance;
										}

										foreach ($rows as $row) {
										?>
											<tr id="row-<?php echo $c?>">
												<td><?php echo $c ?></td>
												<td><?php echo date("m/d/Y", strtotime($row->trans_date)) ?></td>
												<td><?php echo ucfirst($row->trans_type) ?></td>
												<td><?php echo $row->ref_code ?></td>
												<td><?php echo number_format($row->amount, 2, '.', '') ?></td>
												<td><?php echo $row->check_trans_no ?></td>
												<td><?php echo $row->notes ?></td>
											</tr>
										<?php
											$c++;
											$total += $row->amount;
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<th colspan="4" style="text-align: right;">BALANCE</th>
											<th style="text-align: right;"><?php echo number_format($total, 2, '.', '') ;?></th>
											<th></th>
										</tr>
									</tfoot>
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

	//Here we only run
	runAllForms();
	
	// PAGE RELATED SCRIPTS
		
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