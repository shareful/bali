<?php	
$c = 1;
foreach ($items as $item) { ?>
<tr id="row-items-<?php echo $item->item_id;?>">
	<td><?php echo $c; ?></td>
	<td><?php echo $item->code; ?></td>
	<td align="left"><?php echo $item->name; ?></td>
	<td><?php echo $item->project_name ? $item->project_name : 'All'; ?></td>
	<td><?php echo $item->stock ? $item->stock : 0; ?> <?php echo $item->unit_name; ?></td>
	<td><?php echo $item->billed ? $item->billed : 0; ?> <?php echo $item->unit_name; ?></td>
	<td><?php echo ($item->stock-$item->billed) ?> <?php echo $item->unit_name; ?></td>
	<td>
		<a class="btn btn-edit" href="#sale/index/<?php echo $project_id ;?>/<?php echo $item->item_id;?>"><i class="fa fa-lg fa-fw fa-file-text-o"></i> Sales Bills</a>
		<a class="btn btn-edit" href="#purchase/index/<?php echo $project_id ;?>/<?php echo $item->item_id;?>"><i class="fa fa-lg fa-fw fa-file-text"></i> Purchase Bills</a>
		
	</td>
</tr>
<?php 
	$c++;
} 
?>