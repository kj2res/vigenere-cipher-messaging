<?php
/**
 * Created by PhpStorm.
 * User: macbookair
 * Date: 5/15/16
 * Time: 2:10 PM
 */

namespace App\Services;


class VigenereCipher {

    const ENCIPHER = 'encipher';
    const DECIPHER = 'decipher';

    /**
     * Container for Vigenère Square
     * @var
     */
    protected $table;


    public function __construct() {
        $this->createMap();
    }

    /**
     * The Vigenère Square
     */
    private function createMap() {

        $chars = $this->characters();

        for ($i = 0; $i < 27; $i++) {
            $this->table[$i] = $chars;

            if($i > 0){
                $shift = array_shift($chars);
                array_push($chars, $shift);
            }
        }

        for ($i = 0; $i < 27; $i++) {
            array_unshift($this->table[$i],'');
        }
    }

    /**
     * The Characters to use inside Vigenère Square
     * @return array
     */
    private function characters() {
        return range('A', 'Z');
    }

    /**
     * Remove unnecessary characters and convert to uppercase
     * to match the values in our table
     * @param $string
     * @return mixed
     */
    private function rinse($string) {
        return preg_replace('/[^A-Z]/', '', strtoupper($string));
    }

    /**
     * Maps the value in Vigenère Square
     * @param $row
     * @param $col
     * @return mixed
     */
    private function map($row, $col) {
        return  $this->table[$row][$col];
    }

    /**
     * Repeated Cipher
     * @param $keyword
     * @param $string
     * @return string
     */
    private function repeatCipher($keyword, $string) {
        $output = '';
        $output = str_pad($output,strlen($string),$keyword);
        return $output;
    }

    /**
     * Executes an action
     * @param $string
     * @param $keyword
     * @param string $type
     * @return string
     */
    private function execute($string, $keyword, $type = self::ENCIPHER) {

        $text = $this->rinse($string);
        $key = $this->rinse($keyword);
        $repeatedCipher = $this->repeatCipher($key, $text);

        $result = '';

        $cipherCol = str_split($text);
        $cipherRow = str_split($repeatedCipher);
        $length = count($cipherRow);

        for ($i = 0; $i < $length; $i++) {
            $row = $cipherRow[$i];
            $column = $cipherCol[$i];

            if($type === self::ENCIPHER) {
                $rowTarget = array_search($row, $this->table[0]);
                $colTarget = array_search($column, $this->table[1]);
                $result .= $this->map($rowTarget, $colTarget);
            }
            else {
                $rowTarget = array_search($row,$this->table[1]);
                $colTarget = array_search($column,$this->table[$rowTarget]);
                $result .= $this->map(0, $colTarget);
            }
        }

        return $result;
    }

    /**
     * Encryption Action
     * @param $string
     * @param $keyword
     * @return string
     */
    public function encrypt($string, $keyword) {
        return $this->execute($string, $keyword);
    }

    /**
     * Decryption Action
     * @param $string
     * @param $keyword
     * @return string
     */
    public function decrypt($string, $keyword) {
        return $this->execute($string, $keyword, self::DECIPHER);
    }
}