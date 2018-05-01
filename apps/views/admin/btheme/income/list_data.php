									<thead>
										<tr>
											<th data-class="expand">SN</th>
											<th data-class="expand">Voucher #</th>
											<th data-class="expand">Amount Tk</th>
											<th data-class="expand">Project Name</th>
											<th data-class="expand">Income Type</th>
											<th data-class="expand">Ref/Invoice #</th>
											<th data-class="expand">Income Date</th>
											<th data-class="expand">Account</th>
											<th data-class="expand">Notes</th>
											<th style="text-align: center;">Action</th>
										</tr>
									</thead>
									<tbody>
										
										<?php	
										$c = 1;
										$total = 0;
										foreach ($incomes as $income) { 
											$total += $income->amount;
										?>
										<tr id="row-incomes-<?php echo $income->id;?>">
											<td><?php echo $c; ?></td>
											<td><?php echo $income->code; ?></td>
											<td><?php echo $income->amount; ?></td>
											<td><?php echo isset($income->project) ? ($income->project->code.' - '.$income->project->name ) : ''; ?></td>
											<td><?php echo ucfirst($income->income_type); ?></td>
											<td><?php echo $income->ref_code; ?></td>
											<td><?php echo date('m/d/Y', strtotime($income->trans_date)); ?></td>
											<td>
												<?php 
													
													if (isset($income->subaccount)) {
														echo $income->subaccount->name.' - '.$income->subaccount->code;
													} else {
														echo $income->account->name; 
													}
													if ($income->check_trans_no) {
														echo '<br>check/trans #'.$income->check_trans_no;
													}
												?>
											</td>
											<td><?php echo $income->notes; ?></td>
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