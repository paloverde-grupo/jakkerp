<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class jakkbonos extends CI_Model
{
    private $afiliados = array();
    private $temp = false;
    private $fechaInicio = '';
    private $fechaFin = '';
    private $remanente = array(0,0);

    function __construct()
    {
        parent::__construct();
        $this->load->model('/bo/bonos/afiliado');
        $this->load->model('/ov/modelo_billetera');
    }

    function getTemp()
    {
        $val = $this->temp;
        $this->temp = array();
        #log_message("DEV","<tmp> ".json_encode($val)."</tmp>");
        return $val;
    }
    function getTempRows($implode = false,$del =  false)
    {
        $val = $this->temp;
        if(!$val)
            return false;
        if($del)
            $this->temp = array();
        #log_message("DEV","<tmp> ".json_encode($val)."</tmp>");
        if($implode)
            $val = implode(",", $val);
        return $val;
    }
    function setTempRows($tmp) {
        if(gettype($this->temp)!="array")
            $this->temp = array();
        foreach ($this->temp as $tmps)
            if($tmps == $tmp)
                return false;
        array_push($this->temp,$tmp);
    }

    function getAfiliados($log = false)
    {
        $val = $this->afiliados;
        $this->afiliados = array();
        if($log)
            log_message("DEV","<afiliados> ".json_encode($val)."</afiliados>");
        if($this->temp)
            $this->getTemp();
        return $val;
    }

    function setAfiliados($afiliados) {
        if(gettype($this->afiliados)!="array")
            $this->afiliados = array();

        array_push($this->afiliados,$afiliados);
    }

    function setFechaInicio($value = '')
    {
        if (! $value)
            $value = date('Y-m-d');

        $this->fechaInicio = $value;
    }

    function setFechaFin($value = '')
    {
        if (! $value)
            $value = date('Y-m-d');

        $this->fechaFin = $value;
    }

    function getTituloAfiliado($id_usuario,$red = 1,$fecha = '' )
    {
        $query ="SELECT * FROM cross_rango_user WHERE id_user = $id_usuario";

        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q)
            return false;

        $id = $this->issetVar($q,"id_rango",0);

        return $this->getTitulo("nombre","id = $id");
    }


    function isActived ( $id_usuario,$id_bono = 0,$red = 1,$fecha = '' )
    {
        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $this->setFechaInicio($this->getPeriodoFecha($periodo,"INI", $fecha));
        $this->setFechaFin($this->getPeriodoFecha($periodo, "FIN", $fecha));

        $isActived = $this->isActivedAfiliado($id_usuario,$id_bono);
        $isPaid = $this->isPaid($id_usuario,$id_bono,$red);
        $isScheduled = $this->isValidDate($id_usuario,$id_bono);

        $json_1 = json_encode($isPaid);
        $json_2 = json_encode($isActived);
        $json_3 = json_encode($isScheduled);
        $part= "P: $json_1 A: $json_2 S: $json_3";
        $log = "ID:$id_usuario BONO:$id_bono RED:$red ACTIVO: [$part]";
        log_message('DEV',$log);

        if($isPaid||!$isActived||!$isScheduled)
            return false;

        return true;
    }

    function isActivedAfiliado($id_usuario,$bono = 1)
    {
        if($id_usuario==2)
            return true;

        $fechaInicio=$this->getPeriodoFecha("UNI", "INI", '');
        $fechaFin=$this->getPeriodoFecha("UNI", "FIN", '');

        $cumple = $this->getVentaMercancia($id_usuario,$fechaInicio,$fechaFin,2,false);

        if($bono != 2)
            return $cumple;

        $valores = $this->getBonoValorNiveles(1);
        $afiliados = $this->getAfiliadosInicial($valores, $id_usuario, $fechaInicio, $fechaFin);

        if(!$afiliados)
            return false;

        $afiliados = $afiliados[0];

        $nAfiliados = sizeof($afiliados);
        $cumple &= ($nAfiliados > 1);

        return $cumple;
    }


    public function getNombreRed($id_red)
    {
        $dato_red = $this->getTipoRed($id_red);
        $nombre_red = $this->issetVar($dato_red, "nombre", "Red");

        return $nombre_red;
    }

    public function getLastAfiliar($id, $red)
    {
        $query = "SELECT max(id) id FROM afiliar WHERE id_afiliado = $id AND id_red = $red";

        $q = $this->db->query($query);
        $q = $q->result();

        $id = $q ? $q[0]->id : rand(1000, 9999);
        return $id;
    }


    private function isRegistered($id)
    {
        $query = "SELECT * FROM afiliar WHERE id_afiliado = $id AND id_red = 1";

        $this->db->query($query);
        return $query;
    }

    private function isPaid($id_usuario,$id_bono,$red = 1)
    {
        $query = "SELECT
						*
					FROM
						comision_bono c,
						comision_bono_historial h
					WHERE
						c.id_bono_historial = h.id
						AND c.id_bono = h.id_bono
						AND h.id_bono = $id_bono 
						AND c.id_usuario = $id_usuario
						AND h.fecha BETWEEN '$this->fechaInicio' AND '$this->fechaFin'
						AND (c.valor > 0 )";

        $q = $this->db->query($query);
        $q =$q->result();

        if (! $q)
            return false;

        $valid = (sizeof($q) > 0) ? true : false;

        return $valid;
    }

    private function isValidDate($id_usuario,$id_bono,$dia = false)
    {
        $bono = $this->getBono($id_bono);

        $mes_inicio = $bono[0]->mes_desde_afiliacion;
        $mes_fin = $bono[0]->mes_desde_activacion;

        if($mes_inicio<=0)
            return true;

        $select = "DATE_FORMAT(created, '%Y-%m')";
        $select.= " < DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $mes_inicio MONTH),'%Y-%m')";

        if($dia)
            $select = "created < DATE_SUB(NOW(), INTERVAL $mes_inicio MONTH)";

        $query = "SELECT
					    $select 'valid'
					FROM
					    users
					WHERE
					    id = " . $id_usuario;

        $q = $this->db->query($query);
        $q = $q->result();

        if (! $q)
            return false;

        $valid = $this->issetVar($q,"valid",0);
        $valid = ($valid == 1) ? true : false;

        return $valid;
    }

    private function isScheduled($id_usuario,$id_bono,$fecha = "")
    {
        $bono = $this->getBono($id_bono);

        $mes_inicio = $bono[0]->mes_desde_afiliacion;
        $mes_fin = $bono[0]->mes_desde_activacion;
        $where = "";

        $fecha = (strlen($fecha)>2) ? "'".$fecha."'" : "NOW()";

        $isHalfMonth = "(DATE_FORMAT(created,'%d')<16)";
        $halfMonth = "CONCAT(DATE_FORMAT(created,'%Y-%m'),'-15')";
        $limiteInicio = "(CASE WHEN $isHalfMonth THEN $halfMonth ELSE LAST_DAY(created) END)";

        if($mes_inicio>0)
            $where .= " AND DATE_FORMAT($fecha, '%Y-%m-%d') > ".$limiteInicio;

        if($mes_fin>0){
            $mes_fin+=$mes_inicio;
            $where .= " AND DATE_FORMAT($fecha, '%Y-%m-%d') <= ".$limiteInicio;
        }

        $query = "SELECT
					    1 'valid'
					FROM
					    users
					WHERE
					    id = ".$id_usuario.$where;

        $q = $this->db->query($query);
        $q = $q->result();
        if (! $q)
            return false;

        $valid = $this->issetVar($q,"valid",0);
        $valid = ($valid == 1) ? true : false;

        return $valid;
    }

    function getBonosUsuario($id_usuario)
    {
        $redes = $this->getBonoRedes($id_usuario);
        $bonos = $this->getBonos();

        $fecha = date('Y-m-d');
        $parametro = array("id_usuario" => $id_usuario,"fecha" => $fecha);
        $formatoMonto = 'integer';
        $formatoDoble = 'array';

        foreach ($redes as $red){

            $id_red= $red->id_red;
            $parametro["red"] = $id_red;

            foreach ($bonos as $bono){

                $id_bono = $bono->id;
                $valor = 0;
                $extra = "";

                $isActived = $this->isActived($id_usuario,$id_bono,$id_red);

                if($isActived)
                    $valor = $this->getValorBonoBy($id_bono, $parametro);

                $isDoble = gettype($valor) == $formatoDoble;
                $isMonto = gettype($valor) == $formatoMonto;

                if($isDoble){
                    $extra = $valor[1];
                    $valor = $valor[0];
                }else if(!$isMonto){
                    $extra = $valor;
                    $valor = 0;
                }

                $isGanancia = $valor>0||strlen($extra)>2;

                if($isGanancia)
                    $this->repartirBono($id_bono, $id_usuario, $valor,$extra,$fecha,$id_red);

            }/* foreach: $bonos */
        }/* foreach: $redes */
    }

    private function repartirBono($id_bono,$id_usuario,$valor,$extra = "",$fecha="",$red = 1)
    {
        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo,"INI", $fecha);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $fecha);

        $historial = $this->getHistorialBono($id_bono,$fechaInicio, $fechaFin,$red);

        if(!$historial)
            $historial= $this->newHistorialBono($id_bono, $fechaInicio, $fechaFin,$red);

        $this->insertBonoUsuario($id_bono, $id_usuario, $valor, $historial,$extra);

        return true;
    }

    private function newHistorialBono($id_bono, $fechaInicio, $fechaFin,$red=1)
    {
        $dia = date('d', strtotime($fechaInicio));
        $mes = date('m', strtotime($fechaInicio));
        $anio = date('Y', strtotime($fechaInicio));

        $query = "INSERT INTO comision_bono_historial (id_bono,dia,mes,ano,fecha)
                    VALUES ($id_bono,$dia,$mes,$anio,'$fechaFin')";

        $this->db->query($query);

        $result = $this->getHistorialBono($id_bono,$fechaInicio, $fechaFin);

        return $result;
    }

    private function getHistorialBono($id_bono, $fechaInicio, $fechaFin,$red=1)
    {
        $query = "SELECT * FROM comision_bono_historial
            		WHERE
                        fecha  between '$fechaInicio' AND '$fechaFin'
                        AND id_bono = $id_bono";

        $q = $this->db->query($query);
        $result = $q->result();

        if (! $result)
            return false;

        $historial = $result[0]->id;

        return $historial;
    }

    private function insertBonoUsuario($id_bono, $id_usuario, $valor, $historial, $extra="")
    {
        $query = "INSERT INTO comision_bono
                     (id_usuario,id_bono,id_bono_historial,valor,extra)
                    VALUES 
                     ($id_usuario,$id_bono,$historial,$valor,'$extra')";

        $this->db->query($query);
    }


    function getValorBonoBy($id_bono,$parametro)
    {
        switch ($id_bono){

            case 1 :
                return $this->getValorBonoDirectos($parametro);
                break;

            case 2 :
                return $this->getValorBonoBinario($parametro);
                break;

            case 3 :
                return $this->getValorBonoInversion($parametro);
                break;

            default:
                return 0;
                break;

        }/* switch: $id_bono */
    }

    private function getValorBonoDirectos($parametro)
    {
        $id_bono = 1;
        $valores = $this->getBonoValorNiveles($id_bono);

        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];
        $id_red = isset($parametro["red"]) ?  $parametro["red"] : 1;

        log_message('DEV', "BONO $id_bono between: $fechaInicio - $fechaFin");

        $afiliados = $this->getAfiliadosInicial($valores, $id_usuario, $fechaInicio, $fechaFin);

        $monto = $this->getMontoInicial($valores, $afiliados, $fechaInicio, $fechaFin);

        return $monto;
    }

    private function getAfiliadosInicial($valores, $id, $fechaInicio, $fechaFin)
    {
        $where = ""; #@test: 1

        $afiliados = array();

        foreach ($valores as $nivel) {

            if ($nivel->nivel > 0) {

                $this->getDirectosBy($id, $nivel->nivel, $where);
                array_push($afiliados, $this->getAfiliados());

            }/* if: $nivel */
        }/* foreach: $valores */

        return $afiliados;
    }

    private function getMontoInicial($valores, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0; $lvl = 0;
        $where = "AND v.id_venta not in (select id_venta from comision)";
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $Corre = ($i > 0) && isset($afiliados[$lvl]);
            if ($Corre) {
                $per = $valores[$i]->valor / 100;
                #@test: 2
                $afiliado = implode(",", $afiliados[$lvl]);
                $venta = $this->getVentaMercancia($afiliado,$fechaInicio,$fechaFin,2,false,$where);
                $valor = 0;
                if($venta)
                    $valor = $venta[0]->puntos_comisionables;
                $valor*=$per;
                #@test: 3
                log_message('DEV', "A:$afiliado N:$i P:".($per * 100)."% V:$valor S:$monto");
                $monto += $valor;
                #@test: 4
                $lvl ++;
            }/* if: $corre */
        }/* for: $valores */
        return $monto;
    }

    private function getValorBonoBinario($parametro,$pagar=false)
    {
        if(!isset($parametro["fecha"]))
            $parametro["fecha"] = date('Y-m-d');

        $id_bono = 2;
        $valores = $this->getBonoValorNiveles($id_bono);

        $bono = $this->getBono(1);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];
        $id_red = isset($parametro["red"]) ?  $parametro["red"] : 1;
        log_message('DEV', "BONO $id_bono between: $fechaInicio - $fechaFin ($periodo)");

        $afiliados = $this->getAfiliadosMatriz($valores,$id_usuario);

        if(!$afiliados)
            return 0;

        $ganancia = $this->getGananciaBinario($id_usuario,$afiliados,$valores,$fechaInicio, $fechaFin);
        if($ganancia==0)
            return 0;

        list($ganancia,$reporte) = $ganancia;

        #if($pagar)
            $this->repartirBono($id_bono, $id_usuario, $ganancia,$reporte,$fechaFin);

        return 0;
    }

    private function getAfiliadosMatriz($valores, $id)
    {
        $where = ""; #@test: 1

        $afiliados = array();

        if(!$valores)
            return array();

        $limite = $valores[0]->valor;

        for($key = 0;$key<$limite; $key++) {

            $nivel = $key + 1;

            $this->getAfiliadosBy($id, $nivel, "RED", $where);
            array_push($afiliados, $this->getAfiliados());


        }/* foreach: $valores */
        log_message('DEV',"afiliados ".json_encode($afiliados));
        return $afiliados;
    }

    function getGananciaBinario($id_usuario,$afiliados,$valores,$fechaInicio, $fechaFin) {

        $datos = $this->ComprobarBrazos($afiliados);

        if(!$datos)
            return 0;

        list($afiliados,$brazos) = $datos;

        log_message('DEV',"NIVEL 1 : ".json_encode($brazos));
        list($puntos, $ventas) = $this->setPuntosFrontales($id_usuario,$fechaInicio, $fechaFin, $brazos);

        if(!$afiliados)
            return $puntos;

        $uplines =$brazos;

        foreach ($afiliados as $n => $nivel){
            $idx = $n+1;
            log_message('DEV',"NIVEL $idx : ".json_encode($nivel));
            foreach ($nivel as $key => $afiliado){

                $this->setPuntosDerrame( $afiliado, $fechaInicio,$fechaFin, $uplines, $puntos, $ventas);

                log_message('DEV',"lados [$key] : ".json_encode($uplines));
            }
            log_message('DEV', "VENTAS :::>>> ".json_encode($ventas)."PUNTOS :::>>> ".json_encode($puntos));
        }
        log_message('DEV',"ventas  : ".json_encode($ventas));

        $conteo = $puntos;

        $puntos = $this->setPuntosTotales($conteo);
        $puntos = $this->setBrazoMenor($puntos);

        if(!$puntos)
            return false;

        list($puntos,$debil) = $puntos;

        $cumple= $this->getAfiliadosBinario($id_usuario,$fechaFin);

        if($cumple){
            list ($afiliados,$binario) = $cumple;
            $cumple = sizeof($binario) >= 2;
        }
        $remanente = $this->setDatosArrayUnset($ventas, $debil);
        $sobrante= $this->setDatosArrayUnset($conteo, $debil);

        if($cumple){
            $remanente = $this->setRemanentesBinario( $remanente, $sobrante, $puntos);

        } else {
            $remanente = $this->setRemanentesBinario( $remanente, $sobrante);
        }

        $remanente = json_encode($remanente);
        $this->updateRemanenteDebil($id_usuario, $debil, $remanente);

        $ganados = $ventas[$debil];

        $ganados = explode(",", $ganados);
        $pagadas = explode(",", $conteo[$debil]);

        $reporte = $this->setReporteBinario($ganados, $pagadas);
        $reporte =  json_encode($reporte);
        $this->updateRemanente($id_usuario, $debil, $reporte);
        if($ganados == 0)
            return 0;

        if(!$cumple){
            log_message('DEV',">>> NO CUMPLE CONDICION ($id_usuario)");
            return 0;
        }

        $this->updateRemanente($id_usuario, $debil);

        $per = $valores[1]->valor / 100;
        $ganancia = $puntos*$per;

        $regresion = json_encode($this->remanente);
        $extra = "$reporte|$regresion";

        log_message('DEV',">>> BINARIO -> $puntos * $per V:$extra R:$remanente");

        return array($ganancia,$extra);
    }

    private function getPuntosRemanente($id_usuario)
    {
        $remanentes = $this->getBonoRemanente($id_usuario);
        $puntos = array(0, 0);
        foreach ($remanentes as $key => $pata) {
            $datos = json_decode($pata);
            $isObject = gettype($datos) == "object";
            $isArray = gettype($datos) == "array";
            $isEmpty = sizeof($datos) < 1 || $datos === 0;
            log_message('DEV', "pata $key :: $pata ");

            if ($isObject && $isArray)
                continue;
            else if($isEmpty )
                continue;

            foreach ($datos as $id_venta => $valor)
                $puntos [$key] += $valor;

        }

        $json_1 = json_encode($puntos);

        log_message('DEV', "Puntos remanente : $json_1");
        return $puntos;
    }


    function getPuntosBinario($id_usuario, $fecha = false)
    {
        if (!$fecha)
            $fecha = date('Y-m-d');

        $usuario = new $this->afiliado;

        $def = "0";
        $fechaInicio = $this->getPeriodoFecha("DIA", "INI", $fecha);
        $fechaFin = $this->getPeriodoFecha("DIA", "FIN", $fecha);

        #$redes = $this->getRedes();

        $puntos = $this->getPuntosRemanente($id_usuario,2);
        $id_red = 1;
        $profundidad = 4;
        $isbrazos = $this->getAfiliadosBinario($id_usuario);

        if($isbrazos){
            list($brazos,$directos) = $isbrazos;
            $isbrazos = sizeof($brazos)>=2;
        }

        log_message('DEV',"PUNTOS BINARIO :: $id_usuario");
        if(!$isbrazos)
            return $puntos;

        $personal = "getPuntosTotalesPersonalesIntervalosDeTiempo";
        $enlared="getVentasTodaLaRed";
        foreach ($brazos as $key => $brazo) {
            $puntos_personal= $usuario->$personal($brazo, $id_red, $def, $def, $fechaInicio, $fechaFin);
            $puntos_red= $usuario->$enlared($brazo, $id_red, "RED", "EQU", $profundidad, $fechaInicio, $fechaFin, $def, $def, "PUNTOS");

            $puntos[$key] =  $puntos_personal + $puntos_red;

            log_message('DEV',"[$key]($brazo) -> $puntos_personal + $puntos_red = ".$puntos[$key]);
        }

        return $puntos;
    }

    function getPuntosBrazos($id_usuario,$fecha = false,$frecuencia = "UNI"){

        if(!$fecha)
            $fecha = date('Y-m-d');

        $id_bono = 2;
        $valores = $this->getBonoValorNiveles($id_bono);
        $afiliados = $this->getAfiliadosMatriz($valores,$id_usuario);

        $fechaInicio=$this->getPeriodoFecha($frecuencia, "INI", $fecha);
        $fechaFin=$this->getPeriodoFecha($frecuencia, "FIN", $fecha);

        $datos = $this->ComprobarBrazos($afiliados);

        if(!$datos)
            return 0;

        list($afiliados,$brazos) = $datos;

        log_message('DEV',"NIVEL 1 : ".json_encode($brazos));
        list($puntos, $ventas) = $this->setPuntosFrontales($id_usuario,$fechaInicio, $fechaFin, $brazos);

        if(!$afiliados)
            return $puntos;

        $uplines =$brazos;

        foreach ($afiliados as $n => $nivel){
            $idx = $n+1;
            log_message('DEV',"NIVEL $idx : ".json_encode($nivel));
            foreach ($nivel as $key => $afiliado){
                $venta = $this->getVentaMercancia($afiliado,$fechaInicio,$fechaFin,2,false);

                if(!$venta)
                    continue;

                $this->setPuntosDerrame($venta, $afiliado, $uplines, $puntos, $ventas);

                log_message('DEV',"lados [$key] : ".json_encode($uplines));
            }
        }
        log_message('DEV',"ventas  : ".json_encode($ventas));

        $conteo = $puntos;

        $puntos = $this->setPuntosTotales($conteo);

        return $puntos;

        $puntos = $this->setBrazoMenor($puntos);

        if(!$puntos)
            return false;

        list($puntos,$debil) = $puntos;

        $remanente = $this->setDatosArrayUnset($ventas, $debil);
        $sobrante= $this->setDatosArrayUnset($conteo, $debil);
        $remanente = $this->setRemanentesBinario($puntos,  $remanente, $sobrante);
        $remanente = json_encode($remanente);

        #TODO: $this->updateRemanente($id_usuario, $debil, $remanente);

        $ganados = $ventas[$debil];
        if($ganados == 0)
            return 0;

        $ganados = explode(",", $ventas[$debil]);
        $pagadas = explode(",", $conteo[$debil]);

        $reporte = $this->setReporteBinario($ganados, $pagadas);
        $reporte =  json_encode($reporte);

        $per = $valores[1]->valor / 100;
        $ganancia = $puntos*$per;

        log_message('DEV',">>> BINARIO -> $puntos * $per V:$reporte R:$remanente");
        return array($ganancia,$reporte);
    }

    private function setRemanente($id,$remanente,$bono = 2){

        $exist = $this->getRemanente($id, $bono);

        if($exist){
            $this->db->where('id_usuario', $id);
            $this->db->where('id_bono', $bono);
            $this->db->update('comisionPuntosRemanentes',$remanente);
        }else{
            $remanente['id_usuario'] = $id;
            $remanente['id_bono'] = $bono;
            $this->db->insert('comisionPuntosRemanentes',$remanente);
        }

    }

    private function getBonoRemanente($id,$bono = 2) {

        $q = $this->getRemanente ($id,$bono);

        if (!$q)
            return array (0,0);

        $remanente = array (
            $q[0]->izquierda,
            $q[0]->derecha
        );

        return $remanente;
    }

    private function getRemanente($id,$bono) {
        $q = $this->db->query ( "SELECT * FROM comisionPuntosRemanentes WHERE id_bono = $bono and id_usuario = $id" );
        $q = $q->result ();
        return $q;
    }


    private function setValoresRemanente($id_usuario)
    {
        $remanentes = $this->getBonoRemanente($id_usuario);
        $this->remanente = $remanentes;
        $puntos = array(0, 0);
        $ventas = array(0, 0);
        foreach ($remanentes as $key => $pata) {
            $datos = json_decode($pata);
            $isObject = gettype($datos) == "object";
            $isArray = gettype($datos) == "array";
            $isEmpty = sizeof($datos) < 1 || $datos === 0;
            log_message('DEV', "pata $key :: $pata ");

            if ($isObject && $isArray)
                continue;
            else if($isEmpty )
                continue;

            foreach ($datos as $id_venta => $valor) {
                $puntos = $this->setValueSeparated($puntos, $key, $valor);
                $ventas = $this->setValueSeparated($ventas, $key, $id_venta);
            }
        }
        $json_1 = json_encode($puntos);
        $json_2 = json_encode($ventas);
        log_message('DEV', "remanente : P:$json_1 V:$json_2");
        return array($puntos, $ventas);
    }

    private function setValueSeparated($datos, $key, $value,$split= false)
    {
        if($split){
            $list = explode(",",$datos[$key]."");
            foreach ($list as $data)
                if($data == $value)
                    return $datos;
        }

        if ($datos[$key] == 0)
            $datos[$key] = $value;
        else
            $datos[$key] .= ",$value";
        return $datos;
    }

    private function setPuntosTotales($datos)
    {
        $puntos = array(0, 0);
        foreach ($datos as $key => $dato) {
            $values = explode(",", $dato);
            $puntos[$key] = array_sum($values);
        }
        return $puntos;
    }

    private function setDatosArrayUnset($datos, $unset = 0)
    {
        $newDatos = $datos;
        unset($newDatos[$unset]);
        $newDatos = explode(",", implode(",", $newDatos));
        return $newDatos;
    }

    private function setDatosUnset($datos, $unset = 0)
    {
        $newDatos = $datos;
        unset($newDatos[$unset]);

        $newDatos = implode(",", $newDatos);
        return $newDatos;
    }

    private function setReporteBinario($ganados, $pagadas)
    {
        $reporte = array();
        foreach ($ganados as $key => $id_venta) {
            $valor = $pagadas[$key];
            $reporte[$id_venta] = $valor;
        }
        return $reporte;
    }

    private function setRemanentesBinario($remanente, $conteo,$puntos = 0)
    {
        $monto = 0;
        $sobrantes = array();
        foreach ($remanente as $key => $id_venta) {

            $valor = $conteo[$key];

            $suma = $monto + $valor;

            if ($suma > $puntos ){

                if($monto < $puntos)
                    $valor = $suma-$puntos;

                $sobrantes[$id_venta] = $valor;
            }

            $monto = $suma;
        }
        return $sobrantes;
    }
    private function updateRemanenteDebil($id_usuario, $debil, $remanente = 0)
    {
        $contrario = ($debil == 0) ? 1 : 0;
        $this->updateRemanente($id_usuario,$contrario,$remanente);
    }

    private function updateRemanente($id_usuario, $lado, $remanente = 0)
    {
        $lados = array();

        $ladomayor = ($lado == 0) ? "izquierda" : "derecha";

        $lados[$ladomayor] = $remanente;

        $this->setRemanente($id_usuario, $lados, 2);
    }

    function ComprobarBrazos($afiliados){
        if(!isset($afiliados[0]))
            return false;

        $brazos =  $afiliados[0];

        $nBrazos = sizeof($brazos);
        if($nBrazos <2)
            return false;

        unset($afiliados[0]);

        log_message('DEV',"brazos $nBrazos : ".json_encode($brazos));
        return array($afiliados,$brazos);
    }

    private function isUpline($id,$id_debajo = 2, $red = 1)
    {
        $query = "select * from afiliar -- imprimir
                    where debajo_de in ($id_debajo)
                      and id_afiliado = $id
                      and id_red = $red ";
        $query = $this->db->query($query);

        $lados = $query->result();
        return $lados;
    }


    private function setBrazoMenor($puntos)
    {
        $sumPuntos = array_sum($puntos);

        if($sumPuntos == 0)
            return false;

        $menor = 0;$debil=false;$aplica = false;
        foreach ($puntos as $key => $punto) {
            log_message('DEV',">>> PUNTO [$key] -> [[ $punto ]]");
            if (!$aplica || $punto < $menor){
                $debil = $key;
                $menor = $punto;
            }
            $aplica=true;
        }

        $json = json_encode($puntos);
        log_message('DEV',">>> PUNTOS : $json -> $menor");
        return array($menor,$debil);
    }

    private function setPuntosFrontales($id_usuario,$fechaInicio, $fechaFin, $brazos)
    {
        list($puntos, $ventas) = $this->setValoresRemanente($id_usuario);

        foreach ($brazos as $key => $brazo) {
            $venta = $this->getVentaMercancia($brazo, $fechaInicio, $fechaFin, 2, false);

            if (!$venta)
                continue;

            $valor = $this->issetVar($venta,"puntos_comisionables",0);
            $id_venta = $this->issetVar($venta,"id_venta",1);
            log_message('DEV', "Frontales : A1:$brazo N:1 V:$valor K:$key");

            $puntos = $this->setValueSeparated($puntos, $key, $valor);
            $ventas = $this->setValueSeparated($ventas, $key, $id_venta);
        }

        return array($puntos, $ventas);
    }

    private function setPuntosDerrame($afiliado,$fechaInicio,$fechaFin, &$uplines, &$puntos, &$ventas)
    {
        foreach ($uplines as $key => $upline) {
            $isUpline = $this->isUpline($afiliado, $upline);
            if (!$isUpline)
                continue;

            $uplines[$key] .= ",$afiliado";

            $venta = $this->getVentaMercancia($afiliado,$fechaInicio,$fechaFin,2,false);

            if(!$venta)
                continue;

            $valor = $venta[0]->puntos_comisionables;
            $id_venta = $venta[0]->id_venta;

            log_message('DEV', ">>>! ADD lado[$key] ->> a:$afiliado I:$id_venta V:$valor");

            $puntos = $this->setValueSeparated($puntos, $key, $valor);
            $ventas = $this->setValueSeparated($ventas, $key, $id_venta,true);

        }
    }


    function setBrazosFinales($id_usuario,$Patas,$fecha = false){

        if (! $fecha)
            $fecha = date ( "Y-m-d" );

        $izquierda = ($Patas[0]);
        $derecha = ($Patas[1]);

        $debil = ($izquierda<$derecha) ? $izquierda : $derecha;

        $pagado = $this->calcularPuntosPagados($id_usuario, $debil,$fecha);
        $izquierda-=($pagado>$izquierda) ? $izquierda : $pagado;
        $derecha-=($pagado>$derecha) ? $derecha : $pagado;

        $Patas = array($izquierda,$derecha);
        log_message ( 'DEV', "-->> Brazos Neto ($id_usuario) :: " . json_encode ( $Patas ) );

        return $Patas;

    }

    function getPuntosPatas($id_usuario,$fechaInicio,$fechaFin,$condiciones=false) {
        $usuario = new $this->afiliado ();
        $tipo = ($condiciones) && ($condiciones > 0) ? $this->setCondicionValores($condiciones,"condicion1") : "0";
        $item = ($condiciones) && ($condiciones > 0) ? $this->setCondicionValores($condiciones,"condicion2") : "0";

        $afiliados = $this->getAfiliadosBinario ( $id_usuario );

        $puntos = $this->getHistorialBinario($id_usuario,$fechaInicio,$tipo,$item,$fechaFin);

        if(!$puntos)
            return false;

        $Patas = array($puntos[0]->izquierdo,$puntos[0]->derecho);

        log_message ( 'DEV', "-->> " . json_encode ( $Patas ) );

        return (sizeof ( $Patas ) < 2) ? false : $Patas;
    }

    private function setCondicionValores($condicion = false,$nombre = "condicion1"){

        if(sizeof($condicion)>1){
            $condiciones = array();
            $valor_condicion= 0;
            foreach ($condicion as $cond){
                $val =$cond->$nombre;
                if($valor_condicion != $val){
                    $valor =  "'".$val."'";
                    array_push($condiciones, $valor);
                    $valor_condicion = $val;
                }
            }
            return implode(",",$condiciones);
        }

        return $condicion [0]->$nombre;
    }

    private function getAfiliadosBinario($id_usuario,$fecha = false)
    {
        if (! $fecha)
            $fecha = date ( "Y-m-d" );

        $this->getAfiliadosBy($id_usuario, 1, "RED", "order by lado",$id_usuario,1);
        $afiliados = $this->getAfiliados();

        $this->getAfiliadosBy($id_usuario, 1, "DIRECTOS", "",$id_usuario,1);
        $directos = $this->getAfiliados();

        if(!$directos)
            return false;

        $fechaInicio=$this->getPeriodoFecha("UNI", "INI", '');

        $lados = array();
        foreach ($afiliados as $key =>$id_user){

            $venta = $this->getVentaMercancia($id_user, $fechaInicio, $fecha, 2, false);

            $Directo = $this->getAfiliacion($id_user);
            if($Directo)
                $Directo = ($Directo[0]->directo == $id_usuario) ? $id_user : false;

            $json = json_encode($venta);
            log_message('DEV',"Directo ($id_user) -->>>> $json | [[ $Directo ]]");

            if(!$venta || !$Directo)
                $Directo = $this->isDirectoLado($id_usuario,$afiliados, $id_user,$fecha);

            log_message('DEV',"condicion ($id_user)[$key] :: [[ $Directo ]]");

            if(!$Directo)
                return false;

            $lados[$key] = $Directo;

        }

        return array($afiliados,$lados);
    }

    private function getAfiliacion($id, $red = 1)
    {
        $q = $this->db->query("SELECT * FROM afiliar WHERE id_afiliado = $id and id_red = $red");
        $q = $q->result();

        return $q;
    }

    private function isDirectoLado($id_usuario , $afiliados, $directo,$fecha = false)
    {
        if (! $fecha)
            $fecha = date ( "Y-m-d" );

        $fechaInicio=$this->getPeriodoFecha("UNI", "INI", '');
        $fechaFin=$fecha;

        $datoid = $this->getAfiliacion($directo,1);
        $lado = $datoid[0]->lado;

        $mired = $afiliados;
        $directos = false;
        $isdirecto = false;
        $isDirectoCompra = false;
        while(!$isdirecto){

            $islado = false;
            foreach ($mired as $uid){
                $datoid = $this->getAfiliacion($uid,1);
                $milado = $datoid[0]->lado;
                $islado = ($milado==$lado);
                if($islado){
                    $this->getAfiliadosBy($uid, 1, "RED", "",$id_usuario,1);
                    $mired = $this->getAfiliados();
                    $this->getAfiliadosBy($uid, 1, "DIRECTOS", "",$id_usuario,1);
                    $directos = $this->getAfiliados();

                    $isDirectoCompra = false;
                    foreach ($directos as $id_user){
                        $venta = $this->getVentaMercancia($id_user, $fechaInicio, $fechaFin, 2, false);

                        if ($venta)
                            $isDirectoCompra = $id_user;
                    }

                    break;
                }
            }

            if ($isDirectoCompra)
                $isdirecto = true;
            else if (! $islado)
                $isdirecto = true;
        }

        return $isDirectoCompra;
    }

    private function isDirectoContraLado($id_usuario , $afiliados, $directo,$fecha = false)
    {
        if (! $fecha)
            $fecha = date ( "Y-m-d" );

        $fechaInicio=$this->getPeriodoFecha("UNI", "INI", '');
        $fechaFin=$fecha;

        $datoid = $this->getAfiliacion($directo,1);
        $lado = $datoid[0]->lado == 1 ? 0 : 1;

        $mired = $afiliados;
        $directos = false;
        $isdirecto = false;
        $isDirectoCompra = false;
        while(!$isdirecto){

            $islado = false;
            foreach ($mired as $uid){
                $datoid = $this->getAfiliacion($uid,1);
                $milado = $datoid[0]->lado;
                $islado = ($milado==$lado);
                if($islado){
                    $this->getAfiliadosBy($uid, 1, "RED", "",$id_usuario,1);
                    $mired = $this->getAfiliados();
                    $this->getAfiliadosBy($uid, 1, "DIRECTOS", "",$id_usuario,1);
                    $directos = $this->getAfiliados();

                    $isDirectoCompra = false;
                    foreach ($directos as $id_user){
                        $venta = $this->getVentaMercancia($id_user, $fechaInicio, $fechaFin, 2, false);

                        if ($venta)
                            $isDirectoCompra = $id_user;
                    }

                    break;
                }
            }

            if ($isDirectoCompra)
                $isdirecto = true;
            else if (! $islado)
                $isdirecto = true;
        }

        return $isDirectoCompra;
    }

    function getValorBonoInversion($parametro,$pagar = false)
    {
        if(!isset($parametro["fecha"]))
            $parametro["fecha"] = date('Y-m-d');

        $valores = $this->getBonoValorNiveles(3);

        $bono = $this->getBono(1);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];
        $id_red = isset($parametro["red"]) ?  $parametro["red"] : 1;

        $Ganancia = $this->getGananciaInversion($id_usuario,$valores,$id_red,$fechaInicio,$fechaFin);

        if($pagar&&$Ganancia>0)
            $this->repartirBono(3, $id_usuario, $Ganancia,"",$fechaFin);

        return $Ganancia;
    }

    private function getGananciaInversion($id_usuario,$valores,$id_red,$fechaInicio=false,$fechaFin=false)
    {
        if($id_usuario == 1)
            return 0;

        $Ganancia = 0;

        return $Ganancia;
    }

    function getValorBonoRangos($parametro,$pagar = false)
    {
        if(!isset($parametro["fecha"]))
            $parametro["fecha"] = date('Y-m-d');

        $valores = $this->getBonoValorNiveles(4);

        $bono = $this->getBono(4);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];

        log_message('DEV', "between: $fechaInicio - $fechaFin");

        $titulo = $this->getRangoAfiliado($id_usuario);

        $isCobro = true;
        if (! $titulo || ! $isCobro)
            return 0;

        $monto = $this->getMontoRangos($id_usuario, $valores, $titulo);

        if($pagar&&$monto>0)
            $this->repartirBono(4, $id_usuario, $monto,"",$fechaFin);

        return $monto;
    }

    function isInversion ($id,$fecha = false){
        if(!$fecha)
            $fecha = date('Y-m-d');

        $fechaInicio = $this->getInicioFecha($id);
        $venta = $this->getVentaMercancia($id,$fechaInicio,$fecha,2,false,"",true);

        if ($id == 2)
            return true;

        if (!$venta)
            return false;

        $fecha_venta = $this->issetVar($venta,"fecha",$fechaInicio);

        $conteo = $this->getDiffTime($fecha_venta, $fecha);

        if($conteo<=0)
            return false;

        return true;
    }

    function getPasivo($id,$fecha = false){
        if(!$fecha)
            $fecha = date('Y-m-d');


        $billetera = $this->modelo_billetera->get_estatus($id);
        $pasivo = $this->issetVar($billetera,"inversion");
        $fechaInicio = $this->getInicioFecha($id);
        $venta = $this->getVentaMercancia($id,$fechaInicio,$fecha,2,false,"",true);

        if ($id == 2)
            $pasivo = 1;

        if (!$pasivo)
            return false;
        else if (!$venta)
            return false;

        $monto = $this->issetVar($venta,"puntos_comisionables",0);
        $fecha_venta = $this->issetVar($venta,"fecha",$fechaInicio);
        $valores = $this->getBonoValorNiveles(3);
        $factor = $this->issetVar($valores,"valor",1);
        $inversion = !isset($valores[$pasivo]) ? 1 : $valores[$pasivo]->valor;
        $pasivo *= $factor;

        $fecha_estimada = $this->getAnyTime($fecha_venta,"$pasivo month");

        $conteo = $this->getDiffTime($fecha_venta, $fecha_estimada);
        $contados = $this->getDiffTime($fecha_venta, $fecha);

        $per = 100/$conteo;
        $width = $contados*$per;

        $tiempo = "$pasivo MESES";
        $per = 1+($inversion/100);

        $bono = $monto * $per;
        $per = $bono / 100;
        $acumulado = $width * $per;
        $acumulado = round($acumulado,2);
        $acumulado = "$ $acumulado de $ $bono";

        log_message('DEV',"INV: $width per C $inversion % $tiempo meses $ $bono");

        return array($width,$tiempo,$acumulado);

    }

    private function getRangoAfiliado($id_usuario)
    {
        $query = "SELECT * FROM cross_rango_user
					WHERE
					    id_user = $id_usuario
					    AND estatus = 'ACT'";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q ? $q[0] : false;
    }

    private function getTitulo($param = "", $where = "")
    {
        if ($where)
            $where = " WHERE " . $where;

        $query = "SELECT * FROM cat_titulo
					$where
					ORDER BY orden ASC";

        $q = $this->db->query($query);
        $result = $q->result();

        if (! $result)
            return false;

        if ($param && isset($result[0]->$param))
            $result = $result[0]->$param;
        else if ($param === 0)
            $result = $result[0];

        return $result;
    }

    function getPuntos($id,$where = ""){
        $redes = $this->getRedUsuarioby($id,$where);
        $acumulado = 0;
        foreach ($redes as $key => $red_dato){
            $acumulado+=$red_dato->puntos;
        }
        return $acumulado;
    }

    private function getMontoRangos($id_usuario, $valores, $rango)
    {
        $id_rango = $rango->id_rango;


        $bono_rango= isset($valores[$id_rango]) ? $valores[$id_rango]->valor : $valores[1]->valor;

        $monto = $bono_rango;

        $this->entregar_rango($id_usuario,$id_rango);

        return $monto;
    }

    private function entregar_rango($id_usuario,$rango = 0)
    {
        $query = "UPDATE cross_rango_user 
                    SET id_rango = $rango, entregado = 1 
                    WHERE id_user = $id_usuario";
        $q = $this->db->query($query);
    }

    private function editar_afiliacion($id, $datos,$where = false)
    {
        $datos["id_afiliado"] = $id;

        if($where)
            $datos["where"] = $where;

        return $this->myDataset( "afiliar",$datos,"id_afiliado");
    }

    private function nueva_afiliacion($id, $datos)
    {
        $datos["id_afiliado"] = $id;

        return $this->myDataset( "afiliar",$datos,"id_afiliado",true);
    }

    private function myDataset( $table, $datos, $idtable = "id",$new=false)
    {
        if (! $datos)
            return false;

        $type_query = ($new) ? "insert" : "update";
        $isWhere = isset($datos["where"]);

        if (! $new) {

            $this->db->where($idtable, $datos[$idtable]);

            if ($isWhere) {
                foreach ($datos["where"] as $key => $value) {
                    $this->db->where($key, $value);
                }
                unset($datos["where"]);
            }/* if: $isWhere */
        }/* if: $new */

        try {
            return $this->db->$type_query($table, $datos);
        } catch (Exception $e) {
            log_message('ERROR', "$type_query :: " . json_encode($datos));
            return false;
        }/* try */
    }


    private function renovarCompra($id,$red)
    {   log_message('DEV',"Renovar ($id)[$red]");

        $red_item = $red-1;

        $id_venta = $this->insertVenta($id);
        $monto = $this->insertVentaItem($id, $id_venta,$red_item);
        #@test: 5

        return $red;
    }

    private function descontarBilletera($id, $id_venta, $monto)
    {
        $fecha = date('Y-m-d H:i:s');
        $dato = array(
            "id_user" => $id,
            "tipo" => "SUB",
            "Descripcion" => "Descuento Automático - VENTA # $id_venta",
            "fecha" => $fecha,
            "monto" => $monto
        );

        $this->db->insert('transaccion_billetera', $dato);

        return true;
    }

    private function insertVentaItem($id, $id_venta,$item)
    {
        $query = "INSERT INTO cross_venta_mercancia 
                    SELECT 
                        $id_venta,id,1,costo,0,costo,'',null
                    FROM
                        mercancia
                    WHERE
                    	id = $item";

        $this->db->query($query);

        return $this->getMontoVentaItem($id_venta, $item);
    }

    private function getMontoVentaItem($id_venta, $item)
    {
        $query = "SELECT costo_total FROM cross_venta_mercancia
            		WHERE
                        id_mercancia = $item
                        AND id_venta = $id_venta";

        $q = $this->db->query($query);
        $result = $q->result();

        if(!$result)
            return false;

        $monto = $this->issetVar($result,"costo_total",0);

        return $monto;
    }
    /** ARGS:
     * id:id_usuario f0:fechaInicio f1:fechaFin tp:tipo mc:item WH:where OD:order GP:group
     */
    private function getVentaMercancia($id,$f0,$f1,$tp=false,$mc=false,$WH="",$OD=false,$GP=false)
    {
        if ($tp)
            $WH .= " AND m.id_tipo_mercancia in ($tp)";

        if ($mc)
            $WH .= " AND cvm.id_mercancia in ($mc)";

        if ($GP)
            $GP = "GROUP BY cvm.id_mercancia";
        else
            $GP = "";

        if ($OD)
            $OD = "ORDER BY v.fecha DESC,v.id_venta DESC";
        else
            $OD = "";

        $query = "SELECT *
						FROM
							cross_venta_mercancia cvm,
							mercancia m,
                            items i,
							venta v
						WHERE
                            i.id = m.id
							AND m.id = cvm.id_mercancia
							AND cvm.id_venta = v.id_venta
							$WH
							AND v.id_user in ($id)
							AND v.id_estatus = 'ACT'
							AND v.fecha BETWEEN '$f0' AND '$f1 23:59:59'
						$GP $OD";

        $q = $this->db->query($query);
        $q = $q->result();

        return $q;
    }

    private function insertVenta($id)
    {
        $fecha = date('Y-m-d H:i:s');
        $dato = array(
            "id_user" => $id,
            "id_estatus" => "ACT",
            "id_metodo_pago" => "BANCO",
            "fecha" => $fecha
        );

        $this->db->insert('venta', $dato);
        return $this->db->insert_id();
    }

    function setComision($id,$id_red,$valor,$id_venta)
    {
        $dato = array(
            "id_venta" => $id_venta,
            "id_afiliado" => $id,
            "id_red" => $id_red,
            "puntos" => 0,
            "valor" => $valor
        );

        $this->db->insert("comision",$dato);
    }


    private function setRedCumplida($id_usuario,$id_red)
    {
        $dato = array(
            "estatus" => "DES"
        );

        $this->db->where('id_afiliado', $id_usuario);
        $this->db->where('id_red', $id_red);
        $this->db->update('afiliar', $dato);

        $this->setRedBloqueada($id_usuario, $id_red);
        $this->limpiarAfiliaciones();
    }

    private function getMontoBono($id_usuario,$id_bono,$fechaInicio,$fechaFin)
    {
        $query = "SELECT max(c.valor) valor
                    FROM
                		comision_bono c,
                        comision_bono_historial h
                    WHERE
                		c.id_usuario = $id_usuario
                        AND h.id_bono = c.id_bono
                        AND c.id_bono = $id_bono
                        AND c.id_bono_historial = h.id
                        AND h.fecha between '$fechaInicio' AND '$fechaFin'";

        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q)
            return 0;

        return $this->issetVar($q,"valor",0);
    }

    private function getBonoRedes($id,$nored = 1)
    {
        $query = "SELECT 
                        distinct a.id_red,t.nombre 
                    FROM 
                        afiliar a, tipo_red t
                    WHERE 
                        t.id = a.id_red 
                        AND a.id_afiliado = $id 
                        AND a.id_red > 1 
                        AND a.id_red not in ($nored)";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    function getBonoValorNiveles($id)
    {
        $query = "SELECT * FROM cat_bono_valor_nivel WHERE id_bono = $id ORDER BY nivel asc";
        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    private function getTipoRed($id)
    {
        $q = $this->db->query("SELECT * FROM tipo_red WHERE id = $id");
        $q = $q->result();
        return $q;
    }

    private function getBonos() {
        $q = $this->db->query("SELECT * FROM bono WHERE estatus = 'ACT'");
        $q = $q->result();
        return $q;
    }

    private function getBono($id)
    {
        $q = $this->db->query("SELECT * FROM bono WHERE id = $id");
        $q = $q->result();
        return $q;
    }

    private function getDirectosBy($id,$nivel,$where = "",$red = 1,$negocio =false)
    {

        $query = "SELECT -- imprimir
						distinct a.id_afiliado id,
						a.directo
					FROM
						afiliar a,
						users u
					WHERE
						u.id = a.id_afiliado
						AND a.id_red = $red
						AND a.directo = $id
						 $where";

        $q = $this->db->query($query);
        $datos = $q->result();

        if (! $datos)
            return;

        $nivel --;
        foreach ($datos as $dato) {

            if ($nivel <= 0) {
                $this->setAfiliados($dato->id);
            } else {
                $this->getDirectosBy($dato->id, $nivel, $where, $red);
            }/* if: $nivel */
        }/* foreach: $datos */
    }

    private function getAfiliadosBy ($id,$nivel,$tipo,$where,$padre = false,$red = 1)
    {
        $is = array("DIRECTOS" =>"a.directo","RED"=>"a.debajo_de");

        $query = "SELECT 
						a.id_afiliado id,
						a.directo,a.id rowid
					FROM
						afiliar a,
						users u
					WHERE
						u.id = a.id_afiliado
						AND a.id_red = $red
						AND a.debajo_de = $id
						 $where";

        $q = $this->db->query($query);
        $datos = $q->result();

        if (! $datos)
            return;

        $nivel --;
        foreach ($datos as $dato) {

            if ($nivel <= 0) {

                if ($tipo != "DIRECTOS" || $padre == $dato->directo) {
                    $this->setAfiliados($dato->id);
                }
            } else {
                $this->getAfiliadosBy($dato->id, $nivel, $tipo, $where, $padre, $red);
            }/* if: $nivel */
        }/* foreach: $datos */
    }

    function setFechaformato($fecha=false,$formato=0)
    {
        $f = array('Y-m-d H:i:s','Y-m-d');

        if(!$fecha)
            $fecha = date($f[0]);

        $fecha = strtotime($fecha);

        if(isset($f[$formato]))
            return date($f[$formato],$fecha);

        try {
            return date($formato,$fecha);
        } catch (Exception $e) {
            log_message('DEV',"fail conversion date :: $formato");
            return date($f[1],$fecha);
        }
    }

    function issetVar($var,$type=false,$novar = false){

        $result = isset($var) ? $var : $novar;

        if($type)
            $result = isset($var[0]->$type) ? $var[0]->$type : $novar;

        if(!isset($var[0]->$type))
            log_message('DEV',"issetVar T:($type) :: ".json_encode($var));

        return $result;
    }

    private function getEmpresa($attrib = 0)
    {
        $q = $this->db->query("SELECT * FROM empresa_multinivel GROUP BY id_tributaria");
        $q = $q->result();

        if(!$q){
            return 0;
        }

        if($attrib === 0){
            return $q;
        }

        return $q[0]->$attrib;
    }

    private function getPeriodoFecha ($frecuencia,$tipo,$fecha = '')
    {
        if (! $fecha)
            $fecha = date('Y-m-d');

        $periodoFecha = array(
            "SEM" => "Semana",
            "QUI" => "Quincena",
            "MES" => "Mes",
            "ANO" => "Ano"
        );

        $tipoFecha = array(
            "INI" => "Inicio",
            "FIN" => "Fin"
        );

        if ($frecuencia == "UNI") {
            return ($tipo == "INI") ? $this->getInicioFecha() : date('Y-m-d');
        }

        if (! isset($periodoFecha[$frecuencia]) || ! isset($tipoFecha[$tipo])) {
            return $fecha;
        }

        $functionFecha = "get" . $tipoFecha[$tipo] . $periodoFecha[$frecuencia];

        return $this->$functionFecha($fecha);
    }

    private function getInicioFecha($id = false)
    {
        $query = "SELECT
                        date_format(MIN(created),'%Y-%m-%d') fecha
                    FROM
                        users";

        if($id)$query.=" WHERE id = $id";

        $q = $this->db->query($query);
        $q =$q->result();

        $year = new DateTime();
        $year->setDate($year->format('Y'), 1, 1);

        if(!$q)
            return date_format($year, 'Y-m-d');

        return $this->issetVar($q,"fecha",date('Y-m-d'));
    }

    private function getFinSemana($date)
    {
        $offset = strtotime($date);

        $dayofweek = date('w',$offset);

        if($dayofweek == 6)
            return $date;

        $date = date("Y-m-d", strtotime('last Saturday', strtotime($date)));

        return $date;
    }

    private function getInicioSemana($date)
    {
        $fecha_sub = new DateTime($date);
        date_sub($fecha_sub, date_interval_create_from_date_string('7 days'));
        $date = date_format($fecha_sub, 'Y-m-d');

        $offset = strtotime($date);

        $dayofweek = date('w',$offset);

        if($dayofweek == 0)
            return $date;

        $date = date("Y-m-d", strtotime('last Sunday', strtotime($date)));

        return $date;
    }

    private function getInicioQuincena($date)
    {
        $dateAux = new DateTime();

        $dayTime = $this->setFechaformato($date,"d");
        $monthTime = $this->setFechaformato($date,"m");
        $yearTime = $this->setFechaformato($date,"Y");
        $isHalfMonth = ($dayTime<=15);

        $dayTime = ($isHalfMonth) ? 1 : 16;

        $dateAux->setDate($yearTime,$monthTime,$dayTime);

        $date = date_format($dateAux, 'Y-m-d');

        return $date;
    }

    private function getFinQuincena($date) {

        $dateAux = new DateTime();

        $dayTime = $this->setFechaformato($date,"d");
        $monthTime = $this->setFechaformato($date,"m");
        $yearTime = $this->setFechaformato($date,"Y");
        $isHalfMonth = ($dayTime<=15);

        $date = $this->setFechaformato($date,"Y-m-t");

        if($isHalfMonth){
            $dateAux->setDate($yearTime,$monthTime, 15);
            $date = date_format($dateAux, 'Y-m-d');
        }

        return $date;
    }

    private function getInicioMes($date) {
        $dateAux = new DateTime();
        $monthTime = $this->setFechaformato($date,"m");
        $yearTime = $this->setFechaformato($date,"Y");
        $dateAux->setDate($yearTime,$monthTime, 1);
        return date_format($dateAux, 'Y-m-d');
    }

    private function getFinMes($date) {
        return $this->setFechaformato($date,"Y-m-t");
    }

    private function getInicioAno($date) {
        $year = new DateTime($date);
        $year->setDate($year->format('Y'), 1, 1);
        return date_format($year, 'Y-m-d');
    }

    private function getFinAno($date) {
        $year = new DateTime($date);
        $year->setDate($year->format('Y'), 12, 31);
        return date_format($year, 'Y-m-d');
    }

    private function getAnyTime($date, $time = '1 month',$add= false)
    {
        $fecha_sub = new DateTime($date);
        if($add)
            date_add($fecha_sub, date_interval_create_from_date_string("$time"));
        else
            date_sub($fecha_sub, date_interval_create_from_date_string("$time"));

        $date = date_format($fecha_sub, 'Y-m-d');

        return $date;
    }

    private function getNextTime($date, $time = 'month')
    {
        $fecha_sub = new DateTime($date);
        date_add($fecha_sub, date_interval_create_from_date_string("1 $time"));
        $date = date_format($fecha_sub, 'Y-m-d');

        return $date;
    }

    private function getDiffTime($fecha1, $fecha2,$format = "a")
    {
        $fecha1 = new DateTime($fecha1);
        $fecha2 = new DateTime($fecha2);

        $interval = $fecha1->diff($fecha2);
        $value = $interval->format("%$format");
        return $value;
    }

    private function getLastTime($date, $time = 'month')
    {
        $fecha_sub = new DateTime($date);
        date_sub($fecha_sub, date_interval_create_from_date_string("1 $time"));
        $date = date_format($fecha_sub, 'Y-m-d');

        return $date;
    }

    function reporte_activos ($fechaInicio = "",$fechaFin = "",$id = 2,$status = true)
    {
        $this->setFechaInicio($fechaInicio);
        $this->setFechaFin($fechaFin);

        $red = $this->getTipoRedes();
        $id_red = $this->issetVar($red,"id",1);
        $profundidad = $this->issetVar($red,"profundidad",0);

        $afiliadosEnLaRed=array();
        $afiliadosActivos=array();

        $usuario=new $this->afiliado;
        $usuario->getAfiliadosDebajoDe($id,$id_red,"RED",0,$profundidad);
        $afiliadosEnLaRed = $usuario->getIdAfiliadosRed();

        foreach ($afiliadosEnLaRed as $afiliado){

            $Activado = $this->isActivedAfiliado($afiliado);

            if($Activado==$status){
                $query = "SELECT
							 	a.id,
							 	a.username usuario,
							 	b.nombre nombre,
							 	b.apellido apellido,
							 	a.email
							FROM
								users a,
								user_profiles b
							WHERE
								a.id=b.user_id
								AND b.id_tipo_usuario=2
								AND a.id=$afiliado";
                $q=$this->db->query($query);

                $afiliado=$q->result();
                array_push($afiliadosActivos,$afiliado);
            }/* if: $Activado */
        }/* foreach: $afiliadosEnLaRed */

        return $afiliadosActivos;
    }

    private function getRedes()
    {
        $q = $this->db->query("select id , profundidad from tipo_red where estatus = 'ACT' group by id");
        $redes = $q->result();
        return $redes;
    }

    private function getTipoRedes()
    {
        $q = $this->db->query('SELECT * FROM tipo_red');
        $red = $q->result();
        return $q;
    }

    /** <? TEST ?>
     *	last time : 2017-08-05
     *	recent author : qcmarcel
     *  #TEST: ($parametro){
     */

    private function test() {
        return;
        $where = " AND u.created BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
        foreach ($afiliados[$lvl] as $afiliado){
            $activoAfiliado = $this->isActivedAfiliado($afiliado);
        }
        $this->descontarBilleteraCiclo($id, $id_venta, $monto);
        $negocio = isset($Afiliado[0]->red) ? $Afiliado[0]->red : false;
        if ($negocio==$negocioSponsor) return array($sponsor);
        $isMax = ($negocio<$negocioSponsor);
        $subquery ="AND lider = $padre ";
    }


}
