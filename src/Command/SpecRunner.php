<?php

namespace Webspot\SpotSpec\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webspot\SpotSpec\Describe;

class SpecRunner extends Command
{
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Will run the specs in the given path')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to search for specs', './');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = realpath($input->getArgument('path'));
        $specs = glob($path.DIRECTORY_SEPARATOR.'*.spec.php');
        foreach ($specs as $spec) {
            $spec = require $spec;
            if (!$spec instanceof Describe) {
                throw new \RuntimeException('All spec suites must be instances of Describe.');
            }
            $success = $spec->run();
            $desc = $spec->getDescription();
            $result = $spec->getResult();

            $output->writeln([
                ($success ? 'OK  ' : 'FAIL').' when describing '.$desc,
                '-----------------------------------------------------',
            ]);

            foreach ($result as $specDesc => $specSuccess) {
                $output->writeln('['.($specSuccess ? '+' : ' ').'] It '.$specDesc);
            }
            $output->writeln('-----------------------------------------------------');
            $output->writeln('');
        }
    }
}
