<?php

include(setDir()."/bk/calculo.php");

class autobono
{
	
	public $fechaInicio = '';
	public $fechaFin = '';
	public $afiliados = array();
	
	public $db = array();
    private $remanente = array(0,0);

    function __construct($db){
		$this->db = $db;
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
			
			$this->fechaFin = $value . " 23:59:59";
	}
	
	function getAfiliados() {
		$val = $this->afiliados;
		$this->afiliados = array();
		return $val;
	}
	
	function setAfiliados($afiliados) {
		
		array_push($this->afiliados,$afiliados);
		
	}
	
	/** principal **/
	
	public function calcular(){

		$usuario= new calculo($this->db);
		$afiliados = $usuario->getUsuariosRed();
		
		$reparticion= array();
		
		foreach ($afiliados as $afiliado){
			
			$afiliado = $afiliado["id"];
			
			$calcular = $this->calcularBonos($afiliado);
			
			$reparticion[$afiliado] = $calcular;
			
			#TODO: $this->activos_procedure($afiliado);
		}
		
		return $reparticion;
		
	}
	
	private function getIDBonos()
	{
		$data = "SELECT
                    	   id
                        FROM
                            bono
                        WHERE
                            estatus = 'ACT'";
		
		$result = newQuery($this->db,$data);
		return $result;
	}
	
	
	private function getAfiliadosTodos()
	{
		$data = "SELECT
                    	   id
                        FROM
                            users
                        WHERE
                            id > 1";
		
		$result = newQuery($this->db,$data);
		return $result;
	}
	
	private function calcularBonos($id_usuario){
		
		$bonos = $this->getIDBonos();
		
		$parametro = array("id_usuario" => $id_usuario, "fecha" => $this->getLastDay());
		
		$repartido = array();
		
		foreach ($bonos as $bono){
			$id_bono = $bono["id"];
			$isActived = $this->isActived($id_usuario,$id_bono);
			
			if($isActived){
				$monto = $this->getValorBonoBy($id_bono, $parametro);
				$repartir = $this->repartirBono($id_bono,$id_usuario,$monto);
				$repartido[$id_bono] = $monto;
				
			}
			
		}
		
		return $repartido;
		
	}
	
	private function repartirBono($id_bono, $id_usuario, $valor) {
        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

		$fechaInicio = $this->getPeriodoFecha ( $periodo, "INI", '' );
		$fechaFin = $this->getPeriodoFecha ( $periodo, "FIN", '' );
		
		$historial = $this->getHistorialBono ( $id_bono, $fechaInicio, $fechaFin );
		
		if (! $historial)
			$historial = $this->newHistorialBono ( $id_bono, $fechaInicio, $fechaFin );
		
		if ($valor > 0)
			echo "\n PAGO $id_usuario : $ $valor | OK! \n\n";
		
		$data = "INSERT INTO comision_bono
				(id_usuario,id_bono,id_bono_historial,valor)
				VALUES
				($id_usuario,$id_bono,$historial,$valor)";
		
		newQuery ( $this->db, $data );
		
		// $this->cobrar($id_usuario, $valor, $fechaFin);
		
		return true;
	}
	
	private function cobrar($id_usuario, $monto, $fecha)
	{
		$cuenta_cobro = $this->get_cuenta_banco($id_usuario);
		
		if (! $cuenta_cobro)
			return false;
			
			$cuenta = $cuenta_cobro["cuenta"];
			$titular = $cuenta_cobro["titular"];
			$pais = $cuenta_cobro["pais"];
			$banco = $cuenta_cobro["banco"];
			
			$data = "INSERT INTO cobro
			(id_user,id_metodo,id_estatus,monto,fecha,cuenta,titular,banco,pais)
			VALUES
			($id_usuario,1,3,$monto,'$fecha','$cuenta','$titular','$banco','$pais')";
			
			newQuery($this->db, $data);
			
			return true;
	}
	
	private function get_cuenta_banco($id_usuario)
	{
		$data = "SELECT
		c.*,
		CONCAT(u.nombre, ' ', u.apellido) titular
		FROM
		cross_user_banco c,
		user_profiles u
		WHERE
		c.id_user = u.user_id
		AND u.user_id = $id_usuario
		AND c.estatus = 'ACT'";
		
		$result = newQuery($this->db, $data);
		
		if (! $result)
			return false;
			
			$valid = $result[1];
			
			return $valid;
	}
	
	private function newHistorialBono($id_bono, $fechaInicio, $fechaFin)
	{
		$dia = date('d', strtotime($fechaInicio));
		$mes = date('m', strtotime($fechaInicio));
		$anio = date('Y', strtotime($fechaInicio));
		
		$data = "INSERT INTO comision_bono_historial
		(id_bono,dia,mes,ano,fecha)
		VALUES
		($id_bono,$dia,$mes,$anio,'$fechaFin')";
		
		newQuery($this->db, $data);
		
		$result = $this->getHistorialBono($id_bono, $fechaInicio, $fechaFin);
		
		return $result;
	}
	
	private function getHistorialBono($id_bono, $fechaInicio, $fechaFin)
	{
		$data = "SELECT
		*
		FROM
		comision_bono_historial
		WHERE
		fecha  between '$fechaInicio' and '$fechaFin'
		AND id_bono = $id_bono";
		
		$result = newQuery($this->db, $data);
		
		if (! $result)
			return false;
			
			$historial = $result[1]["id"];
			
			return $historial;
	}
	
	/** preproceso **/

	function isActived ( $id_usuario,$id_bono = 0,$red = 1,$fecha = '' ){

        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

		$this->setFechaInicio($this->getPeriodoFecha($periodo, "INI", $fecha));
		$this->setFechaFin($this->getPeriodoFecha($periodo, "FIN", $fecha));

		$isPaid = ($id_bono == 2) ? false : $this->isPaid($id_usuario,$id_bono);
		
		if($isPaid){
		    echo "\n ISPAID ::: ($id_usuario)[$id_bono] $this->fechaInicio - $this->fechaFin ";
			return false;
		}		
		
		$isActived = $this->isActivedAfiliado($id_usuario,$id_bono);
		
		$isScheduled = ($id_bono == 1) ? false
		: $this->isValidDate($id_usuario,$id_bono,$this->fechaFin) ;

        $valActived = intval($isActived);
        $valPaid = intval($isPaid);
        $valScheduled = intval($isScheduled);
        echo "\n ID : $id_usuario -[$id_bono] PAGADO >> $valPaid | ACTIVO !!  $valActived | AGENDADO :: $valScheduled  \n";
		
		if(!$isActived||!$isScheduled){
			return false;
		}
		
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


    function activos_procedure($id_usuario = 2)
	{	    
	    
	    $fechainicio = $this->getPeriodoFecha("QUI", "INI", '');
	    $fechafin = $this->getPeriodoFecha("QUI", "FIN", '');
	    
	    $condicion = $this->getEmpresa ("puntos_personales");
	    
	    $puntos =  $this->getValoresby($id_usuario, $fechainicio, $fechafin);
	    $this->setCalculoDatos($id_usuario, $puntos, $fechainicio, $fechafin);
	    
	    if($puntos<$condicion){
	        $condicion*=2;
	        $fechainicio = $this->getPeriodoFecha("MES", "INI", '');
	        $fechafin = $this->getPeriodoFecha("MES", "FIN", '');
	        $puntos =  $this->getValoresby($id_usuario, $fechainicio, $fechafin);
	    }
	    
	    $activo = $puntos<$condicion;
	    
	    $this->setRedActivo($id_usuario,$activo); 
	    $this->setCalculoBonos($id_usuario, $fechainicio, $fechafin);
	    
	}
	
	private function setCalculoBonos($id_usuario, $fechainicio, $fechafin)
	{
	    $default = array("tipo"=>1,"item"=>0,"condicion"=>"PUNTOS");
	    $bonos=array($default);
	    
	    foreach ($bonos as $bono){
	        $valor =  $this->getValoresby($id_usuario, $fechainicio, $fechafin,$bono["tipo"],$bono["item"],$bono["condicion"]);
	        $this->setCalculoDatos($id_usuario, $valor, $fechainicio, $fechafin,$bono["tipo"],$bono["item"],$bono["condicion"]);
	    }
	    
	}
	
	private function setCalculoDatos($id_usuario,$valor,$fechainicio, $fechafin,$tipo = 0,$item = 0,$set = "PUNTOS"){
	    
	    newQuery($this->db,"DELETE FROM calculo_bonos
                        where id_afiliado = $id_usuario
                        and tipo = '$tipo' and item = '$item'
                        AND fecha BETWEEN $fechainicio AND CONCAT('$fechafin', ' 23:59:59')
                        AND condicion = '$set'");
	    
	    newQuery($this->db,"INSERT INTO calculo_bonos (id_afiliado,valor,condicion,tipo,item) 
			VALUES ($id_usuario,$valor,'$set','$tipo','$item');");
	    
	}
	
	private function setRedActivo($id_usuario,$estatus = false){
	    
	    $estatus = ($estatus) ? "ACT": "DES";
	    
	    $q = newQuery($this->db," update red set estatus = '$estatus' where id_usuario = $id_usuario");
	    
	}
	
	private function getValoresby($id_usuario, $fechainicio, $fechafin,$tipo = 0,$mercancia = 0,$set = "PUNTOS")
	{
	    $set = ($set == "COSTO") ? "m.costo" : "m.puntos_comisionables";
	    
	    if(!$fechainicio||!$fechafin){
	        $fechainicio = $this->getPeriodoFecha("QUI", "INI", '');
	        $fechafin = $this->getPeriodoFecha("QUI", "FIN", '');
	    }
	    
	    $where = "";
	    
	    if($tipo!=0){
	        $in = (gettype($tipo)=="array") ? implode(",", $tipo) : $tipo;
	        $where.=" AND m.id_tipo_mercancia in ($in)";
	    }
	    
	    if($mercancia!=0){
	        $in = (gettype($mercancia)=="array") ? implode(",", $mercancia) : $mercancia;
	        $where.=" AND m.id in ($in)";
	    }
	    
	    $query = "SELECT ( SELECT
						(CASE WHEN SUM($set * cvm.cantidad)
        				 THEN SUM($set * cvm.cantidad)
        				 ELSE 0 END) cart_val
        				FROM
        				    venta v,
        				    cross_venta_mercancia cvm,
                            mercancia m
        				WHERE
							m.id = cvm.id_mercancia
        				    AND v.id_venta = cvm.id_venta
        				    AND v.id_user in ($id_usuario)
        				    AND v.id_estatus = 'ACT'
        				    AND v.fecha BETWEEN '$fechainicio' AND concat('$fechafin',' 23:59:59') $where)
                            +
                            (SELECT
								 (CASE WHEN SUM($set * p.cantidad)
									THEN SUM($set * p.cantidad) ELSE 0 END)
                                 cedi_val
							FROM
								pos_venta o,
								venta v,
							    pos_venta_item p,
                                mercancia m
							WHERE
								p.id_venta = o.id_venta AND m.id = p.item
								AND o.id_venta = v.id_venta
								AND v.id_user in ($id_usuario)
								AND v.id_estatus = 'ACT'
								AND v.fecha BETWEEN '$fechainicio' AND concat('$fechafin',' 23:59:59') $where)
                                total ";
	    
	    $q = newQuery($this->db,$query);	    	    
	    
	    if(!$q)
	        return 0;
	        
	        return $q[1]["total"];
	}
	
	
	private function isRedActivo($id_usuario = 2)
	{
	    $q = newQuery($this->db,"select * from red where id_usuario = $id_usuario");	   
	    
	    if(!$q)
	        newQuery($this->db,"insert into red values (1,$id_usuario,0,'DES',0)");
	        
	        return true;
	}
	
	function isActivedAfiliado_bk($id_usuario,$red = 1,$fecha = '',$bono = false){
		
		if($id_usuario==2)
			return true;
			
			$puntos = $this->getEmpresa ("puntos_personales");
			$usuario= new calculo($this->db);
			
			#$productos=$this->getComprasUnidades($id_usuario,$this->fechaInicio,$this->fechaFin,1);
			#$lider=$this->getComprasUnidades($id_usuario,$this->fechaInicio,$this->fechaFin,5,8);
			#$intermedia=$this->getComprasUnidades($id_usuario,$this->fechaInicio,$this->fechaFin,5,7);
			
			$fechaInicio= ($this->fechaInicio) ? $this->fechaInicio :$this->getPeriodoFecha("QUI","INI", $fecha);
			$fechaFin= ($this->fechaFin) ? $this->fechaFin : $this->getPeriodoFecha("QUI", "FIN", $fecha );
			$fechaInicio2=$this->getPeriodoFecha("MES", "INI", $fechaFin);
			
			$bonoFecha = ($bono)&&($this->fechaInicio)&&($this->fechaFin) ? true : false;
			
			/*if($bonoFecha){
			 $valor = $usuario->getPuntosTotalesPersonalesView($id_usuario,$red,$fechaInicio,$fechaFin,"0","0","vb_activo");
			 $valorMes = $usuario->getPuntosTotalesPersonalesView($id_usuario,$red,$fechaInicio2,$fechaFin,"0","0","vb_activo_2");
			 }else{ 	*/
			$valor = $usuario->getComprasPersonalesIntervaloDeTiempo($id_usuario,$red,$fechaInicio,$fechaFin,"0","0","PUNTOS");
			$valorMes = $usuario->getComprasPersonalesIntervaloDeTiempo($id_usuario,$red,$fechaInicio2,$fechaFin,"0","0","PUNTOS");
			//}
			
			if(!$bonoFecha){
				$log_act = $fechaInicio." - ".$fechaFin;
				$log_act = "ID : $id_usuario -> ACTIVO { $log_act } : $puntos | $valor | $valorMes ";
				echo $log_act;
			}
			
			$pasaMes = ($puntos*2)<=$valorMes;
			
			$Pasa = ($puntos<=$valor||$pasaMes) ? true : false;
			
			return $Pasa;
	}
	
	private function getComprasUnidades($id_usuario = 2,$fechaInicio,$fechaFin,$tipo = 0,$mercancia = 0,$red = 1){
		
		if(!$fechaInicio||!$fechaFin){
			$fechaInicio = $this->getPeriodoFecha("QUI", "INI", '');
			$fechaFin = $this->getPeriodoFecha("QUI", "FIN", '');
		}
		
		$where = "";
		
		if($tipo!=0){
			$in = (gettype($tipo)=="array") ? implode(",", $tipo) : $tipo;
			$where.=" AND i.id_tipo_mercancia in ($in)";
		}
		
		if($mercancia!=0){
			$in = (gettype($mercancia)=="array") ? implode(",", $mercancia) : $mercancia;
			$where.=" AND i.id in ($in)";
		}
		
		$cart = "SELECT
		(CASE WHEN (cvm.cantidad) THEN SUM(cvm.cantidad) ELSE 0 END) unidades
		FROM
		venta v,
		cross_venta_mercancia cvm,
		items i
		WHERE
		i.id = cvm.id_mercancia
		AND cvm.id_venta = v.id_venta
		AND v.id_user = $id_usuario
		AND i.red = $red
		$where
		AND v.id_estatus = 'ACT'
		AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
		
		$cedi = "SELECT
		(CASE WHEN (cvm.cantidad) THEN SUM(cvm.cantidad) ELSE 0 END) unidades
		FROM
		venta v,
		pos_venta_item cvm,
		items i
		WHERE
		i.id = cvm.item
		AND cvm.id_venta = v.id_venta
		AND v.id_user = $id_usuario
		AND i.red = $red
		$where
		AND v.id_estatus = 'ACT'
		AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
		
		$query = "SELECT ($cart)+($cedi) unidades";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return 0;
			
			return intval($q[1]["unidades"]);
			
	}
	
	private function isPaid($id_usuario,$id_bono){
		
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
                    -- AND c.valor > 0";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return false;
			
			$valid = (sizeof($q)>0) ? true : false;
			
			return $valid;
			
	}
	
	private function isPaidHistorial($id_usuario,$historial){
		
		$query = "SELECT
		*
		FROM
		comision_bono c,
		comision_bono_historial h
		WHERE
		c.id_bono_historial = h.id
		AND c.id_bono = h.id_bono
		AND h.id = $historial
		AND c.id_usuario = $id_usuario
		#AND c.valor > 0";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return false;
			
			$valid = (sizeof($q)>0) ? true : false;
			
			return $valid;
			
	}

    private function isValidDate($id_usuario, $id_bono, $fecha = false, $dia = false)
    {

        $bono = $this->getBono($id_bono);

        $mes_inicio = $bono[1]["mes_desde_afiliacion"];
        $mes_fin = $bono[1]["mes_desde_activacion"];

        if ($mes_inicio <= 0) {
            return true;
        }

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

        $q = newQuery($this->db, $query);


        if (!$q)
            return false;

        $valid = ($q[1]["valid"] == 1) ? true : false;

        return $valid;

    }
	
	private function isScheduled($id_usuario,$id_bono,$fecha = ""){
		
		$bono = $this->getBono($id_bono);
		
		$mes_inicio = $bono[1]["mes_desde_afiliacion"];
		$mes_fin = $bono[1]["mes_desde_activacion"];
		$where = "";
		
		if(strlen($fecha)>2){
			$fecha = "'".$fecha."'";
		}else{
			$fecha = "NOW()";
		}
		
		$limiteInicio = "(CASE WHEN (DATE_FORMAT(fecha,'%d')<16) THEN CONCAT(DATE_FORMAT(fecha,'%Y-%m'),'-15') ELSE LAST_DAY(fecha) END)";
		
		if($mes_inicio>0){
			$where .= "DATE_FORMAT($fecha, '%Y-%m-%d') > ".$limiteInicio;
		}
		
		if($mes_fin>0){
			$mes_fin+=$mes_inicio;
			$where .= "DATE_FORMAT($fecha, '%Y-%m-%d') <= ".$limiteInicio;
		}
		
		$query = "SELECT
		$where 'valid'
		FROM
		venta
		WHERE
		id_estatus = 'ACT'
		AND id_user = ".$id_usuario
		." ORDER BY fecha asc
                    LIMIT 1";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return false;
			
			$valid = ($q[1]["valid"]==1) ? true : false;
			
			return $valid;
			
	}
	
	/** calculo **/

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

        echo ("\n BONO $id_bono between: $fechaInicio - $fechaFin");

        $afiliados = $this->getAfiliadosInicial($valores, $id_usuario, $fechaInicio, $fechaFin);

        $monto = $this->getMontoInicial($valores, $afiliados, $fechaInicio, $fechaFin);

        return $monto;
    }

    private function getAfiliadosInicial($valores, $id, $fechaInicio, $fechaFin)
    {
        $where = ""; #@test: 1

        $afiliados = array();

        foreach ($valores as $nivel) {

            if ($nivel["nivel"] > 0) {

                $this->getDirectosBy($id, $nivel["nivel"], $where);
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
                $per = $valores[$i]["valor"] / 100;
                #@test: 2
                $afiliado = implode(",", $afiliados[$lvl]);
                $venta = $this->getVentaMercancia($afiliado,$fechaInicio,$fechaFin,2,false,$where);
                $valor = 0;
                if($venta)
                    $valor = $venta[1]["puntos_comsionables"];
                $valor*=$per;
                #@test: 3
                echo ("\n A:$afiliado N:$i P:".($per * 100)."% V:$valor S:$monto");
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
        echo ("\n BONO $id_bono between: $fechaInicio - $fechaFin");

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

        $limite = $valores[1]["valor"];

        for($key = 0;$key<$limite; $key++) {

            $nivel = $key + 1;

            $this->getAfiliadosBy($id, $nivel, "RED", $where);
            array_push($afiliados, $this->getAfiliados());


        }/* foreach: $valores */
        echo ("\n afiliados ".json_encode($afiliados));
        return $afiliados;
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
                $Directo = ($Directo[1]["directo"] == $id_usuario) ? $id_user : false;

            $json = json_encode($venta);
            echo ("Directo ($id_user) -->>>> $json | [[ $Directo ]]");

            if(!$venta || !$Directo)
                $Directo = $this->isDirectoLado($id_usuario,$afiliados, $id_user,$fecha);

            echo ("condicion ($id_user)[$key] :: [[ $Directo ]]");

            if(!$Directo)
                return false;

            $lados[$key] = $Directo;

        }

        return array($afiliados,$lados);
    }

    private function getAfiliacion($id, $red = 1)
    {
        $q = newQuery($this->db, "SELECT * FROM afiliar WHERE id_afiliado = $id and id_red = $red");


        return $q;
    }

    private function isDirectoLado($id_usuario , $afiliados, $directo,$fecha = false)
    {
        if (! $fecha)
            $fecha = date ( "Y-m-d" );

        $fechaInicio=$this->getPeriodoFecha("UNI", "INI", '');
        $fechaFin=$fecha;

        $datoid = $this->getAfiliacion($directo,1);
        $lado = $datoid[1]["lado"];

        $mired = $afiliados;
        $directos = false;
        $isdirecto = false;
        $isDirectoCompra = false;
        while(!$isdirecto){

            $islado = false;
            foreach ($mired as $uid){
                $datoid = $this->getAfiliacion($uid,1);
                $milado = $datoid[1]["lado"];
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

    function getGananciaBinario($id_usuario,$afiliados,$valores,$fechaInicio, $fechaFin) {

        $datos = $this->ComprobarBrazos($afiliados);

        if(!$datos)
            return 0;

        list($afiliados,$brazos) = $datos;

        echo ("\n NIVEL 1 : ".json_encode($brazos));
        list($puntos, $ventas) = $this->setPuntosFrontales($id_usuario,$fechaInicio, $fechaFin, $brazos);

        if(!$afiliados)
            return $puntos;

        $uplines =$brazos;

        foreach ($afiliados as $n => $nivel){
            $idx = $n+1;
            echo ("\n NIVEL $idx : ".json_encode($nivel));
            foreach ($nivel as $key => $afiliado){

                $this->setPuntosDerrame($afiliado,$fechaInicio,$fechaFin, $uplines, $puntos, $ventas);

                echo ("\n lados [$key] : ".json_encode($uplines));
            }
            echo ( "VENTAS :::>>> ".json_encode($ventas)."PUNTOS :::>>> ".json_encode($puntos));
        }
        echo ("ventas  : ".json_encode($ventas));

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
            echo ">>> NO CUMPLE CONDICION ($id_usuario)";
            return 0;
        }

        $this->updateRemanente($id_usuario, $debil);

        $per = $valores[2]["valor"] / 100;
        $ganancia = $puntos*$per;

        $regresion = json_encode($this->remanente);
        $extra = "$reporte|$regresion";

        echo ("\n >>> BINARIO -> $puntos * $per V:$extra R:$remanente");

        return array($ganancia,$extra);
    }


    private function setRemanente($id,$remanente,$bono = 2){

        $exist = $this->getRemanente($id, $bono);

        if($exist){
            $where = "id_usuario =  $id";
            $where .= " and id_bono =  $bono";
            $this->updateDatos('comisionPuntosRemanentes',$remanente,$where);
        }else{
            $remanente['id_usuario'] = $id;
            $remanente['id_bono'] = $bono;
            $this->insertDatos('comisionPuntosRemanentes',$remanente);
        }

    }

    private function getBonoRemanente($id,$bono = 2) {

        $q = $this->getRemanente ($id,$bono);

        if (!$q)
            return array (0,0);

        $remanente = array (
            $q[1]["izquierda"],
            $q[1]["derecha"]
        );

        return $remanente;
    }

    private function getRemanente($id,$bono) {
        $q = newQuery($this->db, "SELECT * FROM comisionPuntosRemanentes WHERE id_bono = $bono and id_usuario = $id" );
        
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
        echo ("\n remanente : P:$json_1 V:$json_2");
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

        echo ("\n brazos $nBrazos : ".json_encode($brazos));
        return array($afiliados,$brazos);
    }

    private function isUpline($id,$id_debajo = 2, $red = 1)
    {
        $query = "select * from afiliar 
                    where debajo_de in ($id_debajo)
                      and id_afiliado = $id
                      and id_red = $red ";
        $query = newQuery($this->db,$query);

        $lados = $query;
        return $lados;
    }


    private function setBrazoMenor($puntos)
    {
        $menor = 0;$debil=false;$aplica = false;
        foreach ($puntos as $key => $punto) {
            if ($punto != 0 && !$aplica)
                $aplica=true;
            if ($menor == 0 || $punto < $menor){
                $debil = $key;
                $menor = $punto;
            }
        }
        $json = json_encode($puntos);
        echo ("\n >>> PUNTOS : $json -> $menor");
        return $aplica ? array($menor,$debil) : false;
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
            echo ("\n Frontales : A1:$brazo N:1 V:$valor K:$key");

            $puntos = $this->setValueSeparated($puntos, $key, $valor);
            $ventas = $this->setValueSeparated($ventas, $key, $id_venta);
        }

        return array($puntos, $ventas);
    }

    private function setPuntosDerrame($afiliado,$fechaInicio,$fechaFin,&$uplines, &$puntos, &$ventas)
    {#echo ("\n Venta ($afiliado) : ".json_encode($venta));

        foreach ($uplines as $key => $upline) {
            $isUpline = $this->isUpline($afiliado, $upline);
            if (!$isUpline)
                continue;

            $uplines[$key] .= ",$afiliado";

            $venta = $this->getVentaMercancia($afiliado,$fechaInicio,$fechaFin,2,false);

            if(!$venta)
                continue;

            $valor = $this->issetVar($venta,"puntos_comisionables",0);
            $id_venta = $this->issetVar($venta,"id_venta",1);

            echo ( ">>>! ADD lado[$key] ->> a:$afiliado I:$id_venta V:$valor");

            $puntos = $this->setValueSeparated($puntos, $key, $valor);
            $ventas = $this->setValueSeparated($ventas, $key, $id_venta,true);

        }
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

    private function setScheduled($valores,$afiliados,$fechaInicio,$id_bono=1){
		
		for ($i = 0; $i < sizeof($valores); $i ++) {
			$afiliados_scheduled = array();
			$Corre = isset($afiliados[$i]);
			if ($Corre) {
				foreach ($afiliados[$i] as $afiliado) {
					$isScheduled = $this->isScheduled($afiliado, $id_bono, $fechaInicio);
					if ($isScheduled) {
						#echo  " >>-> isScheduled [$afiliado]  :: " . intval($isScheduled));
						array_push($afiliados_scheduled, $afiliado);
					}
				}
				$afiliados[$i] = $afiliados_scheduled;
			}
		}
		
		return $afiliados;
		
	}
	
	private function setActivedAfiliados($valores,$afiliados,$fecha,$id_bono=1){
		
		for($i = 0;$i<sizeof($valores);$i++){
			$afiliados_actived = array();
			$Corre = isset($afiliados[$i]);
			if ($Corre) {
				foreach ($afiliados[$i] as $afiliado) {
					$activoAfiliado = $this->isActivedAfiliado($afiliado, 1, $fecha, $id_bono);
					if ($activoAfiliado) {
						#echo  " >->> isActived [$afiliado]  :: " . intval($activoAfiliado));
						array_push($afiliados_actived, $afiliado);
					}
				}
				$afiliados[$i] = $afiliados_actived;
			}
		}
		
		return $afiliados;
		
	}

    private function insertDatos($table,$datos){
        $attribs = array();$values=array();

        foreach ($datos as $key => $value){
            array_push($attribs, $key);
            $value = "'$value'";
            array_push($values, $value);
        }

        $query = "INSERT INTO $table (".implode(",", $attribs).")
                        VALUES (".implode(",", $values).")";

        newQuery($this->db,$query);

        return true;
    }

    private function updateDatos($table,$datos,$where = false){

        $values=array();

        foreach ($datos as $key => $value){
            $value = "$key = '$value'";
            array_push($values, $value);
        }

        if($where)
            $where = " WHERE ".$where;

        $query = "UPDATE $table SET ".implode(",", $values).$where;

        newQuery($this->db,$query);

        return true;
    }

    private function getMontoBono($id_usuario,$id_bono,$fechaInicio,$fechaFin){
		$query = "SELECT
		max(c.valor) valor
		FROM
		comision_bono c,
		comision_bono_historial h
		WHERE
		c.id_usuario = $id_usuario
		AND h.id_bono = c.id_bono
		AND c.id_bono = $id_bono
		AND c.id_bono_historial = h.id
		AND h.fecha between '$fechaInicio' and '$fechaFin'";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return 0;
			
			return $q[1]["valor"];
	}
	
	private function getBonoValorNiveles($id) {
		$q = newQuery($this->db, "SELECT * FROM cat_bono_valor_nivel WHERE id_bono = $id ORDER BY nivel asc");
		
		return $q;
	}
	
	private function getBono($id) {
		$q = newQuery($this->db, "SELECT * FROM bono WHERE id = $id");
		
		return $q;
	}
	
	private function getDirectosBy($id,$nivel,$where = "",$red = 1){
		
		$query = "SELECT
		a.id_afiliado id,
		a.directo
		FROM
		afiliar a,
		users u
		WHERE
		u.id = a.id_afiliado
		AND a.id_red = $red
		AND a.directo = $id
		$where";
		
		$q = newQuery($this->db, $query);
		
		$datos= $q;
		
		if(!$q){
			return;
		}
		
		$nivel--;
		foreach ($datos as $dato){
			
			if ($nivel <= 0){
				
				$this->setAfiliados ($dato["id"]);
				
			}else{
				$this->getDirectosBy($dato["id"], $nivel, $where,$red);
			}
		}
		
		
	}
	
	private function getAfiliadosBy ($id,$nivel,$tipo,$where,$padre = 2,$red = 1){
		
		$is = array("DIRECTOS" =>"a.directo","RED"=>"a.debajo_de");
		
		$query = "SELECT
                        a.id_afiliado id,
                        a.directo
                    FROM
                        afiliar a,
                        users u
                    WHERE
                        u.id = a.id_afiliado
                        AND a.id_red = $red
                        AND a.debajo_de = $id
		$where";
		
		$q = newQuery($this->db, $query);
		
		$datos= $q;
		
		if(!$q){
			return;
		}
		
		$nivel--;
		foreach ($datos as $dato){
			
			if ($nivel <= 0){
				
				if($tipo != "DIRECTOS" || $padre == $dato["directo"]){
					$this->setAfiliados ($dato["id"]);
				}
				
			}else{
				$this->getAfiliadosBy($dato["id"], $nivel, $tipo, $where,$padre, $red);
			}
		}
		
		
	}
	
	private function getEmpresa($attrib = 0) {
		
		$q = newQuery($this->db, "SELECT * FROM empresa_multinivel GROUP BY id_tributaria");
		
		
		if(!$q){
			return 0;
		}
		
		if($attrib === 0){
			return $q;
		}
		
		return $q[1][$attrib];
		
	}

    function issetVar($var,$type=false,$novar = false){

        $result = isset($var) ? $var : $novar;

        if($type)
            $result = isset($var[1][$type]) ? $var[1][$type] : $novar;

        if(!isset($var[1][$type]))
            echo ("\n issetVar T:($type) :: ".json_encode($var));

        return $result;
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

        $q = newQuery($this->db,$query);
        

        return $q;
    }

	/** complemento **/
	
	private function getLastDay() {

	    return "2018-11-27";
	    
	    $query = "SELECT
					    DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY),
					            '%Y-%m-%d') fecha";
	    $q = newQuery($this->db,$query);
	    $fecha = $q[1]["fecha"]." 23:59:59";
	    return $fecha;
	    
	}
	
	private function getPeriodoFecha ($frecuencia,$tipo,$fecha = ''){
		
		if(!$fecha)
			$fecha= $this->getLastDay();
			
			$periodoFecha = array(
					"SEM" => "Semana",
					"QUI" => "Quincena",
					"MES" => "Mes",
					"ANO" => "Ano"
			);
			
			$tipoFecha= array(
					"INI" => "Inicio",
					"FIN" => "Fin"
			);
			
			if($frecuencia=="UNI"){
				return  ($tipo == "INI") ? $this->getInicioFecha() : date('Y-m-d');
			}
			
			if(!isset($periodoFecha[$frecuencia])||!isset($tipoFecha[$tipo])){
				return ($tipo == "INI" && $tipo != "DIA") ?  date('Y-m-d',strtotime($fecha)) : $fecha;
			}
			
			$functionFecha = "get".$tipoFecha[$tipo].$periodoFecha[$frecuencia];
			
			return $this->$functionFecha($fecha);
			
	}
	
	private function getInicioFecha() {
		
		$query = "SELECT
                        date_format(MIN(created),'%Y-%m-%d') fecha
                    FROM
                        users";
		
		$q = newQuery($this->db, $query);
		
		
		$year = new DateTime();
		$year->setDate($year->format('Y'), 1, 1);
		
		if(!$q)
			date_format($year, 'Y-m-d');
			
			return $q[1]["fecha"];
			
	}
	
	private function getFinSemana($date) {
		$offset = strtotime($date);
		
		$dayofweek = date('w',$offset);
		
		if($dayofweek == 6){
			return $date;
		}
		else{
			return date("Y-m-d", strtotime('last Saturday', strtotime($date)));
		}
	}
	
	private function getInicioSemana($date) {
		
		$fecha_sub = new DateTime($date);
		date_sub($fecha_sub, date_interval_create_from_date_string('6 days'));
		$date = date_format($fecha_sub, 'Y-m-d');
		
		$offset = strtotime($date);
		
		$dayofweek = date('w',$offset);
		
		if($dayofweek == 0)
		{
			return $date;
		}
		else{
			return date("Y-m-d", strtotime('last Sunday', strtotime($date)));
		}
	}
	
	private function getInicioQuincena($date) {
		$dateAux = new DateTime();
		
		if(date('d',strtotime($date))<=15){
			$dateAux->setDate(date('Y',strtotime($date)),date('m',strtotime($date)), 1);
			return date_format($dateAux, 'Y-m-d');
		}else {
			$dateAux->setDate(date('Y',strtotime($date)),date('m',strtotime($date)), 16);
			return date_format($dateAux, 'Y-m-d');
		}
	}
	
	private function getFinQuincena($date) {
		
		$dateAux = new DateTime();
		
		if(date('d',strtotime($date))<=15){
			$dateAux->setDate(date('Y',strtotime($date)),date('m',strtotime($date)), 15);
			return date_format($dateAux, 'Y-m-d');
		}else {
			return date('Y-m-t',strtotime($date));
		}
	}
	
	private function getInicioMes($date) {
		$dateAux = new DateTime();
		$dateAux->setDate(date('Y',strtotime($date)),date('m',strtotime($date)), 1);
		return date_format($dateAux, 'Y-m-d');
	}
	
	private function getFinMes($date) {
		return date('Y-m-t',strtotime($date));
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
	
	
}