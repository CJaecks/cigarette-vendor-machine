<?php

namespace App\Machine;

/**
 * Class CigaretteMachine
 *
 * @package App\Machine
 */
class TransactionResult implements PurchasedItemInterface
{
	private $quantity;
	private $change;
	private $amount;

	public function __construct($quantity = 0, $amount = 0, $change = [])
	{
		$this->quantity = $quantity;
		$this->change   = $change;
		$this->amount   = $amount;
	}

	public function getTotalAmount(): float
	{
		return $this->amount;
	}

	public function getItemQuantity(): int
	{
		return $this->quantity;
	}

	public function getChange(): array
	{
		return $this->change;
	}
}
