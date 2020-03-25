<?php
/**
 * @author Wojciech Niewiadomski
 * @email wojciech.niewiadomski@duolife.eu
 * @copyright DuoLife S.A.
 */

namespace App\Services;


class ProcessTree
{
    const RECORD_CHILDREN = 'children';
    const RECORD_ID = 'id';
    const RECORD_CATEGORY_ID = 'category_id';
    const RECORD_TRANSLATIONS = 'translations';
    const RECORD_NAME = 'name';

    /**
     * @var array|null
     */
    private $tree = null;

    /**
     * @var array
     */
    private $listData = [];

    /**
     * ProcessRecordData constructor.
     * @param array $tree
     */
    public function __construct(array $tree)
    {
        $this->tree = $tree;
    }

    /**
     * @param array|null $recordData
     */
    public function setListData($listData): void
    {
        $this->listData = $listData;
    }

    public function run()
    {
        foreach($this->tree as &$branch) {
            $this->processBranch($branch);
        }
    }

    /**
     * @return array
     */
    public function getResultTree(): array
    {
        return $this->tree;
    }

    /**
     * @param $branch
     */
    protected function processBranch(&$branchData)
    {
        if(!isset($branchData[static::RECORD_ID])) {
            return;
        }

        $findedData = $this->getDataByCategoryId((int)$branchData[static::RECORD_ID]);
        if($findedData) {
            $branchData[static::RECORD_NAME] = $this->getNamesFromTranslations($findedData);
        }

        if(
            isset($branchData[static::RECORD_CHILDREN])
            && is_array($branchData[static::RECORD_CHILDREN])
            && !empty($branchData[static::RECORD_CHILDREN])
        ) {
            $processRecordData = new self($branchData[static::RECORD_CHILDREN]);
            $processRecordData->setListData($this->listData);
            $processRecordData->run();

            $branchData[static::RECORD_CHILDREN] = $processRecordData->getResultTree();
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    protected function getDataByCategoryId(int $id): ?array
    {
        foreach($this->listData as $data) {
            if(isset($data[static::RECORD_CATEGORY_ID]) && ((int)$data[static::RECORD_CATEGORY_ID] === $id)) {
                return $data;
            }
        }

        return null;
    }

    /**
     * @param $data
     * @return array
     */
    private function getNamesFromTranslations($data): array
    {
        $results = [];
        if(isset($data[static::RECORD_TRANSLATIONS]) && is_array($data[static::RECORD_TRANSLATIONS])) {
            foreach($data[static::RECORD_TRANSLATIONS] as $key => $trans) {
                $name = (isset($trans[static::RECORD_NAME]) ? trim($trans[static::RECORD_NAME]) : null);
                if(!empty($name)) {
                    $results[$key] = $name;
                }
            }
        }

        return $results;
    }
}
