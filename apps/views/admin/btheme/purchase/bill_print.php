<?php
$null_var1 = null;
$null_var2 = null;
$bill = purchase_bill_cal_info($bill, $null_var1, $null_var2);
?>
                        <div class="row-fluid invoice-list">
                            <div class="span4">
                                <h4>SUPPLIER ADDRESS</h4>
                                <p>
                                    <?php echo $bill->supplier->name; ?><br>
                                    <?php echo $bill->supplier->address; ?><br>
                                    Mobile : <?php echo $bill->supplier->phone; ?><br>
                                </p>
                            </div>
                            <div class="span4">
                                <h4>INVOICE INFO</h4>
                                <ul class="unstyled">
                                    <li>Invoice Number : <strong><?php echo $bill->invoice_no; ?></strong></li>
                                    <li>Invoice Date : <?php echo date('jS M, Y ', strtotime($bill->bill_date)); ?></li>
                                    <li>Reference Number : <strong><?php echo $bill->ref_no; ?></strong></li>
                                </ul>
                            </div>
                        </div>
                        <div class="space20"></div>
                        <div class="space20"></div>
                        <div class="row-fluid">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>ITEM DESCRIPTIONS</th>
                                        <th class="right">QTY</th>
                                        <th class="right">PRICE</th>
                                        <th class="right">TOTAL PRICE</th>
                                    </tr>
                                </thead>
                                <tbody>                                    
                                    <tr>
                                        <td>1</td>
                                        <td><?php echo $bill->item->name; ?></td>
                                        <td class="right"><?php echo $bill->quantity; ?></td>
                                        <td class="right"><?php echo number_format($bill->price, 2); ?></td>
                                        <td class="right"><?php echo number_format($bill->total_amount, 2); ?></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="space20"></div>
                        <div class="row-fluid">                            
                            <div class="span4 invoice-block pull-right">
                                <ul class="unstyled amounts">
                                    <li><strong>SUBTOTAL :</strong> <?php echo currency_format($bill->total_amount); ?></li>
                                    <?php
                                    $grand_total = $bill->total_amount;
                                        echo '<li><strong>PAID: </strong>'.currency_format($bill->paid_amount).'</li>';      
                                        
                                        echo '<li><strong>Pament Due: </strong>'.currency_format($bill->payable_due_amt).'</li>';      
                                        echo '<li><strong>Security Due: </strong>'.currency_format($bill->security_due_amt).'</li>';      
                                        $grand_total = $bill->total_amount - $bill->paid_amount;
                                    ?>
                                    <li><strong>TOTAL DUE :</strong> <?php echo currency_format($grand_total); ?> </li>
                                </ul>
                            </div>
                            <div class="span4 pull-left">
                                <br>
                                <br>
                                <br>
                                <br>
                                ---------------------------------------<br>
                                Authorized Signatures
                            </div>
                        </div>
                        