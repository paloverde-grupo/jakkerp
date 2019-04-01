-- condicion bonos

INSERT INTO cross_rango_tipos
(id_rango, id_tipo_rango, valor, condicion_red, nivel_red)
VALUES (1, 5, 1, 'RED', 0);

UPDATE cat_rango
SET nombre = 'Puntos',
descripcion = 'Puntos de la red',
condicion_red_afilacion = 'EQU'
WHERE id_rango =  '1';

-- bono directos

 INSERT INTO bono
 (nombre, descripcion, inicio, fin,
 mes_desde_afiliacion, mes_desde_activacion,
  frecuencia, plan, estatus)
  VALUES ('Directos', 'Bono directos', '2018-01-01', '2029-01-31',
  '0', '0', 'DIA', 'NO', 'ACT');

 INSERT INTO cat_bono_valor_nivel
 (id_bono, nivel, valor, condicion_red, verticalidad)
 VALUES ('1', '0', '0', 'RED', 'PASC'), ('1', '1', '5', 'DIRECTOS', 'PASC');

 INSERT INTO cat_bono_condicion
 (id_bono, id_rango, id_tipo_rango, condicion_rango,
 id_red, condicion1, condicion2, calificado)
  VALUES ('1', '1', '5', '1', '1', '2', '0', 'REC');

-- bno binario

 INSERT INTO bono
 (nombre, descripcion, inicio, fin, mes_desde_afiliacion,
 mes_desde_activacion, frecuencia, plan, estatus)
 VALUES ('Binario', 'Lado menor de brazos. remanentes y lateralidad',
  '2018-01-01', '2029-01-31', '0', '0', 'DIA', 'NO', 'ACT');

 INSERT INTO cat_bono_valor_nivel
  (id_bono, nivel, valor, condicion_red, verticalidad)
  VALUES ('2', '0', '5', 'RED', 'PASC'),('2', '1', '10', 'RED', 'PASC');

 INSERT INTO cat_bono_condicion
 (id_bono, id_rango, id_tipo_rango, condicion_rango,
  id_red, condicion1, condicion2, calificado)
  VALUES ('2', '1', '5', '1', '1', '2', '0', 'REC');

  -- bono inversion

 INSERT INTO bono
 (nombre, descripcion, inicio, fin,
 mes_desde_afiliacion, mes_desde_activacion,
 frecuencia, plan, estatus)
  VALUES ('Inversion', 'Inversion:  bono pasivo q depende los parametros en la mercancia : Servicios.\r\n- valor (puntos)\r\n- meses (x4) \r\n- porcentaje (nivel)',
  '2018-01-01', '2029-01-31', '0', '0', 'MES', 'NO', 'ACT');

 INSERT INTO cat_bono_valor_nivel
 (id_bono, nivel, valor, condicion_red, verticalidad)
 VALUES ('3', '0', '4', 'RED', 'PASC'),('3', '1', '41', 'RED', 'PASC'),
  ('3', '2', '90', 'RED', 'PASC'), ('3', '3', '150', 'RED', 'PASC');

 INSERT INTO cat_bono_condicion
 (id_bono, id_rango, id_tipo_rango, condicion_rango,
  id_red, condicion1, condicion2, calificado)
  VALUES ('3', '1', '5', '1', '1', '2', '0', 'REC');

  -- compra inversion

  ALTER TABLE billetera
  ADD inversion int DEFAULT 0 NULL COMMENT 'id de nivel bono 3, 0 es ninguno';

  -- ventas en bonos

  ALTER TABLE comision_bono
  ADD extra varchar(150) DEFAULT '' NULL COMMENT '[id_venta : puntos, ...] ';

  -- remanentes

ALTER TABLE comisionPuntosRemanentes
MODIFY id_bono int(11) NOT NULL DEFAULT 2 COMMENT '2 es Bono Binario',
MODIFY id_bono_historial int(11) NULL DEFAULT 1 COMMENT 'actualizacion reciente',
CHANGE total izquierda VARCHAR(255) NULL DEFAULT '' COMMENT '[id_venta : puntos, ...]',
CHANGE id_pata derecha VARCHAR(255) NULL DEFAULT '' COMMENT '[id_venta : puntos, ...]',
MODIFY fecha timestamp DEFAULT current_timestamp;