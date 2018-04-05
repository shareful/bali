<div class="modal-header">
	<button type="button" class="close btn btn-danger btn-sm" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title" id="myModalLabel"><i class="fa fa-lg fa-fw fa-list"></i> <strong>Sale Bill # "<?php echo $bill->invoice_no ?>" Payment Received Ledger</strong></h4>
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
							<th>Payment Date</th>
							<th>Amount Received</th>
							<th>Notes</th>
							<th>Comment</th>
						</tr>
					</thead>
					<tbody>
						<?php	
						$c = 1;
						$total_paid = 0;
						$total_due = 0;
						foreach ($payments as $payment) { 
							$total_paid += $payment->amount;
						?>
						<tr>
							<td><?php echo $c; ?></td>
							<td><?php echo date("m/d/Y", strtotime($payment->trans_date)); ?></td>
							<td style="text-align: right;"><?php echo currency_format($payment->amount); ?></td>
							<td><?php echo $payment->notes; ?></td>
							<td>
								<?php
									switch ($payment->src_type) {
										case 'advance':
											echo 'Adjusted from advance payment';
											break;
										case 'security':
											echo 'Adjusted from security payment';
											break;										
									}									
								?>
							</td>							
						</tr>
						<?php 
							$c++;
						} 
						$total_due = ($bill->total_amount - $total_paid);
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3" style="text-align: right;">
								<strong>TOTAL PAYMENT RECEIVED :</strong> 
								<strong><?php  echo currency_format($total_paid); ?></strong>
							</td>
							<td colspan="2" style="text-align: right;">
								<strong>TOTAL DUE :</strong> <strong><?php  echo currency_format($total_due); ?></strong>
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