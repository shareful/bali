<thead>
	<tr>
        <th>SN</th>
        <th>Bill #</th>
        <th>Security Due Tk</th>             
    </tr>										
</thead>
<tbody id="itemGrid">
	<?php	
	$c = 1;

	foreach ($bills as $bill) { 
		$security_due_amt = number_format($bill->security_due_amt, 2, '.', '');		
	?>
	<tr>
	    <td><?php echo $c ?></td>
	    <td><a href="sale/bill_print/<?php echo $bill->id ?>" target="_blank"><?php echo $bill->project->code.'-'.$bill->customer->code.'-'.$bill->item->code.'-'.$bill->code; ?></a></td>
	    <td><?php echo $security_due_amt; ?></td>
	</tr>

	<?php 
	}
	?>
</tbody>
<tfoot>
	<tr>
        <th colspan="2" style="text-align: right;">TOTAL SECURITY DUE TK</th>
        <th><?php echo currency_format($total_amount) ?></th>             
    </tr>										
</tfoot>