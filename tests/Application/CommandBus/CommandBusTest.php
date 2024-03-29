<?php declare(strict_types=1);

namespace App\Tests\Application\CommandBus;

use App\Application\CommandBus\CommandBus;
use App\Application\CommandBus\CommandHandlerMap;
use App\Application\CommandBus\CommandHandlerNotFound;
use App\Application\CommandBus\MissingInvokeOnCommandHandler;
use App\Tests\Application\CommandBus\Stub\FakeCommand;
use App\Tests\Application\CommandBus\Stub\FakeCommandHandler;
use App\Tests\Application\CommandBus\Stub\InvalidFakeCommand;
use App\Tests\Application\CommandBus\Stub\InvalidFakeCommandHandler;
use PHPUnit\Framework\TestCase;

final class CommandBusTest extends TestCase
{
    public function test it cannot execute a command without a known handler()
    {
        $command = new FakeCommand();
        $handler = new FakeCommandHandler();
        $commandHandlerMap = new CommandHandlerMap([\get_class($command) => $handler]);
        $commandBus = new CommandBus($commandHandlerMap);

        $this->expectException(CommandHandlerNotFound::class);

        $commandBus->execute($commandHandlerMap);
    }

    public function test it cannot execute a command that have an handler without __invoke method()
    {
        $command = new InvalidFakeCommand();
        $handler = new InvalidFakeCommandHandler();
        $commandHandlerMap = new CommandHandlerMap([\get_class($command) => $handler]);
        $commandBus = new CommandBus($commandHandlerMap);

        $this->expectException(MissingInvokeOnCommandHandler::class);

        $commandBus->execute($command);
    }

    public function test it execute a command()
    {
        $command = new FakeCommand();
        $handler = new FakeCommandHandler();
        $commandHandlerMap = new CommandHandlerMap([\get_class($command) => $handler]);
        $commandBus = new CommandBus($commandHandlerMap);

        $this->assertSame(0, $command->property);

        $commandBus->execute($command);

        $this->assertSame(1, $command->property);
    }
}
