<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 2/12/2018
 * Time: 1:53 AM
 */

class ctest extends CI_Controller
{
    private $counter = 0;

    public function __construct() {
        parent::__construct();
        $this->load->library('unit_test');

    }

    function runTest($assert,$excepted = false, $title = false)
    {
        if(!$title){
            $this->counter++;
            $title = "#".$this->counter;
        }

        $result = "RESULTADO ES : [({ $assert })] SE ESPERABA : [({ $excepted })]";
        $run = $this->unit->run($assert,$excepted,  "TEST: $title", $result);
        echo $run;
    }
}