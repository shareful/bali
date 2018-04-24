<?php
	$admin_theme = $this->config->item('admin_theme');
	$theme = $this->config->item('theme');
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-file-excel-o"></i> Report <span>> Customer Balance</span></h1>
	</div>
	
</div>

<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
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
						
						<form action="report/customer_balance" method="post" class="smart-form" id="frmreport">
							<fieldset>
								<legend>Filter</legend>
								<div class="row">
									<section class="col col-2">
										<label for="customer_id" class="control-label">Select Customer</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="customer_id" id="customer_id" tabindex="3" class="span5 select2">
	                                            <option value=""></option>
	                                            <?php foreach ($customers as $customer) { ?>
	                                                <option value="<?php echo $customer->customer_id; ?>"><?php echo $customer->code . ' - ' . $customer->name; ?></option>
	                                            <?php } ?>
                                        	</select>
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
	                                            <option value="0"> All </option>
	                                            <?php foreach ($projects as $project) { ?>
	                                                <option value="<?php echo $project->project_id; ?>"><?php echo $project->code . ' - ' . $project->name; ?></option>
	                                            <?php } ?>
                                        	</select>
										</label>
									</section>

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
							</fieldset>

                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
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
					<span class="widget-icon"> <i class="fa fa-table"></i> </span><h6 style="float: left; margin: 0px; padding: 5px;"> Customer Balance Report</h6>
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
	                                        <th>Bill Amount</th>
	                                        <th>Received</th>
	                                        <th></th>             
	                                    </tr>										
									</thead>
									<tbody id="itemGrid">
										<?php 
											if (isset($customer_id)) {
												$this->load->view($admin_theme.'/report/customer_balance_data', array('bill_amount' => $bill_amount, 'received_amount'=> $received_amount, 'balance'=>$balance )); 
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

    $("#filter_report").click(function(){
    	if ($('#customer_id').val() != '') {

    	} else {
    		$.bigBox({
				title : "Validaton Error!",
				content : 'Please select customer!',
				color : "#C46A69",
				icon : "fa fa-warning shake animated",
				number : "",
				timeout : 2000
			});
			return;
    	}

		var $btn = $(this);
	    $btn.val('loading');
	    $btn.attr({disabled: true});
		
		$.ajax({
			url : "<?php echo $this->config->item('base_url') ?>report/customer_balance",
			type : "post",
			dataType : "json",
			data : $("#frmreport").serialize(),
			success : function(data) {
				// console.log(data);
				$btn.attr({disabled: false});
				$btn.val('Submit');
				if (data.success == 'true') {
					$('#itemGrid').html(data.html);
				} 
			},
			error: function(){
				$btn.attr({disabled: false});
				$btn.val('Submit');
				$.bigBox({
					title : "Error!",
					content : 'Please check your connection!',
					color : "#C46A69",
					icon : "fa fa-warning shake animated",
					number : "",
					timeout : 6000
				});
			}
		});
		
	});
</script>	