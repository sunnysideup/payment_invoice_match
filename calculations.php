<?php

$overallSolutions = array();

$numberOfAttempts = 3;

$numberOfGuessesPerAttempt = 10000;

$maxDifference = 3;

for($x = 0; $x < $numberOfAttempts; $x++) {
	$invoices = array(
		474.38,
		265.65,
		611.53,
		388.14,
		661.54,
		100.05,
		431.25,
		351.03,
		7245.00,
		582.19,
		244.38,
		85.38,
		861.64,
		617.55,
		229.42,
		287.48,
		43.13,
		560.62,
		1121.27,
		1380.00,
		1207.50,
		215.63,
		366.57,
		474.38
	);

	$payments = array(
		2543.54,
		804.98,
		10153.04,
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
