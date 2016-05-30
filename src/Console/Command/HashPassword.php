<?php

namespace Moulino\Framework\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Moulino\Framework\Config\Config;

class HashPassword extends Command
{
	protected function configure() {
		$this->setName('moulino:hash-password')
			->setDescription('Hash a password with salt key.')
			->addArgument('password', InputArgument::REQUIRED, 'What is the clear password ?');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		Config::loadConfigFile(APP_CONFIG);

		$output->write("<comment>Load configuration file : ".APP_CONFIG."</comment>", true);

		$salt = Config::get('security.salt');
		$clearPwd = $input->getArgument('password');

		$pwd = sha1(sha1($clearPwd).$salt);

		if(count($salt) > 0) {
			$output->write("<comment>Salt key : $salt</comment>", true);
			$output->write("\n<info>Password encoded : $pwd</info>", true);
		} else {
			$output->write("<error>The salt key is empty.</error>", true);
		}

		

	}
}