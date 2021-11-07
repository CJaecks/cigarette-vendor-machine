<?php

namespace App\Machine;

/**
 * Class CigaretteMachine
 *
 * @package App\Machine
 */
class Transaction implements PurchaseTransactionInterface
{
	private $quantity;
	private $paidAmount;

	public function __construct($quantity = 0, $paidAmount = 0.0)
	{
		$this->quantity   = $quantity;
		$this->paidAmount = $paidAmount;
	}

	public function getItemQuantity(): int
	{
		return $this->quantity;
	}

	public function getPaidAmount(): float
	{
		return $this->paidAmount;
	}
}
