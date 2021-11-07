<?php

namespace App\Machine;

use App\Exception\CigaretteMachineCalculationException;

/**
 * Class CigaretteMachine
 *
 * @package App\Machine
 */
class CigaretteMachine implements MachineInterface
{
	const ITEM_PRICE = 4.99;

	const CHANGE_OPTIONS = [
		2,
		1,
		0.50,
		0.20,
		0.10,
		0.05,
		0.02,
		0.01,
	];

	/**
	 * @throws \App\Exception\CigaretteMachineCalculationException
	 */
	public function validateRequestedTransAction($quantity, $paidAmount)
	{
		$priceToPay = $this->calculatePriceToPay($quantity);

		if ($this->calculatePriceToPay($quantity) > $paidAmount) {
			throw new CigaretteMachineCalculationException("less money given ($paidAmount) than total cost ($priceToPay) of amount ($quantity)");
		}
	}

	/**
	 * @param \App\Machine\PurchaseTransactionInterface $purchaseTransaction
	 *
	 * @return \App\Machine\PurchasedItemInterface
	 * @throws \App\Exception\CigaretteMachineCalculationException
	 */
	public function execute(PurchaseTransactionInterface $purchaseTransaction): PurchasedItemInterface
	{
		$quantity   = $purchaseTransaction->getItemQuantity();
		$paidAmount = $purchaseTransaction->getPaidAmount();

		$priceToPay  = $this->calculatePriceToPay($quantity);
		$amountToBuy = $this->calculateAmountToBuy($priceToPay);
		$change      = $this->calculateChange($paidAmount, $priceToPay);

		$this->validateRequestedTransAction($quantity, $paidAmount);

		return new TransactionResult($amountToBuy, $priceToPay, $change);
	}

	/**
	 * @param int $quantity
	 *
	 * @return float|int
	 */
	protected function calculatePriceToPay(int $quantity)
	{
		return $quantity * self::ITEM_PRICE;
	}

	/**
	 * @param $priceToPay
	 *
	 * @return float|int
	 */
	protected function calculateAmountToBuy($priceToPay)
	{
		return $priceToPay / self::ITEM_PRICE;
	}

	/**
	 * @param float $paidAmount
	 * @param float $priceToPay
	 *
	 * @return array
	 */
	protected function calculateChange(float $paidAmount, float $priceToPay): array
	{
		$changeInFloat = round($paidAmount - $priceToPay, 2);
		$change        = [];

		foreach (self::CHANGE_OPTIONS as $coin) {
			$fittingCoinAmount = floor($changeInFloat / $coin);
			$coinAmount        = $fittingCoinAmount >= 1 ? (int)$fittingCoinAmount : 0;

			$changeInFloat = round($changeInFloat - $coinAmount * $coin, 2);
			$change[]      = [$coin, $fittingCoinAmount];
		}

		return $change;
	}
}
