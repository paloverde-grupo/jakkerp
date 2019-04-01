<?php
require_once APPPATH.'controllers/ctest.php';
class setupRed extends ctest {


	public function __construct() {
		parent::__construct(); 
		$this->load->model('/bo/bonos/afiliado');
		$this->load->model('/bo/bonos/red');

	}
	
	public function index(){
		$this->Before();
		$this->testSetValoresRed();
		$this->testSetValoresRedBaseDeDatos();
		$this->after();
	}
	
	private function Before(){
		$this->afiliado->eliminarUsuarios();
		$this->red->eliminarRed();

		$red=$this->red;
		$infinito=0;
		$datosRed = array(
				'id_red' => "300",
				'nombre'   => "Binario",
				'descripcion'    => "Test de Red Binaria",
				'frontal' => 2,
				'profundidad'   => $infinito,
				'valor_punto'    => 1,
				'estatus'   => 'ACT',
				'plan' => 'BIN'
		);
		
		$red->nuevaRed($datosRed);
		$red->ingresarRed();
		
		
/*							RED DE AFILIACION PRUEBA
*           	                 __________
*           	               	|  10000   |
*        	   	               /| giovanny | \
*           	              / |_____2____|  \
*           	        _____/__             __\___
*           	       | 10001  |   	    | 10002 | 
*       	           | carlos |   	    | pedro |\
*       	           |_10000__|   	    |_10000_| \
*  	          ______ /  	   _\_____    __/_____     \_______
*  	         |10003 |   	  | 10004 | |   10005 |   	|10006 |  
* 	         |camilo|   	  |nicolas| |esperanza|   	|pedro |   
* 	         |10001_|   	  |_10001_| |__10002__|   	|_10002|   
*       ____/___    __\____         ____/__
*  	   |10007 	|  | 10008 |       |10009  |  
* 	   |fernando|  |andres |       |ricardo|   
* 	   |10003___|  |_10003_|       |_10002_|
*/
		
		
		$this->crearNuevoUsuario (10000,"giovanny","2016-03-17",20000,300,2,2,0);
		$this->crearNuevoUsuario (10001,"carlos","2016-03-17",20001,300,10000,10000,0);
		$this->crearNuevoUsuario (10002,"pedro","2016-03-17",20002,300,10000,10000,1);
		$this->crearNuevoUsuario (10003,"camilo","2016-03-17",20003,300,10001,10001,0);
		$this->crearNuevoUsuario (10004,"nicolas","2016-03-17",20004,300,10001,10001,1);
		$this->crearNuevoUsuario (10005,"esperanza","2016-03-17",20005,300,10002,10000,0);
		$this->crearNuevoUsuario (10006,"maria","2016-03-17",20006,300,30002,10002,1);
		$this->crearNuevoUsuario (10007,"fernando","2016-03-17",20007,300,10003,10003,0);
		$this->crearNuevoUsuario (10008,"andres","2016-03-17",20008,300,10003,10003,1);
		$this->crearNuevoUsuario (10009,"ricardo","2016-03-17",20009,300,10005,10002,0);
	}
	
	private function crearNuevoUsuario($id_usuario,$username,$created,$id_afiliacion,$id_red,$id_padre,$id_sponsor,$lado_red) {
		$datosUsuario = array(
				'id_usuario' => $id_usuario,
				'username'   => $username,
				'created'    => $created,
				'id_afiliacion' => $id_afiliacion,
				'id_red'   => $id_red,
				'id_padre'    => $id_padre,
				'id_sponsor'   => $id_sponsor,
				'lado_red' => $lado_red
		);
		
		$this->afiliado->nuevoAfiliado ($datosUsuario);
		$this->afiliado->ingresarUsuario ();
	}

	public function testSetValoresRed(){
		$red=$this->red;
		
		$resultado=$red->getIdRed();
		$this->runTest(300,$resultado, 'Test set Base de datos Id Red');
	
		$resultado=$red->getNombre();
		$this->runTest("Binario",$resultado, 'Test set Base de datos nombre Red');
		
		$resultado=$red->getDescripcion();
		$this->runTest("Test de Red Binaria",$resultado, 'Test set Base de datos descripcion Red');
		
		$resultado=$red->getFrontal();
		$this->runTest(2,$resultado, 'Test set Base de datos frontal red');
		
		$resultado=$red->getProfundidad();
		$this->runTest(0,$resultado, 'Test set Base de datos Profundidad red');
		
		$resultado=$red->getValorPunto();
		$this->runTest(1,$resultado, 'Test set Base de datos valor_punto red');
		
		$resultado=$red->getEstatus();
		$this->runTest('ACT',$resultado, 'Test set Base de datos estatus red');
		
		$resultado=$red->getPlan();
		$this->runTest('BIN',$resultado, 'Test set Base de datos plan red');

	}
	
	public function testSetValoresRedBaseDeDatos(){
		$red=new $this->red ();
		$red->setUpRed(300);
		
		$resultado=$red->getIdRed();
		$this->runTest(300,$resultado, 'Test set Base de datos Id Red');
		
		$resultado=$red->getNombre();
		$this->runTest("Binario",$resultado, 'Test set Base de datos nombre Red');
		
		$resultado=$red->getDescripcion();
		$this->runTest("Test de Red Binaria",$resultado, 'Test set Base de datos descripcion Red');
		
		$resultado=$red->getFrontal();
		$this->runTest(2,$resultado, 'Test set Base de datos frontal red');
		
		$resultado=$red->getProfundidad();
		$this->runTest(0,$resultado, 'Test set Base de datos Profundidad red');
		
		$resultado=$red->getValorPunto();
		$this->runTest(1,$resultado, 'Test set Base de datos valor_punto red');
		
		$resultado=$red->getEstatus();
		$this->runTest('ACT',$resultado, 'Test set Base de datos estatus red');
		
		$resultado=$red->getPlan();
		$this->runTest('BIN',$resultado, 'Test set Base de datos plan red');
		
	}

	private function after(){
		$this->afiliado->eliminarUsuarios();
		$this->red->eliminarRed();
	}
	

}
