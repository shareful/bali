<tr>
	<td style="text-align: right;">
		Security <?php echo currency_format($bill_amount['security'])?><br>
		Payment Bill <?php echo currency_format($bill_amount['receivable'])?><br>			
	</td>
	<td style="text-align: right;">
		Payment <?php echo currency_format($received_amount['sale'])?><br>
		Advance <?php echo currency_format($received_amount['advance'])?><br>			
		Security <?php echo currency_format($received_amount['security'])?><br>			
	</td>
	<td>		
	</td>
</tr>
<tr>
	<td><strong>TOTAL <?php echo currency_format($bill_amount['total'])?></strong>
	<td><strong>TOTAL <?php echo currency_format($received_amount['total'])?></strong>
	<td><strong>RECEIVABLE <?php echo currency_format($balance)?></strong>
</tr>	