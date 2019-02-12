<?php
namespace Bundles\PayBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 逾期还款发送短信
 * Class OverdueCommand
 */
class OverdueCommand extends ContainerAwareCommand{

	protected function configure(){
		$this
			->setName('overdue:send-message')
			->setDescription('逾期还款发送短信');
	}

	protected function execute(InputInterface $input, OutputInterface $output){
		echo 2323;
	}
}
