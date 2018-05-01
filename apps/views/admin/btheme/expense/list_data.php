									<thead>
										<tr>
											<th data-class="expand">SN</th>
											<th data-class="expand">Voucher #</th>
											<th data-class="expand">Amount Tk</th>
											<th data-class="expand">Project Name</th>
											<th data-class="expand">Expense TYpe</th>
											<th data-class="expand">Ref/Invoice #</th>
											<th data-class="expand">Expense Date</th>
											<th data-class="expand">Account</th>
											<th data-class="expand">Notes</th>
											<th style="text-align: center;">Action</th>
										</tr>
									</thead>
									<tbody>
										
										<?php	
										$c = 1;
										$total = 0;
										foreach ($expenses as $expense) { 
											$total += $expense->amount;
										?>
										<tr id="row-expenses-<?php echo $expense->id;?>">
											<td><?php echo $c; ?></td>
											<td><?php echo $expense->code; ?></td>
											<td><?php echo $expense->amount; ?></td>
											<td><?php echo isset($expense->project) ? ($expense->project->code.' - '.$expense->project->name ) : ''; ?></td>
											<td><?php echo ucfirst($expense->exp_type); ?></td>
											<td><?php echo $expense->ref_code; ?></td>
											<td><?php echo date('m/d/Y', strtotime($expense->trans_date)); ?></td>
											<td>
												<?php 
													
													if (isset($expense->subaccount)) {
														echo $expense->subaccount->name.' - '.$expense->subaccount->code;
													} else {
														echo $expense->account->name; 
													}
													if ($expense->check_trans_no) {
														echo '<br>check/trans #'.$expense->check_trans_no;
													}
												?>
											</td>
											<td><?php echo $expense->notes; ?></td>
											<td>
											</td>
										</tr>
										<?php 
											$c++;
										} 
										?>
									</tbody>
									<tfoot>
										<tr>
											<th colspan="2" style="text-align: right; ">TOTAL</th>
											<th colspan="7" style="text-align: left;"><?php echo number_format($total, 2, '.', '') ?> Tk</th>
										</tr>
									</tfoot>