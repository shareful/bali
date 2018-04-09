<thead>
	<tr>
        <th>SN</th>
        <th>Bill #</th>
        <th>Payment Due Tk</th>             
    </tr>										
</thead>
<tbody id="itemGrid">
	<?php	
	$c = 1;

	foreach ($bills as $bill) { 
		$receivable_due_amt = number_format($bill->receivable_due_amt, 2, '.', '');		
	?>
	<tr>
	    <td><?php echo $c ?></td>
	    <td><a href="sale/bill_print/<?php echo $bill->id ?>" target="_blank"><?php echo $bill->project->code.'-'.$bill->customer->code.'-'.$bill->item->code.'-'.$bill->code; ?></a></td>
	    <td><?php echo $receivable_due_amt; ?></td>
	</tr>

	<?php 
	}
	?>
</tbody>
<tfoot>
	<tr>
        <th colspan="2" style="text-align: right;">TOTAL PAYMENT DUE TK</th>
        <th><?php echo currency_format($total_amount) ?></th>             
    </tr>										
</tfoot>