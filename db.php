<?php

/**
 * Baza danych
 */
class db
{
    //ustawienia
    protected $_DB_HOST = 'localhost';
    protected $_DB_USER = 'root';
    protected $_DB_PASS = '';
    protected $_DB_NAME = 'reek';
    protected $link;


    public function __construct()
    {
        $this->link = mysqli_connect($this->_DB_HOST, $this->_DB_USER, $this->_DB_PASS);
       /* if ($this->link) {
            echo 'Połączenie udane!<br>';
        }*/
    }

    //polaczenie
    public function connect()
    {
        if (!mysqli_select_db($this->link, $this->_DB_NAME)) {
            die("1st time failed<br>");
        }

        return $this->link;
    }

    //zamkniecie polaczenie z baza
    private function close()
    {
        mysqli_close($this->link);
    }

    //ostatnie id wstawione do bazy
    public function getLastId()
    {
        if ($this->link) {
            return mysqli_insert_id($this->link);
        }
    }

    //wyswietlanie tresci
    public function select($what, $from, $others = "")
    {
        $data = array();
        $this->connect();
        $query = 'SELECT ' . $what . ' from ' . $from . ' ' . $others . ' ';

        if ($this->link) {
            $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));

            while ($r = mysqli_fetch_assoc($result)) {
                $data[] = $r;
            }
            return $data;
        }
        $this->close();

    }

    //dodwanie do bazy
    public function insert($where, $values = "")
    {
        $this->connect();
        $query = "INSERT INTO $where VALUES($values)";
        //print($query);
        if ($this->link) {
            $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
        }
    }

    //usuwanie z bazy
    public function delete($from, $where)
    {
        $this->connect();
        $query = "DELETE FROM $from WHERE $where";
       // print($query);
        if ($this->link) {
            $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
        }
    }

    //edycja danych
    public function update($from, $what, $where)
    {
        $this->connect();
        //jesli what to tablica
        if (is_array($what)) {
            $query = "UPDATE $from SET ";
            foreach ($what as $r => $result) {

                $query .= " $r='$result',";

            }
            $query = substr($query, 0, strlen($query) - 1);
            $query .= " WHERE $where";

        }
        //jesli jest stringiem
        else {
            $query = "UPDATE $from SET $what WHERE $where";
            //print $query;

        }

        if ($this->link) {
            $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
        }

    }

}

?>