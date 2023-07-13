<?php
header( "refresh:100");
$servername = "localhost";
$dbname = "aerogerador";
$username = "esp32";
$password = "raspberry";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, tensao, corrente, velocidadeAntes, velocidadeApos, temperatura, pressao, umidade, vibracao, potencia, timeStamp ";

if (!empty($_GET['limit'])) {
  // Limita a pegar apenas os últimos X registros
  $sql .= "FROM (
    SELECT * FROM apontamentos ORDER BY id DESC LIMIT {$_GET['limit']}
  ) AS sub ORDER BY id ASC";
} else {
  $sql .= "FROM apontamentos ORDER BY id ASC";
}

// Setar requisição como uma api
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("HTTP/1.1 200 OK");

$dados = new stdClass();

function adicionaDado(&$dados, $dado, $medicao) {
  // Adiciona o dado, caso exista
  if (!empty($dado)) {
    $dadoComTimestamp = new stdClass();
    $dadoComTimestamp->$medicao = $dado[$medicao];
    $dadoComTimestamp->timestamp = strtotime($dado['timeStamp']);
    $dados->$medicao[] = $dadoComTimestamp;
  }
}
if ($result = $conn->query($sql)) {
  $dados->tensao = array();
  $dados->corrente = array();
  $dados->potencia = array();
  $dados->vibracao = array();
  while ($dado = $result->fetch_assoc()) {
    $dados->velocidadeAntes = $dado['velocidadeAntes'];
    $dados->velocidadeApos = $dado['velocidadeApos'];
    $dados->temperatura = $dado['temperatura'];
    $dados->pressao = $dado['pressao'];
    $dados->umidade = $dado['umidade'];
    $date = new DateTimeImmutable($dado['timeStamp']);
    $dados->ultimaAtualizacao = $date->format('d/m/Y - H:i:s');
    adicionaDado($dados, $dado, 'tensao');
    adicionaDado($dados, $dado, 'corrente');
    adicionaDado($dados, $dado, 'potencia');
    adicionaDado($dados, $dado, 'vibracao');
  }
  $result->free();
}

echo json_encode($dados);

$conn->close();
?>