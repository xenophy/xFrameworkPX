<?php

class Docs_Tutorial_Model_Db5_sample extends xFrameworkPX_Model
{
    public function deleteData()
    {
        $this->delete(
            array(
                'bind' => array(
                    'id' => 1,
                ),
                'where' => array(
                    'id = :id'
                )
            )
        );
    }

    public function getDataAll()
    {
        return $this->rowAll(
            array(
                'query' => 'SELECT * FROM ' . $this->getTableName()
            )
        );
    }
}

