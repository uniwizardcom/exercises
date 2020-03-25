<?php
/**
 * @author Wojciech Niewiadomski
 * @email wojciech.niewiadomski@duolife.eu
 * @copyright DuoLife S.A.
 */

namespace App\Services;


class ProcessJsonData
{
    /**
     * @var string|null
     */
    private $listInPath = null;

    /**
     * @var string|null
     */
    private $treeInPath = null;

    /**
     * @var string|null
     */
    private $outputPath = null;

    /**
     * @param string|null $listInPath
     */
    public function setListInPath(?string $listInPath): void
    {
        $this->listInPath = $listInPath;
    }

    /**
     * @param string|null $treeInPath
     */
    public function setTreeInPath(?string $treeInPath): void
    {
        $this->treeInPath = $treeInPath;
    }

    /**
     * @param string|null $outputPath
     */
    public function setOutputPath(?string $outputPath): void
    {
        $this->outputPath = $outputPath;
    }

    public function run()
    {
        $tree = json_decode(trim(file_get_contents($this->treeInPath)), true);
        $list = json_decode(trim(file_get_contents($this->listInPath)), true);

        $processRecordData = new ProcessTree($tree);
        $processRecordData->setListData($list);
        $processRecordData->run();

        var_dump($processRecordData->getResultTree());
    }
}
