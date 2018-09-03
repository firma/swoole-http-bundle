<?php
/**
 * Created by Date: 2018/9/3
 */

namespace Firma\Bundle\SwooleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ServerStatusCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swoole:server:status')
            ->setDescription('Status Swoole HTTP Server.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $server = $this->getContainer()->get('app.swoole.server');
            $io->table(
                ['Host', 'Port', 'Status'],
                [[$server->getHost(), $server->getPort(), $server->isRunning() ? 'Running' : 'Stopped']]
            );
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
        }
    }
}
