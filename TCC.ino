float potencia;
//_________________Variaveis utilizadas para o ANEMOMETRO_________________//

const float pi = 3.14159265;  //Número de pi
int periodo = 5000;           //Tempo de amostra para calculo
int amostragem = 2000;        //Tempo para impressão das variaveis
int RaioAnemometro = 75;      //Raio do anemometro(mm)
int tempo1 = 0;               //Tempo para millis()
int tempo2 = 0;               //Tempo para millis()
unsigned int contador1 = 0;   //Contador para o sensor
unsigned int contador2 = 0;   //Contador para o sensor
unsigned int RPMDepois = 0;   //Rotações por minuto anemometro antes
unsigned int RPMAntes = 0;    //Rotações por minuto anemometro depois
float velocidadeAntes = 0;    //Velocidade do vento anemometro antes (m/s)
float velocidadeDepois = 0;   //Velocidade do vento anemometro depois
void anemometro();            //Rotina do anemometro
void interrupcao1();          //Rotina da interrupção1 (quando aciona a entrada do anemometro antes)
void interrupcao2();          //Rotina da interrupção1 (quando aciona a entrada do anemometro depois)

//_________________Variaveis utilizadas para a CORRENTE_________________//

#include <Wire.h>
#include <Adafruit_INA219.h>  //Biblioteca do sensor
float corrente = 0;           //Variavel de amostragem da corrente
float correnteAnterior = 0;
Adafruit_INA219 ina219;

//_________________FFT VARIABLES_________________//

#define SCL_INDEX 0x00
#define SCL_TIME 0x01
#define SCL_FREQUENCY 0x02
#define SCL_PLOT 0x03

#include "arduinoFFT.h"

int tfft1 = 0;  //temporizador da fft

const uint16_t samples = 64;  //This value MUST ALWAYS be a power of 2
//const double signalFrequency = 1000;
const double samplingFrequency = 5000;
//const uint8_t amplitude = 100;

uint16_t i = 0;

double vReal[samples];
double vImag[samples];
double abscissa[samples];
float fftResult[samples];
double peak;

arduinoFFT FFT;


//_________________Variaveis utilizadas para o BMP280_________________//

#include <Adafruit_Sensor.h>          //incluir biblioteca do sensor BME280
#include <Adafruit_BME280.h>          //incluir biblioteca do sensor BME280
Adafruit_BME280 bme;                  // I2C BME280
float temperatura, pressao, umidade;  //Declaração das variaveis



//_________________ADS VARIABLES_________________//
#include <ADS1115_WE.h>
#define I2C_ADDRESS 0x48

ADS1115_WE adc = ADS1115_WE(I2C_ADDRESS);  // -> uses Wire / I2C_ADDRESS
double sensorValue[200];
int arrayIndex = 0;
bool leitura = 1;
int size = 200;
float voltage = 0.0;
float sensorTensao = 0;
float tensao = 0;
void tensaoAerogerador();


//_________________Variaveis utilizadas para o WIFI_________________//

#include <WiFi.h>                                                  //incluir biblioteca Wifi
#include <HTTPClient.h>                                            // ARDUINO V.0.4.0 biblioteca base de dados
const char* ssid = "raspiAP";                                      //login wifi
const char* password = "raspberry";                                //senha wifi
const char* serverName = "http://10.0.0.220/aerogeradorData.php";  //PHP BD
String apiKeyValue = "tPmAT5Ab3j7F9";                              //chave BD
String fftApiKeyValue = "sgUn0I3FrI4Nt";                           //chave BD para fft
int httpResponseCode;                                              //Verificar variaveis enviadas


//.................................... VOID SETUP....................................//
void setup() {
  //_________________SETUP ADS1115_________________//

  Wire.begin();
  Serial.begin(115200);
  if (!adc.init()) {
    Serial.println("ADS1115 not connected!");
  }

  adc.setVoltageRange_mV(ADS1115_RANGE_6144);  //Set the voltage range of the ADC to adjust the gain ->  +/- 6144 mV

  Serial.println("Ready");

  //..............Setup ANEMOMETRO.....................

  pinMode(14, INPUT);  //configura o digital 14 como entrada velocidadeDepois
  pinMode(25, INPUT);  //configura o digital 25 como entrada velocidadeAntes

  //..............Setup CORRENTE.........................

  if (!ina219.begin()) {
    Serial.println("Erro ao comunicar com sensor de corrente");
    Serial.println("Medindo corrente ...");
  }

  //..............Setup BME280.........................

  bool status = bme.begin(0x76);  //Define endereço na I2C
  if (!status) {
    Serial.println("Could not find a valid BME280 sensor, check wiring, address, sensor ID!");
    Serial.print("SensorID was: 0x");
    Serial.println(bme.sensorID(), 16);
    Serial.print("        ID of 0xFF probably means a bad address, a BMP 180 or BMP 085\n");
    Serial.print("   ID of 0x56-0x58 represents a BMP 280,\n");
    Serial.print("        ID of 0x60 represents a BME 280.\n");
    Serial.print("        ID of 0x61 represents a BME 680.\n");
    while (1) delay(10);
  }

  //..............Setup WIFI.........................

  WiFi.begin(ssid, password);  //Conecta wifi
  Serial.println("Connecting");

  while (WiFi.status() != WL_CONNECTED)  //aguarda conexão wifi
  {
    delay(500);  //Enquanto não conectar, fica tentando.....
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());  //Wifi conectado + mostra IP
}

void anemometro()  //Rotina para o anemometro
{
  attachInterrupt(14, interrupcao1, RISING);  //Habilita interrupção em borda de subida no pino 14
  attachInterrupt(25, interrupcao2, RISING);  //Habilita interrupção em borda de subida no pino 25
  if ((millis() - tempo1) > periodo) {
    velocidadeAntes = 0;
    velocidadeDepois = 0;
    RPMDepois = ((contador1)*30) / (periodo / 1000);                         // Calcula rotações por minuto
    velocidadeDepois = ((4 * pi * RaioAnemometro * RPMDepois) / 60) / 1000;  //Calcula a velocidade do vento em m/s
    RPMAntes = ((contador2)*30) / (periodo / 1000);                          // Calcula rotações por minuto
    velocidadeAntes = ((4 * pi * RaioAnemometro * RPMAntes) / 60) / 1000;    //Calcula a velocidade do vento em m/s
    contador1 = 0;
    contador2 = 0;


    tempo1 = millis();
  }
}
void interrupcao1()  //Rotina para a interrupção do anemometro
{
  contador1++;
  detachInterrupt(14);
}
void interrupcao2()  //Rotina para a interrupção do anemometro
{
  contador2++;
  detachInterrupt(25);
}

void SensorCorrente()  //Rotina para a interrupção do Sensor de Corrente
{

  corrente = ((ina219.getCurrent_mA()+3)/1000);  //Soma por 3 para ajuste de offset e dividipo por 1000 para mostar em Amperes
  
}

float lerVin(ADS1115_MUX channel) {
  adc.setCompareChannels(channel);
  sensorTensao = adc.getResult_V();
  return sensorTensao;
}

void bme280()  //Rotina para a leitura de pressão, temperatura e umidade
{
  temperatura = bme.readTemperature();    //Definindo a variavel da temperatura
  pressao = bme.readPressure() / 100.0F;  //Definindo a variavel de pressao
  umidade = bme.readHumidity();           //Definindo a variavel de umidade
}

void vibration() {
  //___________________READ THE SENSOR TO GET THE DATA___________________//
  for (i = 0; i < samples; i++) {
    float voltage = 0.0;

    voltage = readChannel(ADS1115_COMP_1_GND);
    vReal[i] = (voltage); /* Build data displaced on the Y axis to include only positive values*/
    vImag[i] = 0.0;       //Imaginary part must be zeroed in case of looping to avoid wrong calculations and overflows
  }

  // if ((millis() - tfft1) > samples) {

  FFT = arduinoFFT(vReal, vImag, samples, samplingFrequency); /* Create FFT object */
  /* Print the results of the simulated sampling according to time */
  //Serial.println("Data:");
  //PrintVector(vReal, samples, SCL_TIME);
  FFT.Windowing(FFT_WIN_TYP_HAMMING, FFT_FORWARD); /* Weigh data */
  FFT.Compute(FFT_FORWARD);                        /* Compute FFT */
  FFT.ComplexToMagnitude();                        /* Compute magnitudes */
  //Serial.println("Computed magnitudes:");
  //PrintVector(vReal, (samples >> 1), SCL_FREQUENCY);
  //peak = FFT.MajorPeak();
  //Serial.println(x, 6);
  //vReal[i] = 0;
  //i = 0;
  //tfft1 = millis();
  // }
}


void PrintVector(double* vData, uint16_t bufferSize, uint8_t scaleType) {
  for (uint16_t i = 0; i < bufferSize; i++) {
    switch (scaleType) {
      case SCL_INDEX:
        abscissa[i] = (i * 1.0);
        break;
      case SCL_TIME:
        abscissa[i] = ((i * 1.0) / samplingFrequency);
        break;
      case SCL_FREQUENCY:
        abscissa[i] = ((i * 1.0 * samplingFrequency) / samples);
        break;
    }
    Serial.print(abscissa[i], 0);
    if (scaleType == SCL_FREQUENCY)
      Serial.print("Hz");
    Serial.print(" ");
    Serial.println(vData[i], 3);
    fftResult[i] = vData[i];
  }
  //Serial.println();
}

void tensaoAerogerador() {

  sensorTensao = readChannel(ADS1115_COMP_0_GND);
  tensao = (sensorTensao)*5.9*1.8;  // modificado 160323 - Ajuste para resolução
}


float readChannel(ADS1115_MUX channel) {
  float voltage = 0.0;
  adc.setCompareChannels(channel);
  adc.startSingleMeasurement();
  while (adc.isBusy()) {}
  voltage = adc.getResult_V();  // alternative: getResult_mV for Millivolt
  return voltage;
}

void loop() {
  anemometro();      //Rotina para leitura anemômetro
  SensorCorrente();  //Rotina para leitura Corrente
  tensaoAerogerador();
  bme280();  //Rotina para leitura Sensor temperatura pressão umidade
  vibration();  //Rotina para extração da fft

  //**************IMPRESSÃO DAS VARIAVEIS***************************

  if ((millis() - tempo2) > amostragem) {
    //vibration();

    Serial.println("***************MEDIÇÃO DAS VARIÁVEIS******************* ");
    Serial.print("Corrente:");
    Serial.print(corrente);  //Imprime valor de corrente na Serial
    Serial.println("A");

    Serial.print("Tensão:");
    Serial.print(tensao, 2);  //Imprime valor de tensão na Serial
    Serial.println("V");

    Serial.print("Potencia:");
    potencia = corrente * tensao;  //Imprime valor de potencia na Serial
    Serial.print(potencia, 2);
    Serial.println("W");

    Serial.print("Pressao medida: ");
    Serial.print(pressao, 1);  //Imprime valor da pressão na Serial
    Serial.println("hPa");

    Serial.print("Temperatura medida: ");
    Serial.print(temperatura, 1);  //Imprime valor da temperatura na Serial
    Serial.println("°C");

    Serial.print("Umidade relativa do ar medida: ");
    Serial.print(umidade, 1);  //Imprime valor da umidade na Serial
    Serial.println("%");

    Serial.print("Vel. Vento Antes:");
    Serial.print(velocidadeAntes);  //Imprime valor da rotação antes na Serial
    Serial.println("[m/s]");

    Serial.print("Vel. Vento Depois:");
    Serial.print(velocidadeDepois);  //Imprime valor da rotação depois na Serial
    Serial.println("[m/s]");
    Serial.println();

    //Serial.print("Frequencia de Pico:");
    //Serial.println("Spectrum:");
    Serial.println("Data:");
    //PrintVector(vReal, samples, SCL_INDEX);
    PrintVector(vReal, (samples >> 1), SCL_FREQUENCY);
    //std::to_string(peak);
    //Serial.print(peak);  //Imprime valor do pico da fundamental na Serial
    //Serial.println("Hz");
    Serial.println();

    if (WiFi.status() == WL_CONNECTED) {  //Se o wifi estiver conectado
      WiFiClient client;
      HTTPClient http;
      http.begin(client, serverName);  //Acessa o banco de dados e envia as variaveis
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      String httpRequestData = "api_key=" + apiKeyValue
                               + "&tensao=" + tensao
                               + "&corrente=" + corrente
                               + "&velocidadeAntes=" + velocidadeAntes
                               + "&velocidadeApos=" + velocidadeDepois
                               + "&temperatura=" + temperatura
                               + "&pressao=" + pressao
                               + "&umidade=" + umidade
                               + "&potencia=" + potencia
                               + "&peak=" + peak;
      //+ "&fft=" + fftResult[i];


      int httpResponseCode = http.POST(httpRequestData);  //tenta salvar os dados no banco de dados
      
      for (uint16_t i = 0; i < 32; i++) {
        String httpRequestFFTData = "fft_api_key=" + fftApiKeyValue

                                    + "&vibracao=" + String(fftResult[i])
                                    + "&frequencia=" + String(abscissa[i], 0);
        http.POST(httpRequestFFTData);
      }

      //Serial.println(httpRequestData);

      if (httpResponseCode > 0) {  //Apresenta mensagem se salvou
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        Serial.println("Dados salvos com sucesso");
      } else {  //Mostra o n do alarme se não salvou
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
        Serial.println("Falha ao salvar");
      }

      http.end();  //Finaliza conexão
    } else {
      Serial.println("WiFi Disconnected");  //Desconecta o wifi
    }
    for (uint16_t i = 0; i < 32; i++) {
      vReal[i] = 0;
    }
    //corrente = 0;
    tempo2 = millis();  //Tempo para envio ao servidor e impressão serial
  }
}
