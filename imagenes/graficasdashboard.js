$(document).ready(function(){
    if(typeof document.getElementById('graficaganancias') != 'undefined'){
        var ctx1 = document.getElementById('graficaganancias').getContext('2d');
        var chart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                datasets: [{
                    label: 'Ganancias',
                    // backgroundColor: '#42a5f5',
                    borderColor: 'gray',
                    data: [7, 8, 5, 2, 8, 10, 7,7,4,9,8,5]
                }		
                ]},
            options: {}
        });
    }

    if(typeof document.getElementById('graficasemanal') != 'undefined'){
        var ctx2 = document.getElementById('graficasemanal').getContext('2d');
        var chart = new Chart(ctx2, {
            type: 'pie',
            data: 	
            {
                        datasets: [{
                            data: [60,18,10, 8, 4],
                            backgroundColor: ['#42a5f5', 'red', 'green','blue','violet'],
                            label: 'Comparacion de navegadores'
                        }],
                        labels: [
                            'Google Chrome',
                            'Safari',
                            'Edge',
                            'Firefox',
                            'Opera'
                        ]},
            options: {}
        });
    }

});