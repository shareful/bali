<div class="modal-header">
	<button type="button" class="close btn btn-danger btn-sm" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title" id="myModalLabel"><i class="fa fa-lg fa-fw fa-list"></i> <strong>Adjustments History of Advance Received # <?php echo $advance->code ?> </strong></h4>
</div>

<div id="main-content">
	<!-- BEGIN PAGE CONTAINER-->
	<div class="container-fluid">
		<!-- BEGIN PAGE HEADER-->
<div class="row-fluid">
	<div class="span12">
		<!-- BEGIN EXAMPLE TABLE widget-->
		<div class="widget blue">
			
			<div class="widget-body">
				<table class="table table-striped table-bordered" id="smith_table">
					<thead>
						<tr>
							<th>SN</th>
							<th>Adjustment Date</th>
							<th>Amount</th>
							<th>Bill #</th>
						</tr>
					</thead>
					<tbody>
						<?php	
						$c = 1;
						$total_adjusted = 0;
						$total_due = 0;
						foreach ($records as $record) { 
							$total_adjusted += $record->amount;
						?>
						<tr>
							<td><?php echo $c; ?></td>
							<td><?php echo date("m/d/Y", strtotime($record->trans_date)); ?></td>
							<td style="text-align: right;"><?php echo currency_format($record->amount); ?></td>
							<td><?php echo $record->bill->invoice_no; ?></td>
						</tr>
						<?php 
							$c++;
						} 
						$total_due = ($advance->amount - $total_adjusted);
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3" style="text-align: right;">
								<strong>TOTAL ADJUSTED :</strong> 
								<strong><?php  echo currency_format($total_adjusted); ?></strong>
							</td>
							<td colspan="2" style="text-align: right;">
								<strong>ADJUSTEMENT DUE :</strong> <strong><?php  echo currency_format($total_due); ?></strong>
							</td>							
						</tr>
					</tfoot>					
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE widget-->
	</div>
</div>

<div class="modal-footer" style="padding: 10px;">
 
</div>