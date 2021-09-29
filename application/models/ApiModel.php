<?php

/**
 * ApiModel
 */
class ApiModel extends CI_Model {
    public function __construct() {
    }

    /**
     * a függvény visszaadja $number egész értékű osztóit
     * 
     * @param integer $number
     * @return array tömb a $number osztóival
     */
    public function arrayOfDivisors($number) {
        $divisors = array ();
        for($i = 1; $i < $number; $i ++) {
            if ($number % $i == 0) {
                $divisors [] = $i;
            }
        }
        return $divisors;
    }

    /**
     * a függvény kódolja $number-t úgy, hogy az értéket megszorozza 10-zel és oktális számmá konvertálja
     * 
     * @param integer $number
     * @return integer
     */
    public function encodeNumber($number) {
        //itt lehetne alkalmazni MD5, vagy más, egyedi (akár visszafordíthatatlan) encode-olást is
        //most az egyszerűség kedvéért az értéket megszorozzuk 10-zel és oktális számmá konvertáljuk

        return decoct($number * 10);
    }

}