<?php

/*

Zadanie: Uzupełnić ciała metod o brakujący kod php, tak aby otrzymany wynik pokrywał się z zawartością pliku wynik.txt.
Czynności jakie trzeba podjąć:
- analiza bazy danych
- analiza kodu instancji obiektu klasy Zoo
- analiza pliku wynikowego
- napisanie kodu

Wymagania:
- umiejętność analitycznego myślenia
- samozaparcie
- znajomość php 5.x
- znajomość bazy danych
- pisanie zapytań sql

*/

class Zoo
{

    protected $plec;
    public static $plci = array(
        0 => 'nieokreslona',
        1 => 'samiec',
        2 => 'samica'
    );

    /**
     *    Ustawia zmienną $plec na 0 - nieokreślona.
     */

    public function __construct()
    {
        require_once('db.php');
        $this->plec = 0;
    }


    /**
     *  Sprawdza, czy nazwa jest ciągiem znakowym niezerowym; jeśli nie - zwróć false.
     * Następnie dodaje do bazy nazwę wraz z wcześniej ustawioną płcią.
     * @param string nazwa
     * @return boolen | integer w przypadku dodania do bazy zwracany jest id
     */

    public function dodajZwierze($nazwa)
    {
        if (is_string($nazwa) || $nazwa > 0) {
            $db = new db();
            $db->insert("moje_zoo (id, nazwa, plec)", "   '','" . $nazwa . "','.$this->plec.'");
            return $db->getLastId();
            //echo 'Dodano zwierze!';
        } else {
            return false;
        }
    }

    /**
     *    Usuwa wskazany rekord w bazie.
     * @param integer niezerowa liczba naturalna
     * @return boolean
     */

    public function usunZwierze($indentyfikator)
    {
        if ($indentyfikator > 0) {
            $db = new db();
            $db->delete('moje_zoo', 'id=' . $indentyfikator . '');
        }
    }

    /**
     *    Zmienia nazwę wybranego rekordu w bazie.
     * @param integer $indentyfikator niezerowa liczba naturalna
     * @param string $nazwa niezerowy ciąg znaków
     * @return boolean
     */

    public function edytujNazweZwierzecia($indentyfikator, $nazwa)
    {
        if ($indentyfikator > 0 || $nazwa > 0) {
            $db = new db();
            $db->update('moje_zoo', "nazwa = '" . $nazwa . "'", 'id=' . $indentyfikator . '');
        }
    }

    /**
     *    Pobiera wszystkie rekordy z bazy. Rekordy zwrócone posiadają odpowiednią płeć(jako ciąg znaków).
     * @return array()
     */

    public function pobierzWszystkieZwierzeta()
    {
        $db = new db();
        #nie moge pobierac nazw plci bo sie nie bedzie pokrywac z wynikiem
        $results = $db->select('*', 'moje_zoo');
        print("<pre>");
        return $results;
        print("</pre>");
    }

    /**
     *    Sprawdza, czy argument funkcji zawiera się w zbiorze płci. Jeśli tak, to ustawia
     * zmienną wewnętrzną $plec i zwraca true. Jeśli nie - zwraca false.
     * @param int $plec
     * @return boolean
     */

    public function ustawPlec($plec)
    {
        $count = count(self::$plci);

        if (isset($plec) && $plec <= $count) {
            while (list($key) = each(self::$plci)) {
                if ($plec == $key) {
                    return $this->plec = $plec;
                }
            }
        } else {
            return false;
        }
    }


}

$mojeZoo = new Zoo();
$mojeZoo->ustawPlec(1);
$indentyfikatorLwa = $mojeZoo->dodajZwierze('lew');
$indentyfikatorTygrysa = $mojeZoo->dodajZwierze('tygrys');
$indentyfikatorStrusia = $mojeZoo->dodajZwierze('struś');
$indentyfikatorWilka = $mojeZoo->dodajZwierze('wilk');
$mojeZoo->ustawPlec(2);
$indentyfikatorKaczki = $mojeZoo->dodajZwierze('kaczka');
$mojeZoo->edytujNazweZwierzecia($indentyfikatorKaczki, 'dziobak');
$mojeZoo->usunZwierze($indentyfikatorWilka);

print_r($mojeZoo->pobierzWszystkieZwierzeta());

?>