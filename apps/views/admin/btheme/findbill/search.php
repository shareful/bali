<?php
	$admin_theme = $this->config->item('admin_theme');
	$theme = $this->config->item('theme');
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Find a Bill</h1>
	</div>
</div>
<section id="widget-grid">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-search"></i> </span>
					<h2>Bill Search Form</h2>				
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
						
						<form action="findbill/search" method="post" class="smart-form" id="frmFindBill">
							<fieldset>
								<div class="row">
									<section class="col col-2">
										<label for="bill_type" class="control-label">Select Bill Type</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<select name="bill_type" id="bill_type" class="span5 select2">
	                                            <option value="sale"> Sale</option>
	                                            <option value="purchase"> Purchase</option>
	                                        </select>
										</label>
									</section>												
								</div>
								<div class="row">
									<section class="col col-2">
										<label for="bill_no" class="control-label">Bill #</label>
									</section>
									<section class="col col-4">	
										<label class="input">
											<input type="text" name="bill_no" value="" id="bill_no" class="span5" placeholder="Enter Bill number">
										</label>
									</section>									
								</div>
							</fieldset>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <div class="form-actions">
								<input type="button" name="submit" class="btn-lg btn-success" id="search_now" value="Search" />
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
	
	$("#search_now").click(function(){
		var $btn = $(this);
	    $btn.val('loading');
	    $btn.attr({disabled: true});
		
		$.ajax({
			url : "<?php echo $this->config->item('base_url') ?>findbill/search",
			type : "post",
			dataType : "json",
			data : $("#frmFindBill").serialize(),
			success : function(data) {
				$btn.attr({disabled: false});
				$btn.val('Search');
				if (data.bill_id && data.bill_id != '') {
					// $('#search_result_wrap').html(data.html);
					location.hash = 'findbill/result/'+data.bill_id+'/'+data.bill_type;
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
				$btn.val('Search');
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