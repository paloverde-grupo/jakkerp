<?php 
require_once APPPATH.'controllers/ctest.php';
class setupMercancia extends ctest {


	public function __construct() {
		parent::__construct();
		$this->load->model('/bo/bonos/mercancia');

	}
	
	public function index(){
		$this->Before();
		$this->testSetValoresMercancia();
		$this->testSetValoresMercanciaBaseDeDatos();
		$this->testSetValoresProductoBaseDeDatos();
		$this->testSetValoresCombinadoBaseDeDatos();
		$this->testSetValoresPaqueteDeIncripcionBaseDeDatos();
		$this->testSetValoresMembresiaBaseDeDatos();
		$this->after();
	}
	
	private function Before(){
		$this->mercancia->eliminarMercancias();
		$this->mercancia->eliminarCategorias();
		
		$datosCategoria = array(
				'id_categoria' => 250,
				'id_red'   => 300,
		);
		
		$this->mercancia->ingresarCategoria ($datosCategoria);
		
		$servicios=2;
		
		$datosMercancia = array(
				'id_mercancia' => 500,
				'id_tipo_mercancia'   => $servicios,
				'costo'    => 150,
				'puntos_comisionables' => 100,
				'id_categoria' => 250,
				'id_red' => 300
		);
		
		$this->mercancia->nuevaMercancia ($datosMercancia);
		$this->mercancia->ingresarMercancia ();

	}

	private function after(){
		$this->mercancia->eliminarMercancias();
	}


	public function testSetValoresMercancia(){
		$mercancia=$this->mercancia;
		
		$resultado=$mercancia->getIdMercancia();
		$this->runTest(500,$resultado, 'Test set Base de datos Id Mercancia');

		$resultado=$mercancia->getIdTipoMercancia();
		$this->runTest(2,$resultado, 'Test set Base de datos Id Tipo Mercancia');
		
		$resultado=$mercancia->getCosto();
		$this->runTest(150,$resultado, 'Test set Base de datos Costo');
		
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
		
	}
	
	public function testSetValoresMercanciaBaseDeDatos(){

		$mercancia=new $this->mercancia ();
		$mercancia->setUpMercancia(500);
		
		$resultado=$mercancia->getIdMercancia();
		$this->runTest(500,$resultado, 'Test set Base de datos Id Mercancia');

		$resultado=$mercancia->getSku();
		$this->runTest(500,$resultado, 'Test set Base de datos sku');
		
		
		$resultado=$mercancia->getIdTipoMercancia();
		$this->runTest(2,$resultado, 'Test set Base de datos Id Tipo Mercancia');
		
		$resultado=$mercancia->getCosto();
		$this->runTest(150,$resultado, 'Test set Base de datos Costo');
		
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
		
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
		
		$resultado=$mercancia->getIdRed();
		$this->runTest(300,$resultado, 'Test set Base de datos Puntos id red');
		
		$resultado=$mercancia->getIdCategoria();
		$this->runTest(250,$resultado, 'Test set Base de datos Puntos id categoria');
		
		
	}

	public function testSetValoresProductoBaseDeDatos(){
		$this->mercancia->eliminarMercancias();
		$this->mercancia->eliminarCategorias();
		
		$datosCategoria = array(
				'id_categoria' => 250,
				'id_red'   => 300,
		);
		
		$this->mercancia->ingresarCategoria ($datosCategoria);
		
		$producto=1;
		
		$datosMercancia = array(
				'id_mercancia' => 500,
				'id_tipo_mercancia'   => $producto,
				'costo'    => 150,
				'puntos_comisionables' => 100,
				'id_categoria' => 250,
				'id_red' => 300
		);
		
		$this->mercancia->nuevaMercancia ($datosMercancia);
		$this->mercancia->ingresarMercancia ();
		
		$mercancia=new $this->mercancia ();
		$mercancia->setUpMercancia(500);
		
		$resultado=$mercancia->getIdMercancia();
		$this->runTest(500,$resultado, 'Test set Base de datos Id Mercancia');
		
		$resultado=$mercancia->getSku();
		$this->runTest(500,$resultado, 'Test set Base de datos sku');
		
		
		$resultado=$mercancia->getIdTipoMercancia();
		$this->runTest(1,$resultado, 'Test set Base de datos Id Tipo Mercancia');
		
		$resultado=$mercancia->getCosto();
		$this->runTest(150,$resultado, 'Test set Base de datos Costo');
		
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
		
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
		
		$resultado=$mercancia->getIdRed();
		$this->runTest(300,$resultado, 'Test set Base de datos Puntos id red');
		
		$resultado=$mercancia->getIdCategoria();
		$this->runTest(250,$resultado, 'Test set Base de datos Puntos id categoria');
		
	}
	
	public function testSetValoresCombinadoBaseDeDatos(){
		$this->mercancia->eliminarMercancias();
		$this->mercancia->eliminarCategorias();
	
		$datosCategoria = array(
				'id_categoria' => 250,
				'id_red'   => 300,
		);
	
		$this->mercancia->ingresarCategoria ($datosCategoria);
	
		$combinado=3;
	
		$datosMercancia = array(
				'id_mercancia' => 500,
				'id_tipo_mercancia'   => $combinado,
				'costo'    => 150,
				'puntos_comisionables' => 100,
				'id_categoria' => 250,
				'id_red' => 300
		);
	
		$this->mercancia->nuevaMercancia ($datosMercancia);
		$this->mercancia->ingresarMercancia ();
	
		$mercancia=new $this->mercancia ();
		$mercancia->setUpMercancia(500);
	
		$resultado=$mercancia->getIdMercancia();
		$this->runTest(500,$resultado, 'Test set Base de datos Id Mercancia');
	
		$resultado=$mercancia->getSku();
		$this->runTest(500,$resultado, 'Test set Base de datos sku');
	
	
		$resultado=$mercancia->getIdTipoMercancia();
		$this->runTest(3,$resultado, 'Test set Base de datos Id Tipo Mercancia');
	
		$resultado=$mercancia->getCosto();
		$this->runTest(150,$resultado, 'Test set Base de datos Costo');
	
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
	
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
	
		$resultado=$mercancia->getIdRed();
		$this->runTest(300,$resultado, 'Test set Base de datos Puntos id red');
	
		$resultado=$mercancia->getIdCategoria();
		$this->runTest(250,$resultado, 'Test set Base de datos Puntos id categoria');
	
	}

	public function testSetValoresPaqueteDeIncripcionBaseDeDatos(){
		$this->mercancia->eliminarMercancias();
		$this->mercancia->eliminarCategorias();
	
		$datosCategoria = array(
				'id_categoria' => 250,
				'id_red'   => 300,
		);
	
		$this->mercancia->ingresarCategoria ($datosCategoria);
	
		$paquete=4;
	
		$datosMercancia = array(
				'id_mercancia' => 500,
				'id_tipo_mercancia'   => $paquete,
				'costo'    => 150,
				'puntos_comisionables' => 100,
				'id_categoria' => 250,
				'id_red' => 300
		);
	
		$this->mercancia->nuevaMercancia ($datosMercancia);
		$this->mercancia->ingresarMercancia ();
	
		$mercancia=new $this->mercancia ();
		$mercancia->setUpMercancia(500);
	
		$resultado=$mercancia->getIdMercancia();
		$this->runTest(500,$resultado, 'Test set Base de datos Id Mercancia');
	
		$resultado=$mercancia->getSku();
		$this->runTest(500,$resultado, 'Test set Base de datos sku');
	
	
		$resultado=$mercancia->getIdTipoMercancia();
		$this->runTest(4,$resultado, 'Test set Base de datos Id Tipo Mercancia');
	
		$resultado=$mercancia->getCosto();
		$this->runTest(150,$resultado, 'Test set Base de datos Costo');
	
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
	
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
	
		$resultado=$mercancia->getIdRed();
		$this->runTest(300,$resultado, 'Test set Base de datos Puntos id red');
	
		$resultado=$mercancia->getIdCategoria();
		$this->runTest(250,$resultado, 'Test set Base de datos Puntos id categoria');
	
	}
	
	public function testSetValoresMembresiaBaseDeDatos(){
		$this->mercancia->eliminarMercancias();
		$this->mercancia->eliminarCategorias();
	
		$datosCategoria = array(
				'id_categoria' => 250,
				'id_red'   => 300,
		);
	
		$this->mercancia->ingresarCategoria ($datosCategoria);
	
		$membresia=5;
	
		$datosMercancia = array(
				'id_mercancia' => 500,
				'id_tipo_mercancia'   => $membresia,
				'costo'    => 150,
				'puntos_comisionables' => 100,
				'id_categoria' => 250,
				'id_red' => 300
		);
	
		$this->mercancia->nuevaMercancia ($datosMercancia);
		$this->mercancia->ingresarMercancia ();
	
		$mercancia=new $this->mercancia ();
		$mercancia->setUpMercancia(500);
	
		$resultado=$mercancia->getIdMercancia();
		$this->runTest(500,$resultado, 'Test set Base de datos Id Mercancia');
	
		$resultado=$mercancia->getSku();
		$this->runTest(500,$resultado, 'Test set Base de datos sku');
	
	
		$resultado=$mercancia->getIdTipoMercancia();
		$this->runTest(5,$resultado, 'Test set Base de datos Id Tipo Mercancia');
	
		$resultado=$mercancia->getCosto();
		$this->runTest(150,$resultado, 'Test set Base de datos Costo');
	
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
	
		$resultado=$mercancia->getPuntosComisionables();
		$this->runTest(100,$resultado, 'Test set Base de datos Puntos Comisionables');
	
		$resultado=$mercancia->getIdRed();
		$this->runTest(300,$resultado, 'Test set Base de datos Puntos id red');
	
		$resultado=$mercancia->getIdCategoria();
		$this->runTest(250,$resultado, 'Test set Base de datos Puntos id categoria');
	
	}
}
