'use strict';

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart1);
function drawChart1() {
  var data = google.visualization.arrayToDataTable([
    ['Mes', 'Ventas', 'Gastos'],
    ['Mayo',  1000,      400],
    ['Junio',  1170,      460],
    ['Julio',  660,       1120],
    ['Agosto',  1030,      540]
  ]);

  var options = {
    title: 'Desempeño',
    hAxis: {title: 'Meses', titleTextStyle: {color: 'red'}}
 };

var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
  chart.draw(data, options);
}

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart2);
function drawChart2() {
  var data = google.visualization.arrayToDataTable([
    ['Mes', 'Ventas', 'Gastos'],
    ['Mayo',  1000,      400],
    ['Junio',  1170,      460],
    ['Julio',  660,       1120],
    ['Agosto',  1030,      540]
  ]);

  var options = {
    title: 'Desempeño',
    hAxis: {title: 'Meses',  titleTextStyle: {color: '#333'}},
    vAxis: {minValue: 0}
  };

  var chart = new google.visualization.AreaChart(document.getElementById('chart_div2'));
  chart.draw(data, options);
}

$(window).resize(function(){
  drawChart1();
  drawChart2();
});
