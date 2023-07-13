const corVerde = '#55BF3B';
const corAmarelo = '#DDDF0D';
const corVermelho = '#DF5353';
const corCinza = '#CDCDCD';

let opcoesGauge = {
  chart: {
    type: 'gauge',
    plotBackgroundColor: null,
    plotBackgroundImage: null,
    plotBorderWidth: 0,
    plotShadow: false,
    height: '80%'
  },

  title: null,

  pane: {
    startAngle: -90,
    endAngle: 89.9,
    background: null,
    center: ['50%', '75%'],
    size: '110%'
  },

  // the value axis
  yAxis: {
    min: 0,
    max: 100,
    tickPixelInterval: 72,
    tickPosition: 'inside',
    tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
    tickLength: 20,
    tickWidth: 2,
    minorTickInterval: null,
    labels: {
      distance: 20,
      style: {
        fontSize: '14px'
      }
    },
  },
  series: [{
    data: [0],
    dial: {
      radius: '80%',
      backgroundColor: 'gray',
      baseWidth: 12,
      baseLength: '0%',
      rearLength: '0%'
    },
    pivot: {
      backgroundColor: 'gray',
      radius: 6
    }
  }],
  credits: { enabled: false }
}

let gaugeYAxisVelocidade = {
  ...opcoesGauge.yAxis,
  max: 20,
  plotBands: [{
    from: 0,
    to: 15,
    color: corVerde, // green
    thickness: 20
  }, {
    from: 15,
    to: 20,
    color: corVermelho, // yellow
    thickness: 20
  }]
}

let graficoVelocidadeAntes = Highcharts.chart('grafico-velocidade-antes', {
  ...opcoesGauge,
  yAxis: gaugeYAxisVelocidade,
  series: [{
    ...opcoesGauge.series[0],
    name: 'Velocidade Antes',
    tooltip: {
      valueSuffix: ' m/s'
    },
    dataLabels: {
      format: '{y} m/s',
      borderWidth: 0,
      style: {
        fontSize: '16px'
      }
    }
  }]
});

let graficoVelocidadeApos = Highcharts.chart('grafico-velocidade-apos', {
  ...opcoesGauge,
  yAxis: gaugeYAxisVelocidade,
  series: [{
    ...opcoesGauge.series[0],
    name: 'Velocidade Após',
    tooltip: {
      valueSuffix: ' m/s'
    },
    dataLabels: {
      format: '{y} m/s',
      borderWidth: 0,
      style: {
        fontSize: '16px'
      }
    }
  }]
});

let graficoUmidade = Highcharts.chart('grafico-umidade', {
  ...opcoesGauge,
  yAxis: {
    ...opcoesGauge.yAxis,
    plotBands: [{
      from: 0,
      to: 100,
      thickness: 20
    }]
  },
  series: [{
    ...opcoesGauge.series[0],
    name: 'Umidade',
    tooltip: {
      valueSuffix: ' %'
    },
    dataLabels: {
      format: '{y} %',
      borderWidth: 0,
      style: {
        fontSize: '16px'
      }
    }
  }]
});

let graficoTemperatura = Highcharts.chart('grafico-temperatura', {
  chart: {
    marginBottom: 70
  },
  series: [{
    data: [0],
    type: 'column',
    pointWidth: 50,
    threshold: -50,
    borderWidth: 0,
    name: 'Temperatura',
    tooltip: {
      headerFormat: '',
      valueSuffix: ' ºC'
    },
    // color: '#DDDF0D' // yellow
    // color: '#DF5353' // red

  }],
  credits: {
    enabled: false
  },
  legend: {
    enabled: false
  },
  xAxis: {
    labels: {
      enabled: false
    },
    lineWidth: 0,
    tickWidth: 0
  },
  plotOptions: {
    column: {
      pointPadding: 0.2,
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        color: 'white',
        verticalAlign: 'top',
        format: '{y} ºC'
      }
    }
  },
  yAxis: {
    min: 0,
    max: 100,
    minPadding: 0,
    maxPadding: 0,
    startOnTick: false,
    endOnTick: false,
    title: null,
    tickInterval: 10,
    minorTickInterval: 1,
    gridLineWidth: 1,
    minorGridLineWidth: 0,
    tickWidth: 1,
    plotBands: [{
      from: 0,
      to: 80,
      color: corCinza
    },{
      from: 80,
      to: 90,
      color: corAmarelo
    },{
      from: 90,
      to: 100,
      color: corVermelho
    }],
    labels: {
      format: '{text} ºC',
    }

  },
  title: null
}, function (chart) {
  // Draw the shape
  var series = chart.series[0],
    point = series.points[0],
    radius = 40;
  chart.renderer.circle(
    chart.plotLeft + point.shapeArgs.x + (point.shapeArgs.width / 2),
    chart.plotTop + series.yAxis.len + radius - 24,
    30
  )
    .attr({
      fill: series.color
    })
    .add();

});

let graficoPressao = Highcharts.chart('grafico-pressao', {
  series: [{
    type: 'column',
    pointWidth: 50,
    threshold: -50,
    borderWidth: 0,
    name: 'Pressão',
    tooltip: {
      headerFormat: '',
      valueSuffix: ' hPa'
    }
  }],
  credits: {
    enabled: false
  },
  legend: {
    enabled: false
  },
  xAxis: {
    labels: {
      enabled: false
    },
    lineWidth: 0,
    tickWidth: 0
  },
  plotOptions: {
    column: {
      pointPadding: 0.2,
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        color: 'white',
        verticalAlign: 'top',
        format: '{y} hPa'
      }
    }
  },
  yAxis: {
    min: 300,
    max: 1500,
    minPadding: 0,
    maxPadding: 0,
    startOnTick: false,
    endOnTick: false,
    title: null,
    tickInterval: 300,
    minorTickInterval: 1,
    gridLineWidth: 1,
    minorGridLineWidth: 0,
    tickWidth: 1,
    plotBands: [{
      from: 0,
      to: 2200,
      color: '#CDCDCD'
    }],
    labels: {
      format: '{text} hPa',
    }

  },
  title: null
});

let graficoLinhaOpcoes = {
  title: null,
  credits: {
    enabled: false
  },
  xAxis: [{
    labels:{
      enabled: false
    }
  }],

  legend: {
    align: 'left',
    verticalAlign: 'top',
    borderWidth: 0
  },

  tooltip: {
    shared: true,
    crosshairs: true
  },

  plotOptions: {
    series: {
      cursor: 'pointer',
      className: 'popup-on-click',
      marker: {
        lineWidth: 1
      }
    }
  },
}

let graficoCorrente = Highcharts.chart('grafico-corrente', {
  ...graficoLinhaOpcoes,
  yAxis: [{
    title: {
      text: null
    },
    labels: {
      align: 'left',
      x: 0,
      y: 10,
      format: '{value:.,0f} A'
    },
    showFirstLabel: false
  }],
  series: [{
    name: 'Corrente',
    lineWidth: 4,
    marker: {
      radius: 4
    },
    tooltip: {
      headerFormat: '',
      valueSuffix: ' A'
    }
  }]
});

let graficoTensao = Highcharts.chart('grafico-tensao', {
  ...graficoLinhaOpcoes,
  yAxis: [{
    title: {
      text: null
    },
    labels: {
      align: 'left',
      x: 0,
      y: 10,
      format: '{value:.,0f} V'
    },
    min: 0,
    max: 24,
    minPadding: 0,
    maxPadding: 0,
    title: null,
    tickInterval: 5,
    showFirstLabel: false
  }],
  series: [{
    name: 'Tensão',
    lineWidth: 4,
    marker: {
      radius: 4
    },
    tooltip: {
      headerFormat: '',
      valueSuffix: ' V'
    }
  }]
});

let graficoPotencia = Highcharts.chart('grafico-potencia', {
  ...graficoLinhaOpcoes,
  yAxis: [{
    title: {
      text: null
    },
    labels: {
      align: 'left',
      x: 0,
      y: 10,
      format: '{value:.,0f} W'
    },
    min: 0,
    max: 420,
    minPadding: 0,
    maxPadding: 0,
    title: null,
    tickInterval: 100,
    showFirstLabel: false
  }],
  series: [{
    name: 'Potência',
    lineWidth: 4,
    marker: {
      radius: 4
    },
    tooltip: {
      headerFormat: '',
      valueSuffix: ' W'
    }
  }]
});

function pegarDados() {
  fetch('/dados.php?limit=50')
  .then((response) => response.json())
  .then((data) => {
    graficoVelocidadeAntes.series[0].setData([+data.velocidadeAntes]);
    graficoVelocidadeApos.series[0].setData([+data.velocidadeApos]);
    graficoUmidade.series[0].setData([+data.umidade]);
    graficoTemperatura.series[0].setData([+data.temperatura]);
    graficoPressao.series[0].setData([+data.pressao]);
    graficoCorrente.series[0].setData(data.corrente.map(dado => (+dado.corrente)));
    graficoTensao.series[0].setData(data.tensao.map(dado => (+dado.tensao)));
    graficoPotencia.series[0].setData(data.potencia.map(dado => (+dado.potencia)));
    document.getElementById('data-atualizacao').innerHTML = data.ultimaAtualizacao;
    setTimeout(pegarDados, 5 * 1000);
  });
}

pegarDados();