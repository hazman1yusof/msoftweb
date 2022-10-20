

    var ctx = document.getElementById("myChart2").getContext('2d');
    var myChart = new Chart(ctx, {
      plugins: [ChartDataLabels],
      type: 'bar',
      data: {
        labels: ["First Week", "Second Week", "Third Week", "Fourth Week"],
        datasets: [{
          label: 'In Patients',
          data: ip_month,
          borderWidth: 1,
          backgroundColor: '#47aeff',
          borderColor: '#47aeff',
          borderWidth: 1.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 2
        },{
          label: 'Out Patients',
          data: op_month,
          borderWidth: 1,
          backgroundColor: '#f44336',
          borderColor: '#f44336',
          borderWidth: 1.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              drawBorder: false,
              color: '#f2f2f2',
            },
            ticks: {
              beginAtZero: true,
              stepSize: 20
            }
          }],
          xAxes: [{
            ticks: {
              display: false
            },
            gridLines: {
              display: false
            }
          }]
        },
        plugins: {
          // Change options for ALL labels of THIS CHART
          datalabels: {
            rotation: 0,
            display: 'auto',
            align :'top',
            clip:true,
            anchor:'end',
            formatter: function(value, context) {
              return new Intl.NumberFormat().format(value);
            }

          }
        }
      }
    });

    var ctx = document.getElementById("myChart3").getContext('2d');
    var myChart = new Chart(ctx, {
      plugins: [ChartDataLabels],
      type: 'bar',
      data: {
        labels: ["First Week", "Second Week", "Third Week", "Fourth Week"],
        datasets: [{
          label: 'In Patients',
          data: ip_month_epis,
          borderWidth: 1,
          backgroundColor: '#47aeff',
          borderColor: '#47aeff',
          borderWidth: 1.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 2
        },{
          label: 'Out Patients',
          data: op_month_epis,
          borderWidth: 1,
          backgroundColor: '#f44336',
          borderColor: '#f44336',
          borderWidth: 1.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              drawBorder: false,
              color: '#f2f2f2',
            },
            ticks: {
              beginAtZero: true,
              stepSize: 20
            }
          }],
          xAxes: [{
            ticks: {
              display: false
            },
            gridLines: {
              display: false
            }
          }]
        },
        plugins: {
          // Change options for ALL labels of THIS CHART
          datalabels: {
            rotation: 0,
            display: 'auto',
            align :'top',
            clip:true,
            anchor:'end',
            formatter: function(value, context) {
              return new Intl.NumberFormat().format(value);
            }

          }
        }
      }
    });


$(document).ready(function () {

  $("div.niceScroll").css( "height", "215px" );
  delay(function(){
    $("div.niceScroll").css( "height", "215px" );
  }, 500 );

});