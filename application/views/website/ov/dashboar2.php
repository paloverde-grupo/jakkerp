<style>
    /* inicio estilos menu */
    #menu {
        background-color: #18355d;
        /* width: 20%; */
        /* display: inline-block; */
        color: #ffffff;
        display: inline-block;
        /* position: absolute; */
    }

    #logo_menu {
        text-align: center;
    }

    #logo_menu a {
        text-decoration: none;
    }

    #logo_menu a img {
        width: 85%;
        margin: 0 auto;
    }

    #opciones_menu {
        list-style:none;
        padding: 0px;
    }

    #opciones_menu .opcion_menu {
        padding: 20px 0 20px 15px;
        font-size: 18px;
        cursor: pointer;
    }

    #loggout {
        position: absolute;
        bottom: 0px;
        left: 30px;
    }

    #loggout p {
        text-align: center;
    }

    #opciones_menu .opcion_menu:hover {
        background-color: #ffffff;
        color: #18355d;
    }
    
    .subopciones_menu {
        list-style:none;
        padding: 0px;
        position: absolute;
        background-color: #18355d;
        z-index: 9;
    }

    .subopciones_menu .subopcion_menu {
        font-size: 13px;
        cursor: pointer;
    }

    .subopciones_menu .subopcion_menu a {
        text-decoration: none;
        width: 150px;
        background-color: #18355d;
        color: #ffffff;
        padding: 10px 20px;
        display: block;
    }

    .subopciones_menu .subopcion_menu a:hover {
        color: #18355d;
        background-color: #ffffff;
    }

    #opciones_menu .opcion_menu:hover .subopciones_menu .subopcion_menu{
        background-color: #18355d;
        color: #ffffff;
    }
    /* fin estilos menu */

    /* inicio estilos genericos */
    #pantalla{
        /* width: 79%; */
        background-color: #ffffff;
        /* display: inline-block; */
    }

    .icon1{
        width: 25px;
    }

    .icon2 {
        width: 70px;
    }

    /* #encabezado_pantalla{
        padding: 15px 30px;
    } */

    #titulo_pantalla{
        width: 35%;
        display: inline-block;
    }

    #opciones_pantalla{
        width: 64%;
        display: inline-block;
        text-align: right;
    }
    .opcion_pantalla{
        margin: 7px;
    }

    #opciones_pantalla #busqueda_pantalla{
        width: 50%;
        display: inline-block;
    }

    #opciones_pantalla #busqueda_pantalla #texto_busqueda input{
        border: 1px solid #000000;
        border-radius: 6px;
        height: 30px;
        background-color: lightgray;
    }

    #opciones_pantalla #busqueda_pantalla #texto_busqueda, #opciones_pantalla #busqueda_pantalla #icono_busqueda{
        display: inline-block;
    }

    #opciones_pantalla #mensajero_pantalla{
        display: inline-block;
    }

    #opciones_pantalla #campana_pantalla{
        display: inline-block;
    }

    #opciones_pantalla #usuario_pantalla{
        display: inline-block;
    }

    /* fin estilos genericos */

    /* inicio estilos componente */
    #descripciones_economicas .descipcion_economica{
        width: 20%;
        display: inline-block;
        margin: 10px 20px;
        border: 1px solid #000000;
    }

    #grafica_ganancias{
        /* width: 55%; */
        display: inline-block;
        padding-top: 60px;
    }

    #grafica_semanal{
        /* width: 44%; */
        display: inline-block;
        text-align: center;
    }

    /* fin estilos componente */

</style>

<div class="container-fluid">
    <div class="row">

    <!-- <div id="contenedor_dashboard"> -->
        <div id="menu" class="col-sm-3">
            <div id="logo_menu">
                <a href="/">
                    <img src="/logo.png" alt="Networksoft">
                </a>
            </div>
            <ul id="opciones_menu">
                <li class="opcion_menu">
                    Perfil y Red
                    <ul class="subopciones_menu">
                        <li class="subopcion_menu">
                            <a href="/">
                                Perfil
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Foto
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Afiliar
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Redes
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="opcion_menu">
                    Compras y Comisiones
                    <ul class="subopciones_menu">
                        <li class="subopcion_menu">
                            <a href="/">
                                Carrito
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Estado de cuenta
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Billetera
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Reportes
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="opcion_menu">
                    General
                    <ul class="subopciones_menu">
                        <li class="subopcion_menu">
                            <a href="/">
                                Encuestas
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Archivero
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Tablero
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Compartir
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="opcion_menu">
                    Comunicacion
                    <ul class="subopciones_menu">
                        <li class="subopcion_menu">
                            <a href="/">
                                WEB personal
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Chat mi Red
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Email
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Soporte tecnico
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Sugerencias
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="opcion_menu">
                    Informacion y Capacitacion
                    <ul class="subopciones_menu">
                        <li class="subopcion_menu">
                            <a href="/">
                                Informacion
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                presentaciones
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                E-books
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Descargas
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Bonos
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Eventos
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Noticias
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Videos
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Resconocimientos
                            </a>
                        </li>
                        <li class="subopcion_menu">
                            <a href="/">
                                Estadisticas
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div id="loggout"><p>cerrar session</p></div>
        </div>
        <div id="pantalla" class="col-sm-9">
            <div id="encabezado_pantalla">
                <div id="titulo_pantalla">
                    <p>Inicio</p>
                </div>
                <div id="opciones_pantalla">
                    <div id="busqueda_pantalla" class="opcion_pantalla">
                        <div id="texto_busqueda">
                            <input type="text">
                        </div>
                        <div id="icono_busqueda">
                            <span>
                                <img class="icon1" src="/search.png" alt="">
                            </span>
                        </div>
                    </div>
                    <div id="mensajero_pantalla" class="opcion_pantalla">
                        <span>
                            <a href="/">
                                <img class="icon1" src="/message-closed-envelope.png" alt="">
                            </a>
                        </span>
                    </div>
                    <div id="campana_pantalla" class="opcion_pantalla">
                        <span>
                            <a href="/">
                                <img class="icon1" src="/alarm.png" alt="">
                            </a>
                        </span>
                    </div>
                    <div id="usuario_pantalla" class="opcion_pantalla">
                        <span>
                            <a href="/">
                                <img class="icon2" src="/user.png" alt="">
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <div id="dashboard" class="col-sm-12">
                <div id="descripciones_economicas">
                    <div id="ganancias_totales" class="descipcion_economica">
                        <div class="icon_descripcion">
                            <span>
                                <img src="/dollar.png" class="icon1" alt="">
                            </span>
                        </div>
                        <div class="informacion_economica">
                            <div class="cantidad_texto">
                                <p>
                                    $ 24300
                                </p>
                            </div>
                            <div class="texto_economica">
                                <p>
                                    total earings
                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="visitantes_hoy" class="descipcion_economica">
                        <div class="icon_descripcion">
                            <span>
                                <img src="/pulse-line.png" class="icon1" alt="">
                            </span>
                        </div>
                        <div class="informacion_economica">
                            <div class="cantidad_texto">
                                <p>
                                    17212
                                </p>
                            </div>
                            <div class="texto_economica">
                                <p>visitors today</p>
                            </div>
                        </div>
                    </div>
                    <div id="ventas_hoy" class="descipcion_economica">
                        <div class="icon_descripcion">
                            <span>
                                <img src="/cart.png" class="icon1" alt="">
                            </span>
                        </div>
                        <div class="informacion_economica">
                            <div class="cantidad_texto">
                                <p>
                                    2525
                                </p>
                            </div>
                            <div class="texto_economica">
                                <p>Sales today</p>
                            </div>
                        </div>
                    </div>
                    <div id="ingresos_totales" class="descipcion_economica">
                        <div class="icon_descripcion">
                            <span>
                                <img src="/dollar.png" class="icon1" alt="">
                            </span>
                        </div>
                        <div class="informacion_economica">
                            <div class="cantidad_texto">
                                <p>
                                    $18700
                                </p>
                            </div>
                            <div class="texto_economica">
                                <p>Total revenue</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
            
                    <div id="grafica_ganancias" class="col-sm-8">
                        <div id="myfirstchart" style="height: 250px;">
                        </div>
                    </div>
                    <div id="grafica_semanal" class="col-sm-4">
                        <img src="/semanal2.png" alt="">
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> http://morrisjs.github.io/morris.js/
        http://w3.unpocodetodo.info/svg/graficos-circulares1.php
        http://w3.unpocodetodo.info/lab/pieChart1.php
        https://www.desarrollolibre.net/public/download/javascript/anillos/circulos.html
        https://www.desarrollolibre.net/blog/javascript/como-crear-anillos-de-circulos-en-javascript-y-canvas#.XJz2dOtKiRs
        https://github.com/joshnh/Git-Commands
        -->
        

    <!-- </div> -->
    </div>
</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>


<script>
    $(document).ready(function(){
        var espacio 		=  $('#menu').offset().top;
        var alto 			= $(window).height();
        var medida_grid		= (alto - espacio);
        $('#contenedor_dashboard').css({'max-height':medida_grid,'min-height':medida_grid});
        $('#menu').css({'height':medida_grid});
        $('#pantalla').css({'height':medida_grid});

        new Morris.Line({
            // ID of the element in which to draw the chart.
            element: 'myfirstchart',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: [
                { year: '2008', value: 20, cantidad: 0 },
                { year: '2009', value: 10, cantidad: 10},
                { year: '2010', value: 5,  cantidad: 20},
                { year: '2011', value: 2.3, cantidad: 30},
                { year: '2012', value: 20, cantidad: 40}
            ],
            // The name of the data record attribute that contains x-values.
            xkey: 'year',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['value'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['cantidad']
        });
        
        $('.subopciones_menu').hide();

        //mostrar submenu
        $(document).on('mouseover','.opcion_menu',function(){
            $(this).children('.subopciones_menu').show();
            var widthOpcionMenu = "" + $(this).width() + "px";
            var heightWindow = $(window).height();
            var heightSubMenu = $(this).children('.subopciones_menu').height();
            var posicionSubMenu = $(this).children('.subopciones_menu').offset().top;

            if( (heightSubMenu+posicionSubMenu) > heightWindow ){
                var posicionTopSubMenu = "" + ((heightSubMenu+posicionSubMenu) - heightWindow) * (-1) + "px";
                $(this).children('.subopciones_menu').css({'margin-top': posicionTopSubMenu});    
            }
            else{
                $(this).children('.subopciones_menu').css({'margin-top': '-45px'});
            }

            $(this).children('.subopciones_menu').css({'margin-left':widthOpcionMenu});
        });

        //ocultar submenu
        $(document).on('mouseout','.opcion_menu',function(){
            $('.subopciones_menu').hide();
        });
    });
</script>


<!-- 
   <h4>Estadísticas de uso de navegadores<br>
<small>( para el mes de enero del año 2014 )</small></h4>
<canvas id="lienzo" width="400" height="300">Su navegador no soporta canvas :( </canvas>
<script>
var canvas = document.getElementById('lienzo');
if (canvas && canvas.getContext) {
  var ctx = canvas.getContext('2d');
  if (ctx) {
    var oData = {
      'IE': '10.2',
      'Firefox': '26.9',
      'Chrome': '55.7',
      'Safari': '3.9',
      'Opera': '1.8'
    };
    var oColores = {
      'IE': '#6495ED',
      'Firefox': '#FF8C00',
      'Chrome': '#FFD700',
      'Safari': '#32CD32',
      'Opera': '#DC143C',
      'otros': '#ddd'
    };
    // el radio del gráfico;					
    var r = 100;
    // las coordenadas del centro del canvas
    var X = canvas.width / 2
    var Y = canvas.height / 2;
    // dibuja un circulo gris en el centro del canvas
    ctx.fillStyle = '#ddd';
    ctx.moveTo(X, Y);
    ctx.arc(X, Y, r, 0, 2 * Math.PI);
    ctx.fill();
    // dibuja un sector circular		
    var ap = 0;
    var af = (2 * Math.PI / 100) * oData.Chrome;
    var Xap = X + r * Math.cos(ap);
    var Yap = Y + r * Math.sin(ap);

    ctx.beginPath();
    ctx.fillStyle = oColores.Chrome;
    ctx.moveTo(X, Y);
    ctx.lineTo(Xap, Yap);
    ctx.arc(X, Y, r, ap, af);
    ctx.closePath();
    ctx.fill();
    
    
  }
}
</script> 
 -->