$(document).ready(function(){
    if(typeof document.getElementById('graficaedocuenta') != 'undefined'){
        var ctx3 = document.getElementById('graficaedocuenta').getContext('2d');
        var chart = new Chart(ctx3, {
            type: 'pie',
            data: 	
            {
                datasets: [{
                    data: [60,40,],
                    backgroundColor: ['#6495ED','grey'],
                    label: 'Comparacion de navegadores'
                }],
                labels: [
                    'inversion',
                    'faltante'
                ]
            },
            options: {}
        });
    }
});