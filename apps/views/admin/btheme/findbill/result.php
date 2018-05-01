<?php
	$theme = $this->config->item('theme');
	$nul_var1 = null;
	$nul_var2 = null;
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-search"></i> Search Result</h1>
	</div>
</div>
<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<header> 
					<span class="widget-icon"> <i class="fa fa-table"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> <?php echo $bill_type=='purchase' ? 'Purchase Bills' : 'Sales Bills' ?></h6>
				</header>
				<div class="form-actions">
	                <div class="span12 center">
						<div class="jarviswidget-editbox">
							

						</div>
						<div class="widget-body no-padding">
							<div class="table-responsive">
								<?php
									if ($bill_type=='purchase') {
										$bill = purchase_bill_cal_info($bill, $nul_var1, $nul_var2);

										$payable_due_amt_css = '';
										$security_due_amt_css = '';
										
										if ($bill->payable_due_amt > 0) {
											$payable_due_amt_css = 'color: red';
										}

										if ($bill->security_due_amt > 0) {
											$security_due_amt_css = 'color: red';
										}

								?>
										<table class="table table-bordered">
											<thead>
												<tr>
													<th data-class="expand">SN</th>
													<th data-class="expand">Bill #</th>
													<th data-class="expand">Bill Date</th>
													<th data-class="expand">Ref #</th>
													<th data-class="expand">Supplier</th>
													<th data-class="expand">Total Amt (tk)</th>
													<th data-class="expand">Paid Amt (tk)</th>
													<th data-class="expand">Payment Due (tk)</th>
													<th data-class="expand">Sec %</th>
													<th data-class="expand">Sec Due (tk)</th>
													<th data-class="expand">Status</th>
													<th style="text-align: center;">Action</th>
												</tr>
											</thead>
											<tbody>
												<tr id="row-purchases-<?php echo $bill->id;?>">
													<td>1</td>
													<td><a href="purchase/bill_print/<?php echo $bill->id ?>" target="_blank"><?php echo $bill->project->code.'-'.$bill->supplier->code.'-'.$bill->item->code.'-'.$bill->code; ?></a></td>
													<td><?php echo date("m/d/Y", strtotime($bill->bill_date)); ?></td>
													<td><?php echo $bill->ref_no; ?></td>
													<td><?php echo $bill->supplier->name; ?></td>
													<td><?php echo $bill->total_amount; ?></td>
													<td><?php echo $bill->paid_amount; ?></td>
													<td style="<?php echo $payable_due_amt_css; ?>"><?php echo $bill->payable_due_amt; ?></td>
													<td><?php echo $bill->security_perc; ?></td>
													<td style="<?php echo $security_due_amt_css; ?>"><?php echo $bill->security_due_amt; ?></td>
													<td>
														<?php
															
														if ($bill->is_sec_due) {
															echo '<span class="label bg-color-orange pull-right">Security Due</span> ';
														}
														if ($bill->is_payment_due) {
															echo '<span class="label bg-color-pink pull-right">Payment Due</span>';
														}
														?>
													</td>
													<td>
														<?php
														if ($bill->is_sec_due OR $bill->is_payment_due){
														?> 
															<a class="btn btn-edit" href="#payment/make/<?php echo $bill->id;?>"><i class="fa fa-lg fa-fw fa-dollar"></i> Make </a>
														<?php
														}
														?>
														<a class="btn btn-edit" data-toggle="modal" data-target="#remoteModal" href="payment/p_ledger/<?php echo $bill->id;?>"><i class="fa fa-lg fa-fw fa-list"></i> Payments </a>
														<?php
														if ($bill->paid_amount==0) {
														?>
															&nbsp;&nbsp;&nbsp;
															<!-- <button class="btn btn-danger del" onclick="deleteBill(<?php echo $bill->id;?>, this)" ><i class="fa fa-lg fa-fw fa-trash"></i></button> -->
														<?php
														}
														?>
													</td>
												</tr>
											</tbody>
										</table>	
								<?php
								} else if ($bill_type=='sale') {
									$bill = sale_bill_cal_info($bill, $nul_var1, $nul_var2);
									$receivable_due_amt_css = '';
									$security_due_amt_css = '';
									
									if ($bill->receivable_due_amt > 0) {
										$receivable_due_amt_css = 'color: red';
									}

									if ($bill->security_due_amt > 0) {
										$security_due_amt_css = 'color: red';
									}
								?>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th data-class="expand">SN</th>
												<th data-class="expand">Bill #</th>
												<th data-class="expand">Bill Date</th>
												<th data-class="expand">Ref #</th>
												<th data-class="expand">Customer</th>
												<th data-class="expand">Total Amt (tk)</th>
												<th data-class="expand">Received Amt (tk)</th>
												<th data-class="expand">Payment Due (tk)</th>
												<th data-class="expand">Sec %</th>
												<th data-class="expand">Sec Due (tk)</th>
												<th data-class="expand">Status</th>
												<th style="text-align: center;">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr id="row-sales-<?php echo $bill->id;?>">
												<td>1</td>
												<td><a href="sale/bill_print/<?php echo $bill->id ?>" target="_blank"><?php echo $bill->project->code.'-'.$bill->customer->code.'-'.$bill->item->code.'-'.$bill->code; ?></a></td>
												<td><?php echo date("m/d/Y", strtotime($bill->bill_date)); ?></td>
												<td><?php echo $bill->ref_no; ?></td>
												<td><?php echo $bill->customer->name; ?></td>
												<td><?php echo $bill->total_amount; ?></td>
												<td><?php echo $bill->received_amount; ?></td>
												<td style="<?php echo $receivable_due_amt_css; ?>"><?php echo $bill->receivable_due_amt; ?></td>
												<td><?php echo $bill->security_perc; ?></td>
												<td style="<?php echo $security_due_amt_css; ?>"><?php echo $bill->security_due_amt; ?></td>
												<td>
													<?php
														
													if ($bill->is_sec_due) {
														echo '<span class="label bg-color-orange pull-right">Security Due</span> ';
													}
													if ($bill->is_payment_due) {
														echo '<span class="label bg-color-pink pull-right">Payment Due</span>';
													}
													?>
												</td>
												<td>
													<?php
													if ($bill->is_sec_due OR $bill->is_payment_due){
													?> 
														<a class="btn btn-edit" href="#payment/receive/<?php echo $bill->id;?>"><i class="fa fa-lg fa-fw fa-dollar"></i> Receive </a>
													<?php
													}
													?>
													<a class="btn btn-edit" data-toggle="modal" data-target="#remoteModal" href="payment/s_ledger/<?php echo $bill->id;?>"><i class="fa fa-lg fa-fw fa-list"></i> Payments </a>
													<?php
													if ($bill->received_amount==0) {
													?>
														&nbsp;&nbsp;&nbsp;
														<!-- <button class="btn btn-danger del" onclick="deleteBill(<?php echo $bill->id;?>, this)" ><i class="fa fa-lg fa-fw fa-trash"></i></button> -->
													<?php
													}
													?>
												</td>
											</tr>
										</tbody>
									</table>	
								<?php
								}
								?>
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