<?php
/**
 * Api controller
 */
class Api extends CI_Controller {

    /**
	 * Létrehoz egy matematikai feladatot, ahol:
     * - az eredmény max. 100 lehet;
     * - a matematikai művelet összeadás vagy szorzás lehet
     * 
     * a függvény visszaadja a feladatot és egy, a feladat eredményének azonosítását lehetővé tevő egyedi azonosítót
     * 
	 * @return json
	 */
    public function getTask() {
        $return = array();

        //eredmény eldöntése
        $solution = rand(1, 100);

        //művelet eldöntése
        if (rand(0, 1)) {
            //összeadás

            $operator = '+';
            //az osztók közül véletlenszerűen kiválasztunk egyet
            $item1 = rand(1, $solution-1);
            //az művelet egyik tényezőjéből kiszámoljuk a másikat
            $item2 = $solution - $item1;

        } else {
            //szorzás

            $operator = '*';
            //eredmény osztói
            //(ezen a ponton kiszűrhetnénk a prímszámokat, hogy a rutin ne adjon vissza 1-es szám szorzatát feladatul)
            $dividers = $this->ApiModel->arrayOfDivisors($solution);

            //az osztók közül véletlenszerűen kiválasztunk egyet
            $item1 = $dividers[rand(0, count($dividers)-1)];
            //az művelet egyik tényezőjéből kiszámoljuk a másikat
            $item2 = $solution / $item1;

        }

        $return['task'] = $item1 . ' ' . $operator . ' ' . $item2;

        //a műveleti eredményből létrehozzuk a kódolt egyedi azonosítót
        $return['taskId'] = $this->ApiModel->encodeNumber($solution);

        //JSON-ban visszaadjuk az eredményt
        header('Content-Type: application/json');
        echo json_encode($return);

    }

    /**
     * Függvény, mely ellenőrzi a feladott eredményt annak egyedi azonosítója alapján
     * Ha a megoldás egyezik a kódolt azonosítóval:
     * - akkor 'correct' értékkel tér vissza
     * - különben 'fail' értékkel tér vissza
     * ha a várt paramétereket JSON-ban nem kapta meg, akkor 'error' értékkel tér vissza
     * 
     * @param json {"taskId":integer,"solution":integer}
     * @return json
     */
    public function checkTask() {
        $return = array();

        $request = json_decode($this->security->xss_clean($this->input->raw_input_stream));

        if (isset($request->solution) and isset($request->taskId)) {

            if ($this->ApiModel->encodeNumber($request->solution) == $request->taskId) {
                //az eredmény helyes
                $return['result'] = 'correct';
            } else {
                //az eredmény hibás
                $return['result'] = 'fail';
            }

        } else {

            $return['result'] = 'error';

        }

        //JSON-ban visszaadjuk az eredményt
        header('Content-Type: application/json');
        echo json_encode($return);

    }

    /**
     * Függvény, mely ellenőrzi a feladott eredményt annak egyedi azonosítója alapján, mely paramétereket GET-ként is megkaphat
     * Ha a megoldás egyezik a kódolt azonosítóval:
     * - akkor 'correct' értékkel tér vissza
     * - különben 'fail' értékkel tér vissza
     * 
     * @param integer $taskId
     * @param integer $solution
     * @return json
     */
    public function checkTaskByGET($taskId = null, $solution = null) {
        $return = array();

        if ($this->ApiModel->encodeNumber($solution) == $taskId) {

            //az eredmény helyes
            $return['result'] = 'correct';

        } else {

            //az eredmény hibás
            $return['result'] = 'fail';

        }

        //JSON-ban visszaadjuk az eredményt
        header('Content-Type: application/json');
        echo json_encode($return);
    }

}
