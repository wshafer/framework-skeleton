<?php

namespace AppTest\Command;

use App\Command\GreetCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class GreetCommandTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application('Application console');
        $application->add(new GreetCommand());

        $command = $application->find('demo:greet');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName()
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('Hello', $output);
    }

    public function testExecuteWithName()
    {
        $application = new Application('Application console');
        $application->add(new GreetCommand());

        $command = $application->find('demo:greet');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'Tester'
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('Hello Tester', $output);
    }

    public function testExecuteWithYell()
    {
        $application = new Application('Application console');
        $application->add(new GreetCommand());

        $command = $application->find('demo:greet');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'Tester',
            '--yell' => null
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('HELLO TESTER', $output);
    }
}