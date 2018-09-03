<?php
/**
 * Created by Date: 2018/9/3
 */

namespace Firma\Bundle\SwooleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ServerReloadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swoole:server:reload')
            ->setDescription('Reload Swoole HTTP Server.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $server = $this->getContainer()->get('app.swoole.server');
            if ($server->isRunning()) {
                $server->reload();
                $io->success('Swoole server reloaded!');
            } else {
                $io->warning('Server not running! Please before start the server.');
            }
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
        }
    }
}
