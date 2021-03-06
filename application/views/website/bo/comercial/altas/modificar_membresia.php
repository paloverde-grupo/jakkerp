<!-- MAIN CONTENT -->
<!DOCTYPE html>
<html>
<?php 
$porcentajeContador=0;
$item = $mercancia[0];
?>
    <div id="content">

        <section id="widget-grid" class="">
            <!-- START ROW -->
            <div class="row">
                <!-- NEW COL START -->
                <article class="col-sm-12 col-md-12 col-lg-12">
                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-colorbutton="false"	>
                        <!-- widget div-->

                        <div class="widget-body">
                            <form class='smart-form' id='update_merc' name='update_merc' method='post' action='/bo/admin/update_mercancia' enctype='multipart/form-data' role="form" >  
                                <h3><center><b>Editar mercancía :: <?= $nombre_merc ?> </b></center></h3>

                                <section class="col col-6" style="display:none;">
                                    <label class="select"> 
                                        <select id="tipo_merc" required name="tipo_merc">
                                            <option value="5">merc</option>
                                        </select>
                                    </label>
                                </section>

                                <section class="col col-6" style="display:none;">
                                    <label class="select"> 
                                        <select id="id_merc" required name="id_merc">
                                            <option value='<?php echo $id_mercancia ?>'>merc</option>
                                        </select>
                                    </label>
                                </section>
									
                                <fieldset>

                                    <legend>Datos de la membresía</legend>
                                    <div id="form_mercancia">
                                        <div class="row">
                                            <fieldset>
												
                                                <section class="col col-2" style="width: 50%;">
                                                    <label class="input">Nombre
                                                        <input required type="text" value='<?php echo $data_merc[0]->nombre ?>' id="nombre_s" name="nombre">
                                                    </label>
                                                </section>

                                                <section class="col col-2" style="width: 50%;">
                                                    <label class="input">caducidad
                                                        <input placeholder="En días" required type="number" value='<?php echo $data_merc[0]->caducidad ?>' id="caducidad" name="caducidad" required>
                                                    </label>
                                                </section>

                                                <section class="col col-12" style="width: 50%;">Categoría
                                                    <label class="select">
                                                        <select name="red">
                                                            <?php
                                                            foreach ($grupos as $key) {

                                                                if ($data_merc[0]->id_red == $key->id_grupo) {
                                                                    ?>
                                                                    <option selected value='<?= $key->id_grupo ?>'>
                                                                    <?= $key->descripcion . " (" . $key->red . ")" ?>
                                                                    </option>
                                                                    <?php } else { ?>
                                                                    <option value='<?= $key->id_grupo ?>'>
                                                                    <?= $key->descripcion . " (" . $key->red . ")" ?>
                                                                    </option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </label>
                                                </section>
                                                <section class="col col-2" style="width: 50%;">
                                                    <label class="input"><span id="labelextra">Descuento de la
                                                            membresía</span> 
                                                        <input required id="precio_promo"  type="number" name="descuento" value='<?= $item->descuento; ?>' required/> 
                                                    </label>
                                                </section>													
														
                                                <div>
                                                    <section style="padding-left: 15px; width: 100%;" class="col col-12">
                                                        Descripcion
                                                        <label class="textarea"> 										
                                                            <textarea name="descripcion" rows="3" class="custom-scroll"><?php echo $data_merc[0]->descripcion ?></textarea> 
                                                        </label>
                                                    </section>


                                                    <section id="imagenes2" class="col col-12">
                                                        <label class="label">
                                                            Imágen actual
                                                        </label>
                                                        <?php
                                                        foreach ($img as $key) {
                                                            echo '<div class="no-padding col-xs-12 col-sm-12 col-md-6 col-lg-6"><img style="max-height: 150px;" src="' . $key[0]->url . '" width="150" height="126"></div>';
                                                        }
                                                        ?>
                                                    </section>

                                                    <section id="imagenes" class="col col-12">
                                                        <label class="label">
                                                            Imágen
                                                        </label>
                                                        <div class="input input-file">
                                                            <span class="button"><input id="img" name="img" 
                                                               onchange="this.parentNode.nextSibling.value = this.value" 
                                                               type="file" multiple>Buscar</span><input id="imagen_mr" 
                                                                     placeholder="Agregar alguna imágen" type="text" >
                                                        </div>
                                                        <small>
                                                            <cite title="Source Title">Para ver el archivo que va a cargar, pulse con el puntero en el boton de "Buscar"</cite>
                                                        </small>
                                                    </section>

                                                </div>

                                            </fieldset>
												
                                            <fieldset id="moneda_field">
                                                <legend>Moneda y país</legend>
													
                                                <section class="col col-2" style="width: 50%;">
                                                    <label class="input">Costo distribuidores
                                                        <input required type="number" value='<?php echo $item->costo ?>' name="costo" id="costo" onchange="calcular_precio_total()">
                                                    </label>
                                                </section>

                                                <section class="col col-3" style="width: 50%;">
                                                    <label class="input">
                                                        Puntos comisionables
                                                        <input type="number" min="0" max="" value='<?= $item->puntos_comisionables ?>' name="puntos_com" id="puntos_com">
                                                    </label>
                                                </section>
                                            </fieldset>
                                            <fieldset id="impuesto_field">
                                                <legend>Impuesto</legend>
                                                <section class="col col-12" style="width: 50%;">País de la mercancía
                                                    <label class="select">
                                                        <select id="pais2" required name="pais" onChange="select_pais()">
                                                            <?php
                                                            foreach ($pais as $key) {
                                                                if ($item->pais == $key->Code) {
                                                                    ?>
                                                                    <option selected value="<?= $key->Code ?>">
                                                                    <?= $key->Name ?>
                                                                    </option>
                                                                    <?php } else { ?>
                                                                    <option value="<?= $key->Code ?>">
                                                                    <?= $key->Name ?>
                                                                    </option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </label>
                                                </section>
                                                <?php $i = 0 ?>
                                                <?php foreach ($impuestos_merc as $merc) {
                                                    ?>	
                                                <section id="impuesto">
                                                        <section class="col col-6" id="<?= $i = $i + 1 ?>">Impuesto
                                                            <label class="select">
                                                                <select name="id_impuesto[]" onchange="calcular_precio_total()">

                                                                        <?php
                                                                        foreach ($impuesto as $key) {
                                                                            if ($key->id_pais == $item->pais) {
                                                                                ?>
                                                                                <?php
                                                                                if ($merc->id_impuesto == $key->id_impuesto) {
                                                                                    ?>
                                                                                    <option selected value='<?php echo $key->id_impuesto ?>' onclick="calcular_precio_total()">
                                                                                    <?php echo $key->descripcion . ' ' . $key->porcentaje . ' % (ACTIVO)' ?>
                                                                                    </option>
                                                                                    <?php
                                                                                    $porcentajeContador += $key->porcentaje;
                                                                                } else {
                                                                                    ?>
                                                                                    <option value='<?php echo $key->id_impuesto ?>' onclick="calcular_precio_total()">
                                                                                    <?php echo $key->descripcion . ' ' . $key->porcentaje . ' %' ?>
                                                                                    </option>
                                                                                        <?php } ?>
                                                                                <?php }
                                                                        }
                                                                        ?>	
                                                                    </select>
                                                                <a class='txt-color-red' onclick="dell_impuesto(<?= $i ?>)" style='cursor: pointer;'>Eliminar <i class="fa fa-minus"></i></a>
                                                                </label>
                                                            </section>
                                                    </section>
                                                <?php }?>
                                            </fieldset>
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-6" style="width: 50%">
                                                        <br>
                                                        <br>
                                                        <a onclick="add_impuesto()" style='cursor: pointer;'>Agregar impuesto<i class="fa fa-plus"></i></a>
                                                    </section>
                                                </div>
                                            </fieldset>
                                        </div>	
                                        <?php  
                                        $valor_total=$item->costo;
                                        $isIVA = ($item->iva=="MAS");+
                                        $isPorcentaje = ($porcentajeContador!=0);
                                        
                                        if($isPorcentaje&&$isIVA)
                                            $valor_total+=(($item->costo*$porcentajeContador)/100);

                                        ?>					
                                        <section class="col col-6">Requiere especificación
                                            <div class="inline-group">
                                                <label class="radio">
                                                    <input type="radio" value="1" name="iva" onchange="calcular_precio_total()" <?php if ($item->iva == "CON") {
                                            echo "checked";
                                        } ?>>
                                                    <i></i>con IVA</label>
                                                <label class="radio">
                                                    <input type="radio" value="0" onchange="calcular_precio_total()" name="iva" <?php if ($item->iva == "MAS") {
                                            echo "checked";
                                        } ?>>
                                                    <i></i>más IVA</label>
                                            </div>
                                        </section>
                                
                                        <div class="row">

                                            <section class="col col-6">
                                                <label class="input">
                                                    Costo distribuidores con IVA
                                                    <input type="text" value='<?= $valor_total; ?>' min="1" max="" name="distribuidores_iva" id="distribuidores_iva" disabled>
                                                </label>
                                            </section>
                                        </div>
                                </fieldset>

                                <fieldset>
                                    <section class="col col-12 pull-right" >
                                        <button type="submit" class="btn btn-success">
                                            Actualizar
                                        </button>
                                    </section>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </article>
            </div>

        </section>
        </div>
<script src="/template/js/plugin/markdown/markdown.min.js"></script>
<script src="/template/js/plugin/markdown/to-markdown.min.js"></script>
<script src="/template/js/plugin/markdown/bootstrap-markdown.min.js"></script>
<script type="text/javascript">
// DO NOT REMOVE : GLOBAL FUNCTIONS!
var i = <?= $i?>;

$(document).ready(function() {
	 
	$('#startdate').datepicker({
		changeMonth: true,
		numberOfMonths: 2,
		dateFormat:"yy-mm-dd",
		changeYear: true,
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		onSelect : function(selectedDate) {
			$('#finishdate').datepicker('option', 'minDate', selectedDate);
		}
	});

	$('#finishdate').datepicker({
		changeMonth: true,
		numberOfMonths: 2,
		dateFormat:"yy-mm-dd",
		changeYear: true,
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		onSelect : function(selectedDate) {
			$('#startdate').datepicker('option', 'maxDate', selectedDate);
		}
	});
	pageSetUp();

})

function add_impuesto()
{
	i=i+1;
	var code=	'<div id="'+i+'"><section class="col col-3" id="impuesto" style="width: 50%;">Impuesto'
	+'<label class="select">'
	+'<select name="id_impuesto[]" onClick="calcular_precio_total()">'
	+'</select>'
	+'</label>'
	+'<a class="txt-color-red" onclick="dell_impuesto('+i+')" style="cursor: pointer;">Eliminar <i class="fa fa-minus"></i></a>'
	+'</section></div>';
	$("#impuesto_field").append(code);
	ImpuestosPais2(i);
	//i = i + 1
}

function dell_impuesto(id)
{	
	$("#"+id+"").remove();
	calcular_precio_total();
	
}
function ImpuestosPais(){
	var pa = $("#pais2").val();
	
	$.ajax({
		type: "POST",
		url: "/bo/mercancia/ImpuestaPais",
		data: {pais: pa}
	})
	.done(function( msg )
	{
		$('#impuesto option').each(function() {
		    
		        $(this).remove();
		    
		});
		datos=$.parseJSON(msg);
	      for(var i in datos){
		      var impuestos = $('#impuesto');
		      $('#impuesto select').each(function() {
				  $(this).append('<option value="'+datos[i]['id_impuesto']+'" onclick="calcular_precio_total()">'+datos[i]['descripcion']+' '+datos[i]['porcentaje']+'</option>');
			    
			});
	    	  
	        
	      }
	});
}

function ImpuestosPais2(id){
	var pa = $("#pais2").val();
	
	$.ajax({
		type: "POST",
		url: "/bo/mercancia/ImpuestaPais",
		data: {pais: pa}
	})
	.done(function( msg )
	{
		$('#'+id+' option').each(function() {
		    
		        $(this).remove();
		    
		});
		datos=$.parseJSON(msg);
	      for(var i in datos){
		      var impuestos = $('#'+id);
		      $('#'+id+' select').each(function() {
				  $(this).append('<option value="'+datos[i]['id_impuesto']+'" onclick="calcular_precio_total()">'+datos[i]['descripcion']+' '+datos[i]['porcentaje']+'</option>');
			    
			});  
	      }
	});
}

function validar_impuesto(){
	var  Impuesto = new Array();
$('select[name="id_impuesto[]"]').each(function() {	
	Impuesto.push($(this).val());
});	
return Impuesto;
}
function validar_tipo_iva(porcentaje, tipo, valor){
	var valor_iva=0;
	valor_iva=((valor)*parseFloat(porcentaje))/(100);
if(tipo=="1"){
	precio_con_iva=valor/*-valor_iva*/;
	return precio_con_iva;
}
if(tipo=="0"){
	precio_con_iva=parseFloat(valor)+valor_iva;
	return precio_con_iva;
}
}


function calcular_porcentaje_total(){
		var  Impuesto=validar_impuesto();
		var resultado=0;
		var porcentaje=0;
		if(Impuesto){
		for(i=0;i<Impuesto.length;i++){
	
	$.ajax({
		async: false,
		type: "POST",
		url: "/bo/mercancia/ImpuestoPaisPorId",
		data: {impuesto: Impuesto[i]}
	})
	.done(function( msg )
	{
		recibir=$.parseJSON(msg);
		porcentaje+=parseInt(recibir[0]["porcentaje"]);
	});
}

return porcentaje;
}else{
	return false;
}
}
function calcular_precio_total(){
var tipo_iva=$("input:radio[name=iva]:checked").val();
var porcentaje=calcular_porcentaje_total();
var Resultado_Final=0;
	var valor_real=$("#real").val();
	var valor_distribuidor=$("#costo").val();
	var valor_publico=$("#costo_publico").val();
	var validar_real=validar_campos_vacios(valor_real);
	var validar_distribuidor=validar_campos_vacios(valor_distribuidor);
	var validar_publico=validar_campos_vacios(valor_publico);
	if(porcentaje!=false || porcentaje==0){
	if(validar_distribuidor==true){
	Resultado_Final=validar_tipo_iva(porcentaje, tipo_iva, valor_distribuidor);
	$("#distribuidores_iva").val(Resultado_Final);
						}
			else{$("#distribuidores_iva").val("falta algun dato");}

	}else{
		//$("#real_iva").val("falta algun dato dato");
		$("#distribuidores_iva").val("falta un dato");
		//$("#publico_iva").val("falta un dato");
	}
}
function validar_campos_vacios(campo){
if(campo=="undefined"){
return false;
}
if(campo==null){
return false;
}
if(campo==""){
return false;
}
return true;
}
function select_pais(){
calcular_precio_total();
ImpuestosPais();	
}

/*$( "#update_merc" ).submit(function( event ) {
	event.preventDefault();
		enviar();
});

function enviar() {
	//iniciarSpinner();
	$.ajax({
						type: "POST",
						url: "/bo/admin/update_mercancia",
						data: $('#update_merc').serialize()
						})
						.done(function( msg ) {

							bootbox.dialog({
						message: "Se ha modificado la membresia.",
						title: 'Felicitaciones',
						buttons: {
							success: {
							label: "Aceptar",
							className: "btn-success",
							callback: function() {
								
								location.href="/bo/comercial/carrito";
								//FinalizarSpinner();
								}
							}
						}
					})
					
						});//fin Done ajax
	
}*/

</script>

