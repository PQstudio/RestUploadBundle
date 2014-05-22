<?php
namespace PQstudio\RestUploadBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearOldCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pq:rest-upload:clearold')
            ->setDescription('Deletes old tmp files')
            ->addOption(
                'older-than',
                null,
                InputOption::VALUE_REQUIRED,
                'Time in minutes to delete older files than this value',
                null
            )
            ->setHelp(
                <<<EOT
                    The <info>%command.name%</info>command deletes old tmp files.

<info>php %command.full_name% [--older-than=...]</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileRepository = $this->getContainer()->get('file_repository');
        $filesystem = $this->getContainer()->get('tmpfiles_filesystem');

        $oldFiles = $fileRepository->findOlderThan($input->getOption('older-than'));
        foreach($oldFiles as $oldFile) {
            $filesystem->delete($oldFile->getFilename());
        }

        $fileRepository->deleteOlderThan($input->getOption('older-than'));
        $output->writeln('Done.');
    }
}
