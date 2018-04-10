<tr>
	<td style="text-align: right;">
		Security <?php echo currency_format($bill_amount['security'])?><br>
		Payment Bill <?php echo currency_format($bill_amount['payable'])?><br>			
	</td>
	<td style="text-align: right;">
		Payment <?php echo currency_format($paid_amount['purchase'])?><br>
		Advance <?php echo currency_format($paid_amount['advance'])?><br>			
		Security <?php echo currency_format($paid_amount['security'])?><br>			
	</td>
	<td>		
	</td>
</tr>
<tr>
	<td><strong>TOTAL <?php echo currency_format($bill_amount['total'])?></strong>
	<td><strong>TOTAL <?php echo currency_format($paid_amount['total'])?></strong>
	<td><strong>PAYABLE <?php echo currency_format($balance)?></strong>
</tr>	