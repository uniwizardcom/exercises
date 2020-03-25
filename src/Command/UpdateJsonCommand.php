<?php
/**
 * @author Wojciech Niewiadomski
 * @email wojtek@uniwizard.com
 */
namespace App\Command;

use App\Services\FileException;
use App\Services\ProcessJsonData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class UpdateJsonCommand extends Command
{
    const LIST_IN = 'list_in';
    const TREE_IN = 'tree_in';
    const TREE_OUT = 'tree_out';

    /**
     * the name of the command (the part after "bin/console")
     * @var string
     */
    protected static $defaultName = 'task:json';

    /**
     * @var string|null
     */
    private $projectDir = null;

    /**
     * UpdateJsonCommand constructor.
     * @param string $path
     */
    public function __construct(string $projectDir)
    {
        parent::__construct($projectDir);
        $this->projectDir = $projectDir;
    }

    protected function configure()
    {
        $this
            ->setDescription('Task - update json')
            ->setHelp('Updating json structure')
            ->addArgument(static::LIST_IN, InputArgument::REQUIRED, 'List of data for corelating')
            ->addArgument(static::TREE_IN, InputArgument::REQUIRED, 'Tree for update')
            ->addArgument(static::TREE_OUT, InputArgument::OPTIONAL, 'Result file')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $listInputJsonFilePath = $this->getFilePathFromAttribute($input->getArgument(static::LIST_IN));
            $treeInputJsonFilePath = $this->getFilePathFromAttribute($input->getArgument(static::TREE_IN));
        } catch (FileException $e) {
            $output->writeln($e->getMessage());
            return -1;
        }

        try {
            $treeOutputJsonFilePath = $this->getFilePathFromAttribute($input->getArgument(static::TREE_OUT));
        } catch (FileException $e) {
            $treeOutputJsonFilePath = $e->getFileName();
        }
        $this->checkingFileOutput($treeOutputJsonFilePath);

        $process = new ProcessJsonData();
        $process->setListInPath($listInputJsonFilePath);
        $process->setTreeInPath($treeInputJsonFilePath);
        $process->setOutputPath($treeOutputJsonFilePath);
        $process->run();

        return 0;
    }

    /**
     * @param string|null $attributeFileName
     * @return string
     * @throws FileException
     */
    private function getFilePathFromAttribute(?string $attributeFileName): string
    {
        $equalCharPosition = strpos($attributeFileName, '=');
        $attributeFileNameValue = (
            ($equalCharPosition !== false)
                ? substr($attributeFileName, $equalCharPosition+1)
                : $attributeFileName
        );

        $fileDirPath = $this->projectDir . '/data/UpdateJsonCommand/' . $attributeFileNameValue;
        if(!is_file($fileDirPath)) {
            $fileException = new FileException();
            $fileException->setFileName($fileDirPath);
            throw $fileException;
        }

        return $fileDirPath;
    }

    /**
     * @param string|null $treeOutputJsonFilePath
     */
    private function checkingFileOutput(?string &$treeOutputJsonFilePath)
    {
        if(empty($treeOutputJsonFilePath)) {
            $treeOutputJsonFilePath = $this->projectDir . '/data/UpdateJsonCommand/' . static::TREE_OUT . '.json';
        }
        elseif(is_dir($treeOutputJsonFilePath)) {
            $treeOutputJsonFilePath .= static::TREE_OUT . '.json';
        }

        file_put_contents($treeOutputJsonFilePath, null);
    }
}
