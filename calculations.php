<?php

$overallSolutions = array();

$numberOfAttempts = 3;

$numberOfGuessesPerAttempt = 10000;

$maxDifference = 3;

for($x = 0; $x < $numberOfAttempts; $x++) {
	$invoices = array(
		474.38
	);

	$payments = array(
		38.53
	);
	echo "<h2>Attempt ".($x + 1)."</h3>";
	unset($solutions);
	$solutions= array();
	for($i = 0; $i < count($invoices); $i++){
		$count = 0;
		$numberOfGuessesPerAttempt; //
		$maxAbove = 0;
		$minBelow = 0;
		$increment = (1/($numberOfGuessesPerAttempt)) * $maxDifference;
		while ($count < $numberOfGuessesPerAttempt) {
			$maxAbove += $increment;
			$minBelow -= $increment;
			$count++;
			unset($options);
			$options = array();
			$total= 0;
			shuffle($invoices);
			for($j = $i; $j >= 0; $j--) {
				$options[$j]= $j;
				$total += $invoices[$j];
			}
			foreach($payments as $paymentIndex => $payment){
				if($payment > 0 ) {
					$difference = $payment - $total;
					if($difference > $minBelow && $difference < $maxAbove) {
						$paymentKey = "A".intval($payment * 100);
						$payments[$paymentIndex] = 0;
						unset($sortedInvoices);
						$sortedInvoices = array();
						foreach($options as $optionKey => $option){
							if($invoices[$optionKey]) {
								$sortedInvoices[$optionKey] = $invoices[$optionKey];
								$invoices[$optionKey] = 0;
							}
						}
						sort($sortedInvoices);
						$test = 0;
						$v = "MATCH FOR $payment: <br />";
						foreach($sortedInvoices as $option => $sortedAmount){
							$test +=$sortedAmount;
							$v .= "<br />\t\t".$sortedAmount;
						}
						$v .= "<br />\t\t=============<br />\t\t ".($test -$payment)." ";
						$newOption = true;
						if(isset($overallSolutions[$paymentKey]) && count($overallSolutions[$paymentKey])) {
							foreach($overallSolutions[$paymentKey] as $paymentOption) {
								if($paymentOption) {
									if($paymentOption == $v) {
										$newOption = false;
									}
								}
							}
						}
						$overallSolutions[$paymentKey][] = $v;
						if($newOption) {
							if(!isset($solutions[$paymentKey])) {
								$solutions[$paymentKey] = array();
							}
							$solutions[$paymentKey][] = $v;
						}
					}
				}
			}
		}
	}
	echo "<pre>";
	echo "<h3>Matched payments</h3>";
	print_r($solutions);
	echo "<h3>Unmatched payments</h3>";
	foreach($invoices as $invoice) {
	 if($invoice) {
			echo "<br />".$invoice;
		}
	}
	echo "</pre>";
}
