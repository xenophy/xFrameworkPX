<?php

class Docs_Tutorial_Database_Db4_sample extends xFrameworkPX_Model
{
    public function updateData($title, $note)
    {
        $this->update(
            array(
                'field' => array(
                    'title',
                    'note',
                ),
                'value' => array(
                    ':title',
                    ':note',
                ),
                'bind' => array(
                    'id' => 1,
                    'title' => $title,
                    'note' => $note,
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

