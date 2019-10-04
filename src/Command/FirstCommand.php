<?php
namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;


class FirstCommand extends ContainerAwareCommand
{

	const S_NAME = 'firs_command';

	const S_DESCRIPTION = "useless test 3";

	protected function configure()
	{
		$this->setName('app:' . self::S_NAME)
			->setDescription(self::S_DESCRIPTION)
			->setHelp("no help available")
			->addOption('silent', null, InputOption::VALUE_NONE, 'Output less text.');
	}

	protected function putStatusFile($infoLog)
	{

		if ($infoLog) {
			$statusFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . 	 DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::S_NAME . '_status.txt';
			file_put_contents($statusFile, $infoLog);
		}
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->write(self::S_NAME);
		$output->write('...');
		$output->writeln('Started.');
		

		$output->writeln('OK.');

	}
}