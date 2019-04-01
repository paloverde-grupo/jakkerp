<?php
require_once APPPATH . 'controllers/ctest.php';

class setupVenta extends ctest
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('/bo/bonos/venta');
        $this->load->model('/bo/bonos/mercancia');

    }

    public function index()
    {
        $this->Before();
        $this->testSetValoresVenta();
        $this->testSetValoresVentaBaseDeDatos();
        $this->after();
    }

    private function Before()
    {

        $this->venta->eliminarVentas();
        $this->mercancia->eliminarMercancias();
        $this->mercancia->eliminarCategorias();

        $datosCategoria = array(
            'id_categoria' => 250,
            'id_red' => 300,
        );

        $this->mercancia->ingresarCategoria($datosCategoria);


        $servicios = 2;

        $datosMercancia = array(
            'id_mercancia' => 500,
            'id_tipo_mercancia' => $servicios,
            'costo' => 150,
            'puntos_comisionables' => 100,
            'id_categoria' => 250,
            'id_red' => 300
        );

        $this->mercancia->nuevaMercancia($datosMercancia);
        $this->mercancia->ingresarMercancia();

        $servicios = 2;

        $datosMercancia = array(
            'id_mercancia' => 501,
            'id_tipo_mercancia' => $servicios,
            'costo' => 200,
            'puntos_comisionables' => 250,
            'id_categoria' => 250,
            'id_red' => 300
        );

        $this->mercancia->nuevaMercancia($datosMercancia);
        $this->mercancia->ingresarMercancia();

        $datosMercanciasVendidas = array();
        $datosMercancia1 = array(
            'id_mercancia' => 500,
            'costo_total' => 150,
            'puntos_comisionables' => 100,
        );

        $datosMercancia2 = array(
            'id_mercancia' => 501,
            'costo_total' => 200,
            'puntos_comisionables' => 250,
        );


        array_push($datosMercanciasVendidas, $datosMercancia1, $datosMercancia2);

        $datosVenta = array(
            'id_venta' => 700,
            'id_usuario' => 10000,
            'estatus' => 'ACT',
            'fecha' => '2016-03-01',
            'mercancia' => $datosMercanciasVendidas
        );

        $this->venta->nuevaVenta($datosVenta);
        $this->venta->ingresarVenta();

    }

    private function after()
    {
        $this->venta->eliminarVentas();
        $this->mercancia->eliminarMercancias();
        $this->mercancia->eliminarCategorias();
    }


    public function testSetValoresVenta()
    {
        $venta = $this->venta;

        $resultado = $venta->getIdVenta();
        $this->runTest(700, $resultado, 'Test set Base de datos Id Venta');

        $resultado = $venta->getIdUsuario();
        $this->runTest(10000, $resultado, 'Test set Base de datos Id Usuario');

        $resultado = $venta->getEstatus();
        $this->runTest('ACT', $resultado, 'Test set Base de datos Estatus venta');

        $resultado = $venta->getFecha();
        $this->runTest('2016-03-01', $resultado, 'Test set Base de datos fecha');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[0]["id_mercancia"];
        $this->runTest(500, $resultado, 'Test set Base de datos id mercancia');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[0]["costo_total"];
        $this->runTest(150, $resultado, 'Test set Base de datos costo total');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[0]["puntos_comisionables"];
        $this->runTest(100, $resultado, 'Test set Base de datos puntos comisionables');


        $resultado = $venta->getMercancia();
        $resultado = $resultado[1]["id_mercancia"];
        $this->runTest(501, $resultado, 'Test set Base de datos id mercancia');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[1]["costo_total"];
        $this->runTest(200, $resultado, 'Test set Base de datos costo total');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[1]["puntos_comisionables"];
        $this->runTest(250, $resultado, 'Test set Base de datos puntos comisionables');

    }

    public function testSetValoresVentaBaseDeDatos()
    {

        $venta = new $this->venta ();
        $venta->setUpVenta(700);

        $resultado = $venta->getIdVenta();
        $this->runTest(700, $resultado, 'Test set Base de datos Id Venta');

        $resultado = $venta->getIdUsuario();
        $this->runTest(10000, $resultado, 'Test set Base de datos Id Usuario');

        $resultado = $venta->getEstatus();
        $this->runTest('ACT', $resultado, 'Test set Base de datos Estatus venta');

        $resultado = $venta->getFecha();
        $this->runTest('2016-03-01 00:00:00', $resultado, 'Test set Base de datos fecha');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[0]["id_mercancia"];
        $this->runTest(500, $resultado, 'Test set Base de datos id mercancia');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[0]["costo_total"];
        $this->runTest(150, $resultado, 'Test set Base de datos costo total');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[0]["puntos_comisionables"];
        $this->runTest(100, $resultado, 'Test set Base de datos puntos comisionables');


        $resultado = $venta->getMercancia();
        $resultado = $resultado[1]["id_mercancia"];
        $this->runTest(501, $resultado, 'Test set Base de datos id mercancia');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[1]["costo_total"];
        $this->runTest(200, $resultado, 'Test set Base de datos costo total');

        $resultado = $venta->getMercancia();
        $resultado = $resultado[1]["puntos_comisionables"];
        $this->runTest(250, $resultado, 'Test set Base de datos puntos comisionables');


    }


}
