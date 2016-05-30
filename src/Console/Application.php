<?php

namespace Moulino\Framework\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Moulino\Framework\Console\Command as MoulinoCommand;

class Application extends BaseApplication
{
	function __construct($version = '0.1.0') {
        $title = 
"                    _ _               ___                                            _    \n".
"  /\/\   ___  _   _| (_)_ __   ___   / __\ __ __ _ _ __ ___   _____      _____  _ __| | __\n".
" /    \ / _ \| | | | | | '_ \ / _ \ / _\| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /\n".
"/ /\/\ \ (_) | |_| | | | | | | (_) / /  | | | (_| | | | | | |  __/\ V  V / (_) | |  |   < \n".
"\/    \/\___/ \__,_|_|_|_| |_|\___/\/   |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\\\n\n";

		parent::__construct($title, $version);

        if(file_exists(VENDOR.DS."robmorgan/phinx")) {

    		$init = new \Phinx\Console\Command\Init();
    		$create = new \Phinx\Console\Command\Create();
            $migrate = new \Phinx\Console\Command\Migrate();
            $rollback = new \Phinx\Console\Command\Rollback();
            $status = new \Phinx\Console\Command\Status();
            $test = new \Phinx\Console\Command\Test();
            $seedCreate = new \Phinx\Console\Command\SeedCreate();
            $seedRun = new \Phinx\Console\Command\SeedRun();

    		$this->addCommands(array(
                $init->setName('phinx:init'),
                $create->setName('phinx:create'),
                $migrate->setName('phinx:migrate'),
                $rollback->setName('phinx:rollback'),
                $status->setName('phinx:status'),
                $test->setName('phinx:test'),
                $seedCreate->setName('phinx:seed-create'),
                $seedRun->setName('phinx:seed-run')
            ));
        }

        $this->add(new MoulinoCommand\HashPassword());
	}
}