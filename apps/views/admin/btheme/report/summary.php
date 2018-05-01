<?php
	$admin_theme = $this->config->item('admin_theme');
	$theme = $this->config->item('theme');
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-file-excel-o"></i> Report <span>> At a Glance</span></h1>
	</div>
	
</div>

<section id="widget-grid">
	<div class="row">
		<?php
		$hide_filter = true;
		// if (isset($params) AND !empty($params)) {
		// 	$hide_filter = true;
		// }
		if ($hide_filter) {
			echo '<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4"><a href="javascript://" onclick="$(\'#report-filter\').slideToggle();">Filter Report </a></div>';
		}
		?>
		<article class="col-sm-12 col-md-12 col-lg-12" id="report-filter" <?php echo $hide_filter ? 'style="display: none;"' : '';?> >
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Report Filter</h2>				
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
						
						<form action="report/profit" method="post" class="smart-form" id="frmreport">
							<fieldset>
								<legend>Filter</legend>
								<div class="row">
									<section class="col col-2">
										<label for="project_id" class="control-label">Select Project</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="project_id" id="project_id" tabindex="3" class="span5 select2">
	                                            <option value="0"> All </option>
	                                            <?php foreach ($projects as $project) { ?>
	                                                <option value="<?php echo $project->project_id; ?>" <?php echo (isset($params['project_id']) AND $params['project_id'] == $project->project_id) ? 'selected' : ''; ?>><?php echo $project->code . ' - ' . $project->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>

									<!-- <section class="col col-2">
										<label for="item_id" class="control-label">Select Item</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="item_id" id="item_id" tabindex="3" class="span5 select2">
	                                            <option value=""> None </option>
	                                            
                                        	</select>
										</label>
									</section> -->
								</div>

								<div class="row">
									<section class="col col-2">
										<label for="from_date" class="control-label">From Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input id="from_date" type="text" name="from_date" value="<?php echo isset($params['from_date']) ? $params['from_date'] : '' ?>">
                                        </label>
									</section>

									<section class="col col-2">
										<label for="to_date" class="control-label">To Date</label>
									</section>
									<section class="col col-4">	
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
                                            <input id="to_date" type="text" name="to_date" value="<?php echo isset($params['to_date']) ? $params['to_date'] : date("m-d-Y") ?>">
                                        </label>
									</section>									
								</div>
							</fieldset>

                            <!-- <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> -->
                            <div class="form-actions">
	                            <input type="button" name="submit" class="btn-lg btn-success" id="filter_report" value="Submit" />
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

<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
				<header> 
					<span class="widget-icon"> <i class="fa fa-table"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> At a Glance</h6>
				</header>
				<div class="form-actions">
	                <div class="span12 center">
						<div class="jarviswidget-editbox">
						</div>
						<div class="widget-body no-padding">
							<div class="table-responsive" id="sales_details2">
								<table class="table table-striped table-bordered table-hover display compact" cellspacing="0"  width="100%">
									<thead>
										<tr>
											<th colspan="2" style="text-align: center; background-color: #ccc;">Expenditure</th>
										</tr>
										<tr>
	                                        <th>Expense</th>
	                                        <th><?php echo currency_format($other_expense) ?></th>             
	                                    </tr>	
	                                    <tr>
	                                        <th>Bill Paid</th>
	                                        <th><?php echo currency_format($bill_expense) ?></th>             
	                                    </tr>				
	                                    <tr>
	                                        <th>Advance Paid</th>
	                                        <th><?php echo currency_format($advance_expense) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th>Security Paid</th>
	                                        <th><?php echo currency_format($security_expense) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th style="text-align: right;">TOTAL</th>
	                                        <th><?php echo currency_format($total_expense) ?></th>
	                                    </tr>

	                                    <tr>
											<th colspan="2" style="text-align: center; background-color: #ccc;">Incomes</th>
										</tr>
										<tr>
	                                        <th>Bill Payment Received</th>
	                                        <th><?php echo currency_format($bill_income) ?></th>
	                                    </tr>				
	                                    <tr>
	                                        <th>Advance Received</th>
	                                        <th><?php echo currency_format($advance_income) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th>Security Received</th>
	                                        <th><?php echo currency_format($security_income) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th>Investment Received</th>
	                                        <th><?php echo currency_format($invest_income) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th>Other Incomes</th>
	                                        <th><?php echo currency_format($other_income) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th style="text-align: right;">TOTAL</th>
	                                        <th><?php echo currency_format($total_income) ?></th>
	                                    </tr>
	                                    
	                                    <tr>
											<th colspan="2" style="text-align: center; background-color: #ccc;">Payable</th>
										</tr>
										<tr>
	                                        <th>Bill Payable</th>
	                                        <th><?php echo currency_format($bill_payable) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th>Security Payable</th>
	                                        <th><?php echo currency_format($security_payable) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th style="text-align: right;">TOTAL</th>
	                                        <th><?php echo currency_format($total_payable) ?></th>
	                                    </tr>

	                                    <tr>
											<th colspan="2" style="text-align: center; background-color: #ccc;">Receivable</th>
										</tr>
										<tr>
	                                        <th>Bill Receivable</th>
	                                        <th><?php echo currency_format($bill_receivable) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th>Security Receivable</th>
	                                        <th><?php echo currency_format($security_receivable) ?></th>
	                                    </tr>
	                                    <tr>
	                                        <th style="text-align: right;">TOTAL</th>
	                                        <th><?php echo currency_format($total_receivable) ?></th>
	                                    </tr>

	                                    <tr>
											<th colspan="2" style="text-align: center; background-color: #ccc;">Accounts Balance</th>
										</tr>
										<?php
										if (isset($opening_balance)) {
										?>
											<tr>
		                                        <th>BALANCE FORWARDED</th>
		                                        <th><?php echo currency_format($opening_balance) ?></th>
		                                    </tr>
										<?php
										}

										foreach ($accounts as $account) {
										?>
											<tr>
		                                        <th><?php echo $account->name ?></th>
		                                        <th><?php echo currency_format($account->balance) ?></th>
		                                    </tr>
										<?php
											if ($account->have_sub == 'Yes' AND isset($account->subaccounts)) {
													foreach ($account->subaccounts as $subaccount) {
												?>
													<tr>
				                                        <td>(<?php echo $subaccount->name ?>)</td>
				                                        <td>(<?php echo currency_format($subaccount->balance) ?>)</td>
				                                    </tr>
												<?php
													}
											}
										}
										?>
	                                    <!-- <tr>
											<th colspan="2" style="text-align: center; background-color: #ccc;" height="20">&nbsp;</th>
										</tr> -->
										<tr>
	                                        <th style="text-align: right;">CLOSING BALANCE</th>
	                                        <th><?php echo currency_format($net_closing_balance) ?></th>
	                                    </tr>
									</thead>
								</table>
							</div>
						</div>						
					</div>
        		</div>
			</div>
		</article>
	</div>
</section>

<script type="text/javascript">

	runAllForms();

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

    /*$("#project_id").change(function(){
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
    });*/
    
    $("#filter_report").click(function(){		
			location.hash = 'report/summary?'+$("#frmreport").serialize();		
	});

</script>	