<?php
require_once APPPATH . 'controllers/ctest.php';

class setupBonoDatos extends ctest
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('/bo/bonos/modelo_bono');
        $this->load->model('/bo/bonos/bono');

    }

    public function index()
    {
        $this->Before();
        $this->after();
    }

    private function Before()
    {

        $puntosComisionables = 4;
        $infinito = 0;
        $servicios = 2;
        $id_mercancia = 145;

        $datosRango = array(
            'id_rango' => 60,
            'nombre_rango' => "Bluetooth",
            'descripcion_rango' => "Bluetooth",
            'id_tipo_rango' => $puntosComisionables,
            'valor' => 110,
            'condicion_red' => "RED",
            'nivel_red' => $infinito,
            'id_condicion' => 1,
            'id_red' => 26,
            'condicion_red_afilacion' => "EQU",
            'condicion1' => $servicios,
            'condicion2' => $id_mercancia,
            'estatus_rango' => "ACT"
        );

        $inicioAfiliacion = 0;
        $fechaActual = 0;

        $datosBono = array(
            'id_bono' => 50,
            'nombre_bono' => "Bono Bluetooth",
            'descripcion_bono' => "Bono Bluetooth",
            'plan' => "NO",
            'inicio' => '2016-03-01',
            'fin' => '2026-03-01',
            'frecuencia' => "MES",
            'mes_desde_afiliacion' => $inicioAfiliacion,
            'mes_desde_activacion' => $fechaActual,
            'estatus_bono' => 'ACT'
        );


        $datosValoresBono = array();

        $datosValoresBonoAfiliado = array(
            'id_valor' => 4,
            'id_rango' => 50,
            'nivel' => 0,
            'condicion_red' => "RED",
            'verticalidad' => "ASC",
            'valor' => 0
        );

        $datosValoresBonoPadreAfiliado = array(
            'id_valor' => 3,
            'id_rango' => 50,
            'nivel' => 1,
            'condicion_red' => "DIRECTOS",
            'verticalidad' => "ASC",
            'valor' => 1.6
        );

        $datosValoresBonoAbueloAfiliado = array(
            'id_valor' => 2,
            'id_rango' => 50,
            'nivel' => 2,
            'condicion_red' => "RED",
            'verticalidad' => "ASC",
            'valor' => 0.8
        );

        $datosValoresBonoBisAbueloAfiliado = array(
            'id_valor' => 1,
            'id_rango' => 50,
            'nivel' => 3,
            'condicion_red' => "DIRECTOS",
            'verticalidad' => "ASC",
            'valor' => 0.8
        );

        array_push($datosValoresBono, $datosValoresBonoAfiliado, $datosValoresBonoPadreAfiliado, $datosValoresBonoAbueloAfiliado, $datosValoresBonoBisAbueloAfiliado);

        $this->modelo_bono->nuevoBono($datosRango, $datosBono, $datosValoresBono);
        $this->modelo_bono->limpiarBono();
        $this->modelo_bono->ingresarBono();
        $this->testSetValoresBonoBaseDeDatos();
        $this->testSetValoresCondicionesBonoBaseDeDatos();
        $this->testSetValoresValorBonoBaseDeDatos();
        $this->testSetValoresActivacionBonoBaseDeDatos();
        $this->testSetBono();
    }

    private function after()
    {
        $this->modelo_bono->limpiarBono();
    }


    public function testSetValoresBonoBaseDeDatos()
    {
        $bono = $this->bono;

        $this->bono->setDatosBono(50);

        $resultado = $bono->getId();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getNombre();
        $this->runTest("Bono Bluetooth", $resultado, 'Test set Base de datos Nombre Bono');

        $resultado = $bono->getDescripcion();
        $this->runTest("Bono Bluetooth", $resultado, 'Test set Base de datos Descripcion Bono');

        $resultado = $bono->getTipoBono();
        $this->runTest("NO", $resultado, 'Test set Base de datos Plan Bono');

    }

    public function testSetValoresCondicionesBonoBaseDeDatos()
    {
        $bono = $this->bono;

        $this->bono->setDatosCondicionesBono(50);

        $resultado = $bono->getCondicionesBono()->getIdCondicion();
        $this->runTest(1, $resultado, 'Test set Base de datos Condiciones ID Condicion');

        $resultado = $bono->getCondicionesBono()->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Condiciones ID Bono');

        $resultado = $bono->getCondicionesBono()->getIdRango();
        $this->runTest(60, $resultado, 'Test set Base de datos Condiciones ID Rango');

        $resultado = $bono->getCondicionesBono()->getIdTipoRango();
        $this->runTest(4, $resultado, 'Test set Base de datos Condiciones ID Tipo Rango');

        $resultado = $bono->getCondicionesBono()->getIdRed();
        $this->runTest(26, $resultado, 'Test set Base de datos Condiciones ID Red');

        $resultado = $bono->getCondicionesBono()->getCondicionRed();
        $this->runTest("RED", $resultado, 'Test set Base de datos Condiciones Condicion Red');

        $resultado = $bono->getCondicionesBono()->getNivelRed();
        $this->runTest(0, $resultado, 'Test set Base de datos Condiciones Nivel Red');

        $resultado = $bono->getCondicionesBono()->getValor();
        $this->runTest(110, $resultado, 'Test set Base de datos Condiciones Valor');

        $resultado = $bono->getCondicionesBono()->getCondicionBono1();
        $this->runTest(2, $resultado, 'Test set Base de datos Condicion1');

        $resultado = $bono->getCondicionesBono()->getCondicionBono2();
        $this->runTest(145, $resultado, 'Test set Base de datos Condicion2');

    }

    public function testSetValoresValorBonoBaseDeDatos()
    {
        $bono = $this->bono;

        $this->bono->setDatosValorBono(50);

        // Nivel 3
        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getId();
        $this->runTest(4, $resultado, 'Test set Base de datos Id Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getCondicionRed();
        $this->runTest("RED", $resultado, 'Test set Base de datos Condicion Red');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getNivel();
        $this->runTest(0, $resultado, 'Test set Base de datos Nivel');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getValor();
        $this->runTest(0, $resultado, 'Test set Base de datos Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getVerticalidad();
        $this->runTest("ASC", $resultado, 'Test set Base de datos Verticalidad');

        // Nivel 2
        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getId();
        $this->runTest(3, $resultado, 'Test set Base de datos Id Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getCondicionRed();
        $this->runTest("DIRECTOS", $resultado, 'Test set Base de datos Condicion Red');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getNivel();
        $this->runTest(1, $resultado, 'Test set Base de datos Nivel');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getValor();
        $this->runTest(1.6, $resultado, 'Test set Base de datos Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getVerticalidad();
        $this->runTest("ASC", $resultado, 'Test set Base de datos Verticalidad');


        // Nivel 1
        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getId();
        $this->runTest(2, $resultado, 'Test set Base de datos Id Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getCondicionRed();
        $this->runTest("RED", $resultado, 'Test set Base de datos Condicion Red');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getNivel();
        $this->runTest(2, $resultado, 'Test set Base de datos Nivel');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getValor();
        $this->runTest(0.8, $resultado, 'Test set Base de datos Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getVerticalidad();
        $this->runTest("ASC", $resultado, 'Test set Base de datos Verticalidad');

        // Nivel 0
        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getId();
        $this->runTest(1, $resultado, 'Test set Base de datos Id Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getCondicionRed();
        $this->runTest("DIRECTOS", $resultado, 'Test set Base de datos Condicion Red');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getNivel();
        $this->runTest(3, $resultado, 'Test set Base de datos Nivel');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getValor();
        $this->runTest(0.8, $resultado, 'Test set Base de datos Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getVerticalidad();
        $this->runTest("ASC", $resultado, 'Test set Base de datos Verticalidad');

    }

    public function testSetValoresActivacionBonoBaseDeDatos()
    {
        $bono = $this->bono;

        $this->bono->setDatosActivacionBono(50);

        $resultado = $bono->getActivacionBono()->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getActivacionBono()->getInicio();
        $this->runTest("2016-03-01", $resultado, 'Test set Base de datos Inicio');

        $resultado = $bono->getActivacionBono()->getFin();
        $this->runTest("2026-03-01", $resultado, 'Test set Base de datos Fin');

        $resultado = $bono->getActivacionBono()->getMesDesdeAfiliacionAfiliado();
        $this->runTest(0, $resultado, 'Test set Base de datos Mes desde afiliacion');

        $resultado = $bono->getActivacionBono()->getMesDesdeActivacionAfiliado();
        $this->runTest(0, $resultado, 'Test set Base de datos Mes desde Activacion');

        $resultado = $bono->getActivacionBono()->getFrecuencia();
        $this->runTest('MES', $resultado, 'Test set Base de datos Frecuencia');

        $resultado = $bono->getActivacionBono()->getEstado();
        $this->runTest('ACT', $resultado, 'Test set Base de datos Frecuencia');


    }

    public function testSetBono()
    {

        $bono = new $this->bono ();
        $bono->setUpBono(50);

        $resultado = $bono->getId();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getNombre();
        $this->runTest("Bono Bluetooth", $resultado, 'Test set Base de datos Nombre Bono');

        $resultado = $bono->getDescripcion();
        $this->runTest("Bono Bluetooth", $resultado, 'Test set Base de datos Descripcion Bono');

        $resultado = $bono->getTipoBono();
        $this->runTest("NO", $resultado, 'Test set Base de datos Plan Bono');


        $resultado = $bono->getCondicionesBono()->getIdCondicion();
        $this->runTest(1, $resultado, 'Test set Base de datos Condiciones ID Condicion');

        $resultado = $bono->getCondicionesBono()->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Condiciones ID Bono');

        $resultado = $bono->getCondicionesBono()->getIdRango();
        $this->runTest(60, $resultado, 'Test set Base de datos Condiciones ID Rango');

        $resultado = $bono->getCondicionesBono()->getIdTipoRango();
        $this->runTest(4, $resultado, 'Test set Base de datos Condiciones ID Tipo Rango');

        $resultado = $bono->getCondicionesBono()->getIdRed();
        $this->runTest(26, $resultado, 'Test set Base de datos Condiciones ID Red');

        $resultado = $bono->getCondicionesBono()->getCondicionRed();
        $this->runTest("RED", $resultado, 'Test set Base de datos Condiciones Condicion Red');

        $resultado = $bono->getCondicionesBono()->getNivelRed();
        $this->runTest(0, $resultado, 'Test set Base de datos Condiciones Nivel Red');

        $resultado = $bono->getCondicionesBono()->getValor();
        $this->runTest(110, $resultado, 'Test set Base de datos Condiciones Valor');


        $resultado = $bono->getCondicionesBono()->getCondicionAfiliadosRed();
        $this->runTest('EQU', $resultado, 'Test set Base de datos Condicion Red Afiliacion');


        $resultado = $bono->getCondicionesBono()->getCondicionBono1();
        $this->runTest(2, $resultado, 'Test set Base de datos Condicion1');

        $resultado = $bono->getCondicionesBono()->getCondicionBono2();
        $this->runTest(145, $resultado, 'Test set Base de datos Condicion2');


        // Nivel 3
        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getId();
        $this->runTest(4, $resultado, 'Test set Base de datos Id Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getCondicionRed();
        $this->runTest("RED", $resultado, 'Test set Base de datos Condicion Red');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getNivel();
        $this->runTest(0, $resultado, 'Test set Base de datos Nivel');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[3]->getValor();
        $this->runTest(0, $resultado, 'Test set Base de datos Valor');

        // Nivel 2
        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getId();
        $this->runTest(3, $resultado, 'Test set Base de datos Id Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getCondicionRed();
        $this->runTest("DIRECTOS", $resultado, 'Test set Base de datos Condicion Red');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getNivel();
        $this->runTest(1, $resultado, 'Test set Base de datos Nivel');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[2]->getValor();
        $this->runTest(1.6, $resultado, 'Test set Base de datos Valor');

        // Nivel 1
        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getId();
        $this->runTest(2, $resultado, 'Test set Base de datos Id Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getCondicionRed();
        $this->runTest("RED", $resultado, 'Test set Base de datos Condicion Red');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getNivel();
        $this->runTest(2, $resultado, 'Test set Base de datos Nivel');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[1]->getValor();
        $this->runTest(0.8, $resultado, 'Test set Base de datos Valor');

        // Nivel 0
        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getId();
        $this->runTest(1, $resultado, 'Test set Base de datos Id Valor');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getIdBono();
        $this->runTest(50, $resultado, 'Test set Base de datos Id Bono');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getCondicionRed();
        $this->runTest("DIRECTOS", $resultado, 'Test set Base de datos Condicion Red');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getNivel();
        $this->runTest(3, $resultado, 'Test set Base de datos Nivel');

        $resultado = $bono->getValoresBono();
        $resultado = $resultado[0]->getValor();
        $this->runTest(0.8, $resultado, 'Test set Base de datos Valor');

        $resultado = $bono->getActivacionBono()->getInicio();
        $this->runTest("2016-03-01", $resultado, 'Test set Base de datos Inicio');

        $resultado = $bono->getActivacionBono()->getFin();
        $this->runTest("2026-03-01", $resultado, 'Test set Base de datos Fin');

        $resultado = $bono->getActivacionBono()->getMesDesdeAfiliacionAfiliado();
        $this->runTest(0, $resultado, 'Test set Base de datos Mes desde afiliacion');

        $resultado = $bono->getActivacionBono()->getMesDesdeActivacionAfiliado();
        $this->runTest(0, $resultado, 'Test set Base de datos Mes desde Activacion');

        $resultado = $bono->getActivacionBono()->getFrecuencia();
        $this->runTest('MES', $resultado, 'Test set Base de datos Frecuencia');

        $resultado = $bono->getActivacionBono()->getEstado();
        $this->runTest('ACT', $resultado, 'Test set Base de datos Frecuencia');

    }


}
