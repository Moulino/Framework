<?php

namespace Moulino\Framework\Auth\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Moulino\Framework\Auth\Exception\SaltIsEmpty;

class HashPassword extends Command
{
	private $passwordHasher;

	public function __construct($passwordHasher) {
		$this->passwordHasher = $passwordHasher;
		parent::__construct();
	}

	protected function configure() {
		$this->setName('moulino:hash-password')
			->setDescription('Hash a password with salt key.')
			->addArgument('password', InputArgument::REQUIRED, 'What is the clear password ?');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$clearPwd = $input->getArgument('password');
		try {
			$pwd = $this->passwordHasher->hash($clearPwd);
		} catch (SaltIsEmtpy $e) {
			$message = $e->getMessage();
			$output->write("<error>$e</error>", true);
		}
		$output->write("<info>Password encoded : $pwd</info>", true);
	}
}