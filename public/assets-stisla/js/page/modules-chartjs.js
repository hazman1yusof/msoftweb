"use strict";


console.log(ip_month);

var ctx = document.getElementById("myChart2").getContext('2d');
var myChart = new Chart(ctx, {
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
  }
});
