<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 1/12/2018
 * Time: 1:38 AM
 */

?>
<div id="spinner-div"></div><div class="row fuelux">
		<div id="myWizard" class="wizard wizard_r">
			<ul class="steps">
				<li data-target="#step1_r" class="active">
					<span class="badge badge-info">1</span>Datos de registro<span class="chevron"></span>
				</li>
				<li data-target="#step2_r">
					<span class="badge">2</span>Datos personales<span class="chevron"></span>
				</li>
				
			</ul>
			<div id="acciones_r" class="actions">
				<button type="button" class="final btn btn-sm btn-primary btn-prev">
					<i class="fa fa-arrow-left"></i>Anterior
				</button>
				<button type="button" class="final btn btn-sm btn-success btn-next" data-last="Afiliar" disabled="disabled">
					Siguiente<i class="fa fa-arrow-right"></i>
				</button>
			</div>
		</div>
		<div class="step-content">
			<div class="form-horizontal" id="fuelux-wizard" >
				<div class="step-pane active" id="step1_r">
					<form id="register" class="smart-form">
						<fieldset>
							<legend>Información de cuenta</legend>
							<section id="usuario" class="col col-6">
								<label class="input"><i class="icon-prepend fa fa-user"></i>
								<input id="username" onkeyup="use_username()" required="" name="username" placeholder="Usuario" type="text">
								</label>
							</section>
							<section id="correo" class="col col-6">
								<label class="input"><i class="icon-prepend fa fa-envelope-o"></i>
								<input id="email" onkeyup="use_mail()" required="" name="email" placeholder="Dirección de Correo Electrónico" type="email">
							</label></section>
								<section class="col col-6">
								<label class="input"><i class="icon-prepend fa fa-lock"></i>
								<input id="password" onkeyup="confirm_pass()" required type="password" name="password" placeholder="Contraseña">
								</label>
							</section>
							<section id="confirmar_password" class="col col-6">
								<label class="input"><i class="icon-prepend fa fa-lock"></i>
									<input id="confirm_password" onkeyup="confirm_pass()" required type="password" name="confirm_password" placeholder="Confirme contraseña">
								</label>
							</section>
						</fieldset>
					</form>
				</div>
				<div class="step-pane" id="step2_r">
					<form method="POST" action="/perfil_red/afiliar_nuevo_r/<?=$id;?>" id="afiliar_red" class="smart-form" novalidate="novalidate">
						<fieldset>
							<legend>Datos personales del afiliado</legend>
                            <input required type="hidden" id="id" name="afiliados" value="<?=$id;?>">
                            <input id="lado" required type="hidden" name="lado" value="<?=$lado+1;?>">
							<div class="row">
								<section class="col col-6">
									<label class="input"><i class="icon-prepend fa fa-user"></i>
									<input id="nombre" required type="text" name="nombre" placeholder="Nombre(s)">

									<input type="hidden" name="tipo_plan" id="tipo_plan_r">
									</label>
								</section>
								<section class="col col-6">
									<label class="input"><i class="icon-prepend fa fa-user"></i>
									<input id="apellido" required type="text" name="apellido" placeholder="Apellidos (paterno y/o materno)">
									</label>
								</section>
								<section class="col col-6">
									<label class="input"><i class="icon-append fa fa-calendar"></i>
									<input required id="datepicker" type="date"  name="nacimiento" placeholder="Fecha de nacimiento" readonly="readonly">
									</label>
								</section>
								<section class="col col-6" id="key_red">
									<label id="key_2" class="input"> <i class="icon-prepend fa fa-barcode"></i>
										<input id="keyword_red" onkeyup="check_keyword_red()" placeholder="Identificación (IFE,CURP,RFC)" type="text" name="keyword">
									</label>
								</section>
							</div>

							<div class="row">
								<div id="tel_red" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<section class="col col-6">
										<label class="input"><i class="icon-prepend fa fa-phone"></i>
											<input required name="fijo[]" placeholder="(99) 99-99-99-99" type="tel">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"><i class="icon-prepend fa fa-mobile"></i>
											<input required name="movil[]" placeholder="(999) 99-99-99-99-99" type="tel">
										</label>
									</section>
								</div>
								<div id="" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<section class="col col-12">
									<button type="button" onclick="agregar_red(1)" class="btn btn-primary">&nbsp;Agregar <i class="fa fa-mobile"></i>&nbsp;</button>&nbsp;
									<button type="button" onclick="agregar_red(2)" class="btn btn-primary">&nbsp;Agregar <i class="fa fa-phone"></i>&nbsp;</button>
								</section>
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>Datos del Beneficiario</legend>
							<div class="row">
								<section class="col col-4">
									<label class="input">
										<input placeholder="Nombre" type="text" name="nombre_co">
									</label>
								</section>
								<section class="col col-4">
									<label class="input">
										<input placeholder="Apellido" type="text" name="apellido_co">
									</label>
								</section>
								<section class="col col-4" id="key_red_co">
									<label id="key_3" class="input"> <i class=" icon-prepend fa fa-users"></i>
										<!-- check_keyword_red_co() --><input onkeyup="" placeholder="Parentesco" type="text" name="keyword_co" id="keyword_red_co">
									</label>
								</section>
							</div>
						</fieldset>
						<fieldset>
							<legend>Configuración del afiliado</legend>
								<section class="col col-12">
									<label class="toggle">
									<input type="checkbox" checked="checked" name="sponsor">
									<i data-swchoff-text="No" data-swchon-text="Si"></i>Soy su sponsor</label>
									<small>Si eres el sponsor de este afiliado,
                                        estará como frontal en tus comisiones</small>
								</section>
						</fieldset>
						<fieldset>
							<legend>Dirección del afiliado</legend>
							<div id="dir_red" class="row">
								<section class="col col-6">País
									<label class="select">
										<select id="pais_red" required name="pais"><?foreach ($pais as $key){?>
											<option value="<?=$key->Code?>"><?=$key->Name?></option><?}?>
										</select>
									</label>
								</section>
								<section id="municipio" class="col col-6">
									<label class="input">
									Ciudad - Estado
										<input type="text" name="estado" >
									</label>
								</section>
								<section id="municipio_red" class="col col-6">
									<label class="input">Delegación o Municipio
									<input type="text" name="municipio" >
									</label>
								</section>

								<section id="colonia_red" class="col col-6">
									<label class="input">Colonia
									<input type="text" name="colonia" >
									</label>
								</section>

								<section class="col col-6">
									<label class="input">Dirección de domicilio
									<input required type="text" name="calle">
									(Calle,No. Exterior,No. Interior)
									</label>
								</section>
								
								<section class="col col-6">
									<label class="input">Código postal
										<input required type="text" id="cp_red" name="cp">
									</label>
								</section>
								
							</div>
						</fieldset>
						<fieldset>
							<legend>Estadistica</legend>
							<div class="row">
								<section class="col col-6">Estado civil
									<label class="select">
									<select name="civil"><?foreach ($civil as $key){?>
									<option value="<?=$key->id_edo_civil?>"><?=$key->descripcion?></option><?}?>
									</select>
									</label>
								</section>
								<section class="col col-6">Género&nbsp;
									<div class="inline-group"><?foreach ($sexo as $value){?>
									<label class="radio">
									<input <?=($value->id_sexo==1) ? "checked" : " " ?> type="radio" value="<?=$value->id_sexo?>" name="sexo" placeholder="sexo"><i></i><?=$value->descripcion?>
									</label><?}?>
									</div>
								</section>
								<section class="col col-12">Estudio&nbsp;
									<div class="inline-group"><?foreach ($estudios as $value){?>
									<label class="radio">
									<input <?=($value->id_estudio==1) ? "checked" : " " ?> type="radio" value="<?=$value->id_estudio?>" name="estudios"><i></i><?=$value->descripcion?>
									</label><?}?>
									</div>
								</section>
								<section class="col col-6">Ocupación&nbsp;
									<div class="inline-group"><?foreach ($ocupacion as $value){?>
									<label class="radio">
									<input <?=($value->id_ocupacion==1) ? "checked" : " " ?> type="radio" value="<?=$value->id_ocupacion?>" name="ocupacion"><i></i><?=$value->descripcion?>
									</label><?}?>
									</div>
								</section>
								<section class="col col-6">Tiempo dedicado&nbsp;
									<div class="inline-group"><?foreach ($tiempo_dedicado as $value){?>
									<label class="radio">
									<input <?=($value->id_tiempo_dedicado==1) ? "checked" : " " ?> type="radio" value="<?=$value->id_tiempo_dedicado?>" name="tiempo_dedicado"><i></i><?=$value->descripcion?>
									</label><?}?>
									</div>
								</section>
							</div>
						</fieldset>
						<input class="hide" type="text" name="red" id="red" value="<?php echo $id_red; ?>" placeholder="">
						<input type="text" class="hide" name="id" value="<?php echo $id; ?>" placeholder="">
					</form>
				</div>


			</div>
		</div>
		</div>
<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script src="/template/js/plugin/fuelux/wizard/wizard.min.js"></script>
<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        $('.wizard_r').on('finished.fu.wizard', function (e, data) {

            $(".invalid").remove();

            var ids = new Array(
                "#nombre",
                "#apellido",
                "#datepicker",
                "#keyword_red",
                "#username",
                "#email",
                "#password",
                "#confirm_password"
            );
            var mensajes = new Array(
                "Por favor ingresa tu nombre",
                "Por favor ingresa tu apellido",
                "Por favor ingresa tu fecha de nacimiento",
                "Por favor ingresa tu Identificación",
                "Por favor ingresa un nombre de usuario",
                "Por favor ingresa un correo",
                "Por favor ingresa una contraseña",
                "Por favor confirma tu contraseña"
            );

            var idss = new Array(
                "#username"
            );
            var mensajess = new Array(
                "El nombre de usuario no puede contener espacios en blanco"
            );
            var validacion_ = valida_espacios(idss, mensajess);
            var validacion = valida_vacios(ids, mensajes);
            if (validacion && validacion_) {
                setiniciarSpinner();
                $('.btn-next').attr('disabled', 'disabled');
                $('.btn-prev').attr('disabled', 'disabled');
                var id = $("#id").val();
                $.ajax({
                    url: "/auth/register",
                    data: $("#register").serialize(),
                    type: "POST"
                }).done(function (msg1) {
                    //var email = $("#email").val();
                    //$("#afiliar_red").append("<input value='" + email + "' type='hidden' name='mail_important'>");
                    var use_important = $("#username").val();
                    $("#afiliar_red").append("<input value='" + use_important + "' type='hidden' name='use_important'>");
                    $.ajax({
                        url: "/ov/perfil_red/afiliar_nuevo",
                        data: $("#afiliar_red").serialize(),
                        type: "POST"
                    }).done(function (msg) {
                        bootbox.dialog({
                            message: msg,
                            title: "Atención",
                            buttons: {
                                success: {
                                    label: "Ok!",
                                    className: "btn-success",
                                    callback: function () {
                                        location.href = "";
                                        FinalizarSpinner();
                                    }
                                }
                            }
                        });

                    });//Fin ajax Profile
                });//Fin ajax register

            }//Fin ajaxs
            else {
                $.smallBox({
                    title: "<h1>Atención</h1>",
                    content: "<h3>Por favor revisa que todos los datos estén correctos</h3>",
                    color: "#C46A69",
                    icon: "fa fa-warning fadeInLeft animated",
                    timeout: 4000
                });
            }

        });
    });

    function use_username() {
        $('#username').val($('#username').val().replace(" ", ""));
        var username = $("#username").val();
        $.ajax({
            type: "POST",
            url: "/ov/perfil_red/use_username",
            data: {username: username},
        })
            .done(function (msg) {
                $("#msg_usuario").remove();
                $("#usuario").append("<div id='msg_usuario'>" + msg + "</div>")
            });
        validate_user_data();
    }

    function use_mail() {
        var mail = $("#email").val();
        $.ajax({
            type: "POST",
            url: "/ov/perfil_red/use_mail",
            data: {mail: mail},
        })
            .done(function (msg) {
                $("#msg_correo").remove();
                $("#correo").append("<div id='msg_correo'>" + msg + "</div>")
            });
        validate_user_data()
    }


    function confirm_pass() {
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        $.ajax({
            type: "POST",
            url: "/ov/perfil_red/confirm_password",
            data: {password: password, confirm_password: confirm_password},
        })
            .done(function (msg) {
                $("#msg_confirm_password").remove();
                $("#confirmar_password").append("<div id='msg_confirm_password'>" + msg + "</div>")
            });
        validate_user_data()
    }

    function validate_user_data() {
        var username = $("#username").val();
        var mail = $("#email").val();

        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();

        $("#validate_user_data").remove();

        $.ajax({
            type: "POST",
            url: "/ov/perfil_red/validate_user_data",
            data: {mail: mail, username: username, password: password, confirm_password: confirm_password},
        })
            .done(function (msg) {
                $("#validate_user_data").remove();
                $("#register").append("<div id='validate_user_data'>" + msg + "</div>")
            });
    }

    var id = 0;

    function agregar_red(tipo) {
        if (tipo == 1) {
            $("#tel_red").append("<section id='tel_red_" + id + "' class='col col-3'><label class='input'> <i class='icon-prepend fa fa-mobile'></i><input type='tel' name='movil[]' placeholder='(999) 99-99-99-99-99'></label><a style='cursor: pointer;color: red;' onclick='delete_telefono(" + id + ")'>Eliminar <i class='fa fa-minus'></i></a></section>");
        }
        else {
            $("#tel_red").append("<section id='tel_red_" + id + "' class='col col-3'><label class='input'> <i class='icon-prepend fa fa-phone'></i><input type='tel' name='fijo[]' placeholder='(999) 99-99-99-99-99'></label><a style='cursor: pointer;color: red;' onclick='delete_telefono(" + id + ")'>Eliminar <i class='fa fa-minus'></i></a></section>");
        }

        id++;
    }

    function delete_telefono(id) {
        $("#tel_red_" + id + "").remove();
    }

    function check_keyword_red() {
        $("#msg_key_red").remove();
        var keyword = $("#keyword_red").val();
        $("#key_2").append('<i id="ajax_2" class="icon-append fa fa-spinner fa-spin"></i>');
        $.ajax({
            type: "POST",
            url: "/ov/perfil_red/use_keyword",
            data: {keyword: keyword},
        })
            .done(function (msg) {
                $("#key_red").append("<p id='msg_key_red'>" + msg + "</msg>");
                $("#ajax_2").remove();
            });
    }

    $(function () {
        a = new Date();
        año = a.getFullYear() - 19;
        $("#datepicker").datepicker({
            changeMonth: true,
            numberOfMonths: 2,
            maxDate: año + "-12-31",
            dateFormat: "yy-mm-dd",
            changeYear: true
        });
    });

</script>