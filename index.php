<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="./estilo.css" rel="stylesheet">
  </head>
  <body class="bg-light">
    <main class="mb-4">
      <header class="py-3 mb-4 border-bottom bg-white sticky-top">
        <div class="container d-flex flex-wrap justify-content-center text-center px-4">
          <h3>Sistema de Monitoramento dos Parâmetros Elétricos de um Mini Aerogerador</h3>
          <p class="text-small m-0">Última Atualização: <span id="data-atualizacao">-</span></p>
        </div>
      </header>

      <div class="container px-4">
        <div class="row mb-4 d-flex align-items-stretch g-4">
          <div class="col-md-4">
            <div class="card card-hover-shadow h-100">
              <div class="card-header">
                Velocidade Antes
              </div>
              <div class="card-body">
                <div id="grafico-velocidade-antes"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-hover-shadow h-100">
              <div class="card-header">
                Velocidade Após
              </div>
              <div class="card-body">
                <div id="grafico-velocidade-apos"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-hover-shadow h-100">
              <div class="card-header">
                Umidade
              </div>
              <div class="card-body">
                <div id="grafico-umidade"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-4 d-flex justify-content-md-center align-items-stretch g-4">
          <div class="col-sm-6 col-lg-3">
            <div class="card card-hover-shadow h-100">
              <div class="card-header">
                Temperatura
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-center">
                  <div id="grafico-temperatura"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card card-hover-shadow h-100">
              <div class="card-header">
                Pressão
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-center">
                  <div id="grafico-pressao"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row d-flex justify-content-md-center align-items-stretch g-4">
          <div class="col-md-4">
            <div class="card card-hover-shadow h-100">
              <div class="card-header">
                Corrente
              </div>
              <div class="card-body">
                <div id="grafico-corrente"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-hover-shadow h-100">
              <div class="card-header">
                Tensão
              </div>
              <div class="card-body">
                <div id="grafico-tensao"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card card-hover-shadow h-100">
              <div class="card-header">
                Potência
              </div>
              <div class="card-body">
                <div id="grafico-potencia"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </main>
    <script src="./graficos.js"></script>
  </body>
</html>