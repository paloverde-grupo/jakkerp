<?php
require_once APPPATH.'controllers/ctest.php';
class setupRepartidorComisionBono extends ctest {

	public function __construct() {
		parent::__construct(); 
		$this->load->model('/bo/bonos/repartidor_comision_bono');


	}
	
	public function index(){
		$this->Before();
		$this->testSetHistorialComisionesBonoUsuario();
		$this->after();
		
		$this->Before();
		$this->testSetRepartirComisionesBonoUsuario();
		$this->after();
	}
	
	private function Before(){
		$this->repartidor_comision_bono->eliminarHistorialComisionBono();
	}
	
	private function after(){
		$this->repartidor_comision_bono->eliminarHistorialComisionBono();
	}
	
	public function testSetHistorialComisionesBonoUsuario(){
		$repartidorComisionBono=$this->repartidor_comision_bono;
		
		$id=900;
		$id_bono=15;
		$dia=1;
		$mes=3;
		$ano=2016;
		$fecha="2013-03-01";
		
		$repartidorComisionBono->ingresarHistorialComisionBono($id,$id_bono,$dia,$mes,$ano,$fecha);
		
		$repartidorComisionBono->setUpHistorial(900);
		
		$resultado=$repartidorComisionBono->getId();
		$this->runTest(900,$resultado, 'Test Get Repartidor de Bono ID');
		
		$resultado=$repartidorComisionBono->getIdBono();
		$this->runTest(15,$resultado, 'Test Get Repartidor de Bono ID Bono');
		
		$resultado=$repartidorComisionBono->getDia();
		$this->runTest(1,$resultado, 'Test Get Repartidor de Bono Dia');
		
		$resultado=$repartidorComisionBono->getMes();
		$this->runTest(3,$resultado, 'Test Get Repartidor de Bono Mes');
		
		$resultado=$repartidorComisionBono->getAno();
		$this->runTest(2016,$resultado, 'Test Get Repartidor de Bono Ano');
		
		$resultado=$repartidorComisionBono->getFecha();
		$this->runTest("2013-03-01",$resultado, 'Test Get Repartidor de Bono Fecha');
		
	}
	
	public function testSetRepartirComisionesBonoUsuario(){
	
		$repartidorComisionBono=$this->repartidor_comision_bono;
		
		$id=900;
		$id_bono=15;
		$dia=1;
		$mes=3;
		$ano=2016;
		$fecha="2013-03-01";
		
		$repartidorComisionBono->ingresarHistorialComisionBono($id,$id_bono,$dia,$mes,$ano,$fecha);
		$repartidorComisionBono->setUpHistorial(900);
		
		$id_transaccion=200;
		$id_usuario=10000;
		$id_bono=$repartidorComisionBono->getIdBono();
		$id_bono_historial=$repartidorComisionBono->getId();
		$valor=36.5;
		
		$repartidorComisionBono->repartirComisionBono($id_transaccion,$id_usuario,$id_bono,$id_bono_historial,$valor);
		$repartidorComisionBono->setUpReparticionComision(200);

		$resultado=$repartidorComisionBono->getIdTransaccion();
		$this->runTest(200,$resultado, 'Test Get Repartidor de Bono ID Transaccion');
		
		$resultado=$repartidorComisionBono->getIdUsuario();
		$this->runTest(10000,$resultado, 'Test Get Repartidor de Bono ID Usuario');
		
		$resultado=$repartidorComisionBono->getValor();
		$this->runTest(36.5,$resultado, 'Test Get Repartidor de Bono Valor');

		
		$id_transaccion=201;
		$id_usuario=10001;
		$id_bono=$repartidorComisionBono->getIdBono();
		$id_bono_historial=$repartidorComisionBono->getId();
		$valor=40.96;
		
		$repartidorComisionBono->repartirComisionBono($id_transaccion,$id_usuario,$id_bono,$id_bono_historial,$valor);
		$repartidorComisionBono->setUpReparticionComision(201);
		
		$resultado=$repartidorComisionBono->getIdTransaccion();
		$this->runTest(201,$resultado, 'Test Get Repartidor de Bono ID Transaccion');
		
		$resultado=$repartidorComisionBono->getIdUsuario();
		$this->runTest(10001,$resultado, 'Test Get Repartidor de Bono ID Usuario');
		
		$resultado=$repartidorComisionBono->getValor();
		$this->runTest(40.96,$resultado, 'Test Get Repartidor de Bono Valor');
		
		$id_transaccion=202;
		$id_usuario=10002;
		$id_bono=$repartidorComisionBono->getIdBono();
		$id_bono_historial=$repartidorComisionBono->getId();
		$valor=0.10;
		
		$repartidorComisionBono->repartirComisionBono($id_transaccion,$id_usuario,$id_bono,$id_bono_historial,$valor);
		$repartidorComisionBono->setUpReparticionComision(202);
		
		$resultado=$repartidorComisionBono->getIdTransaccion();
		$this->runTest(202,$resultado, 'Test Get Repartidor de Bono ID Transaccion');
		
		$resultado=$repartidorComisionBono->getIdUsuario();
		$this->runTest(10002,$resultado, 'Test Get Repartidor de Bono ID Usuario');
		
		$resultado=$repartidorComisionBono->getValor();
		$this->runTest(0.10,$resultado, 'Test Get Repartidor de Bono Valor');
		
		
	}
}
