<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

function purchase_bill_cal_info($bill, &$pending_to_adjust_left=null, &$sec_pending_to_adjust_left=null){

	$is_sec_due = false;
	$is_payment_due = false;
	$amount_can_pay = 0;
	$sec_amount_can_pay = 0;

	$bill_sec_amt = ($bill->total_amount - $bill->payable_amount);
	$bill_due_amt = ($bill->total_amount - $bill->paid_amount);

	if (is_null($pending_to_adjust_left)) {
		$pending_to_adjust_left = $bill_due_amt;
	}

	if ($bill_due_amt == 0) {
		# do nothing...
	} else if ($bill_due_amt > $bill_sec_amt) {
		// Security and Payment both are due
		if ($bill_sec_amt > 0) {
			$is_sec_due = true;
		}
		$is_payment_due = true;
	} else if ($bill_sec_amt > 0) {
		// Payment are clear but security due
		$is_sec_due = true;
	}

	$payable_due_amt = $bill->payable_amount - $bill->paid_amount;
	if ($payable_due_amt < 0) {
		$payable_due_amt = 0;
	}

	$security_due_amt =$bill_sec_amt - (($bill->paid_amount > $bill->payable_amount) ? ($bill->paid_amount - $bill->payable_amount) : 0);
	// $security_due_amt = $payable_due_amt > 0 ? $bill_sec_amt :  ($bill_sec_amt - ($bill->paid_amount - $bill_due_amt));

	if (is_null($sec_pending_to_adjust_left)) {
		$sec_pending_to_adjust_left = $security_due_amt;
	}

	if ($payable_due_amt <= 0) {
		$amount_can_pay = 0;
	} else {
		if ($payable_due_amt > $pending_to_adjust_left) {
			$amount_can_pay = $pending_to_adjust_left;
			$pending_to_adjust_left = 0;
		} else {
			$amount_can_pay = $payable_due_amt;
			$pending_to_adjust_left = $pending_to_adjust_left - $amount_can_pay;
		}
	}

	if ($security_due_amt <= 0) {
		$sec_amount_can_pay = 0;
	} else {
		if ($security_due_amt > $sec_pending_to_adjust_left) {
			$sec_amount_can_pay = $sec_pending_to_adjust_left;
			$sec_pending_to_adjust_left = 0;
		} else {
			$sec_amount_can_pay = $security_due_amt;
			$sec_pending_to_adjust_left = $sec_pending_to_adjust_left - $sec_amount_can_pay;
		}
	}

	$payable_due_amt = number_format($payable_due_amt, 2, '.', '');
	$security_due_amt = number_format($security_due_amt, 2, '.', '');
	$amount_can_pay = number_format($amount_can_pay, 2, '.', '');
	$sec_amount_can_pay = number_format($sec_amount_can_pay, 2, '.', '');
	$bill_due_amt = number_format($bill_due_amt, 2, '.', '');

	// Assign new calculated values
	$bill->is_sec_due = $is_sec_due;
	$bill->is_payment_due = $is_payment_due;
	$bill->bill_sec_amt = $bill_sec_amt;
	$bill->bill_due_amt = $bill_due_amt;
	$bill->payable_due_amt = $payable_due_amt;
	$bill->security_due_amt = $security_due_amt;
	$bill->amount_can_pay = $amount_can_pay;
	$bill->sec_amount_can_pay = $sec_amount_can_pay;

	return $bill;
}

function sale_bill_cal_info($bill, &$pending_to_adjust_left=null, &$sec_pending_to_adjust_left=null){

	$is_sec_due = false;
	$is_payment_due = false;
	$amount_can_receive = 0;
	$sec_amount_can_receive = 0;

	$bill_sec_amt = ($bill->total_amount - $bill->receivable_amount);
	$bill_due_amt = ($bill->total_amount - $bill->received_amount);

	if (is_null($pending_to_adjust_left)) {
		$pending_to_adjust_left = $bill_due_amt;
	}

	if ($bill_due_amt == 0) {
		# do nothing...
	} else if ($bill_due_amt > $bill_sec_amt) {
		// Security and Payment both are due
		if ($bill_sec_amt > 0) {
			$is_sec_due = true;
		}
		$is_payment_due = true;
	} else if ($bill_sec_amt > 0) {
		// Payment are clear but security due
		$is_sec_due = true;
	}

	$receivable_due_amt = $bill->receivable_amount - $bill->received_amount;
	if ($receivable_due_amt < 0) {
		$receivable_due_amt = 0;
	}

	$security_due_amt =$bill_sec_amt - (($bill->received_amount > $bill->receivable_amount) ? ($bill->received_amount - $bill->receivable_amount) : 0);
	// $security_due_amt = $receivable_due_amt > 0 ? $bill_sec_amt :  ($bill_sec_amt - ($bill->received_amount - $bill_due_amt));
	if (is_null($sec_pending_to_adjust_left)) {
		$sec_pending_to_adjust_left = $security_due_amt;
	}

	if ($receivable_due_amt <= 0) {
		$amount_can_receive = 0;
	} else {
		if ($receivable_due_amt > $pending_to_adjust_left) {
			$amount_can_receive = $pending_to_adjust_left;
			$pending_to_adjust_left = 0;
		} else {
			$amount_can_receive = $receivable_due_amt;
			$pending_to_adjust_left = $pending_to_adjust_left - $amount_can_receive;
		}
	}


	if ($security_due_amt <= 0) {
		$sec_amount_can_receive = 0;
	} else {
		if ($security_due_amt > $pending_to_adjust_left) {
			$sec_amount_can_receive = $pending_to_adjust_left;
			$pending_to_adjust_left = 0;
		} else {
			$sec_amount_can_receive = $security_due_amt;
			$pending_to_adjust_left = $pending_to_adjust_left - $sec_amount_can_receive;
		}
	}

	$receivable_due_amt = number_format($receivable_due_amt, 2, '.', '');
	$security_due_amt = number_format($security_due_amt, 2, '.', '');
	$amount_can_receive = number_format($amount_can_receive, 2, '.', '');
	$sec_amount_can_receive = number_format($sec_amount_can_receive, 2, '.', '');
	$bill_due_amt = number_format($bill_due_amt, 2, '.', '');

	// Assign new calculated values
	$bill->is_sec_due = $is_sec_due;
	$bill->is_payment_due = $is_payment_due;
	$bill->bill_sec_amt = $bill_sec_amt;
	$bill->bill_due_amt = $bill_due_amt;
	$bill->receivable_due_amt = $receivable_due_amt;
	$bill->security_due_amt = $security_due_amt;
	$bill->amount_can_receive = $amount_can_receive;
	$bill->sec_amount_can_receive = $sec_amount_can_receive;

	return $bill;
}