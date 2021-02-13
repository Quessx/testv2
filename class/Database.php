<?php

class Database
{
    private $database = "database";
    private $db = null;
    private $xml = null;
    private $table = null;
    private $column = null;
    private $value = null;

    public $where = null;

    public function __construct()
    {
        //Путь к БД
        $this->db = dirname(__FILE__) . "/data/" . $this->database . ".xml";

        //Проверка на сущ. такого файла
        if (!file_exists($this->db)) {
            //создание папки с бд
            mkdir(dirname(__FILE__) . "/data/");
            //Создаем файл и записываем database как дочерний элемент
            file_put_contents($this->db, '<?xml version="1.0" encoding="utf-8"?><database></database>');
        }

        try {
            //подключаем SimpleXMLElement для работы с xml
            $this->xml = new SimpleXMLElement(file_get_contents($this->db));
        } catch (Exception $e) {
            //вывод ошибок
            exit("Error: " . $e->getMessage());
        }

    }

    //Добавление таблицы
    public function addTable($name)
    {
        //ищем имя
        $table = $this->xml->xpath($name);

        //создаем таблицу
        if (empty($table)) {
            $this->xml->addChild($name)->addChild("row");
            return $this->save();
        }
    }

    public function from($table)
    {
        $this->table = trim($table);

        return $this;
    }

    //Сохранение
    private function save()
    {
        return $this->xml->asXML($this->db);
    }

    //получение таблицы в виде массива
    public function getList()
    {
        $xmlstring = file_get_contents($this->db);
        $xmlLoad = simplexml_load_string($xmlstring);
        $json = json_encode($xmlLoad);
        $array = json_decode($json, true);
        return $array[$this->table];
    }


    //где ищем
    public function where($column, $value)
    {

        $row = $this->from($this->table)->getList()['row'];

        $this->column[] = $column;
        $this->value[] = $value;

        $this->column = array_unique($this->column);
        $this->value = array_unique($this->value);

        foreach ($row as $key => $val) {
            if (!isset($val[$this->column[1]])) {

                if ($val[$this->column[0]] == $this->value[0]) {
                    $this->where = $val;
                    return $this;

                }

            }

            if (isset($val[$this->column[1]])) {

                if ($val[$this->column[0]] == $this->value[0] && $val[$this->column[1]] == $this->value[1]) {
                    $this->where = $val;
                    return $this;

                }

            }

        }
    }

    public function insert($name, $value)
    {

        $rows = $this->xml->xpath('//database/' . $this->table . "/row");

        $check = $this->where;

        if (!is_null($check)) {

            $res1 = array_key_exists($name, $this->getList()['row'][$check['id'] - 1]);

            if (!$res1) {
                $rows[$check['id'] - 1]->addChild($name, $value);
                $this->save();
                return;
            }
        }

        $res = array_key_exists($name, $this->getList()['row'][$this->lastId() - 1]);

        if ($name == "id") {
            $this->addRow()->addChild($name, $this->lastId() + 1);
            $this->save();
            return;
        } else if (!$res) {
            $rows[$this->lastId() - 1]->addChild($name, $value);
            $this->save();
            return;
        }


    }

    public function update($column, $value)
    {

        $columns = $this->column[0];

        foreach ($this->xml->xpath('//database/' . $this->table . '/row') as $key => $row) {

            if ($row->$columns == $this->where[$columns]) {
                $res = $row;
                $res->$column = $value;
            }
        }

        return $this->save();

    }

    public function delete($val = null)
    {

        $columns = $this->column[0];

        if (is_null($val)) {

            foreach ($this->xml->xpath('//database/' . $this->table . '/row') as $key => $row) {
                if ($row->$columns == $this->where[$columns]) {
                    $res = $row->$columns;
                    $node = dom_import_simplexml($res);
                    $node->parentNode->removeChild($node);

                }
            }
        } else if ($val === "*") {
            foreach ($this->xml->xpath('//database/' . $this->table . '/row') as $key => $row) {
                if ($row->$columns == $this->where[$columns]) {
                    $node = dom_import_simplexml($row);
                    $node->parentNode->removeChild($node);
                }
            }
        } else {
            die();
        }

        return $this->save();
    }

    private function lastId()
    {
        $rows = $this->xml->xpath('//database/' . $this->table . '/row/id');

        return count($rows);
    }

    private function addRow()
    {
        $table = $this->xml->xpath('//database/' . $this->table);
        return $table[0]->addChild('row');
    }

}