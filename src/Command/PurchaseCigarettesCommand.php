<?php

namespace App\Command;

use App\Machine\CigaretteMachine;
use App\Machine\Transaction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function str_replace;

/**
 * Class CigaretteMachine
 *
 * @package App\Command
 */
class PurchaseCigarettesCommand extends Command
{
	/**
	 * @return void
	 */
	protected function configure()
	{
		$this->addArgument('packs', InputArgument::REQUIRED, "How many packs do you want to buy?");
		$this->addArgument('amount', InputArgument::REQUIRED, "The amount in euro.");
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return void
	 * @throws \App\Exception\CigaretteMachineCalculationException
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$itemCount = (int)$input->getArgument('packs');
		$amount    = (float)str_replace(',', '.', $input->getArgument('amount'));

		$cigaretteMachine = new CigaretteMachine();

		$transaction       = new Transaction($itemCount, $amount);
		$transActionResult = $cigaretteMachine->execute($transaction);

		$boughtAmount = $transActionResult->getItemQuantity();
		$totalAmount  = $transActionResult->getTotalAmount();
		$change       = $transActionResult->getChange();

		$price            = CigaretteMachine::ITEM_PRICE;
		$output->writeln("You bought $boughtAmount packs of cigarettes for $totalAmount, each for $price. ");
		$output->writeln('Your change is: ');

		$table = new Table($output);
		$table
			->setHeaders(['Coins', 'Count'])
			->setRows($change);
		$table->render();
	}
}
