#include <SoftwareSerial.h>
#include <Wire.h>
#include "RTClib.h"
#include <Adafruit_Sensor.h>
#include "Adafruit_BME680.h"
#define BME_SCK 13
#define BME_MISO 12
#define BME_MOSI 11
#define BME_CS 10
#define PRESIUNE_LA_NIVELUL_MARII (1013.25)

SoftwareSerial gprsSerial(7, 8);
RTC_DS1307 rtc;    // Creeare obiect rtc pentru ceasul in timp real
Adafruit_BME680 bme; // I2C

  float pondere_umiditate = 0.25; // so hum effect is 25% of the total air quality score
float pondere_aer = 0.75; // so gas effect is 75% of the total air quality score
float indice_umiditate, indice_aer;
float referinta_aer;
float referinta_umiditate = 40;
int   CalculeazaReferintaAer_index = 0;


void setup()


{



Serial.begin(4800); 
gprsSerial.begin(4800);

#ifdef AVR
  Wire.begin();
#else
  Wire1.begin(); // initializare conexiune cu interfata ceas RTC
#endif

  rtc.begin(); // inceput rtc
//Pentru ajustare ceas dupa ora sistemului se scot comentariile de pe linia de mai jos.
  // rtc.adjust(DateTime(F(__DATE__), F(__TIME__)));
//Pentru ajustare ceas dupa parametrii introdusi se scot comentariile de pe linia de mai jos si se modifica parametrii.
//rtc.adjust(DateTime(2018, 8, 27, 20, 58, 0));

  Serial.println("Initializare sistem AirSense GSM+GPRS+RTC");
  gprsSerial.flush();
  Serial.flush();
  
  // comanda GPRS  
  gprsSerial.println("AT+CGATT?");
  delay(1000);
  toSerial();

  // setari retea
  gprsSerial.println("AT+SAPBR=3,1,\"CONTYPE\",\"GPRS\"");
  delay(1000);
  toSerial();

  //setari accesspoint pentru internet
  gprsSerial.println("AT+SAPBR=3,1,\"APN\",\"net\"");
  delay(1000);
  toSerial();

//Gprs
  gprsSerial.println("AT+CGATT=1");
  delay(100);
  toSerial();

    //setari GPRS
  gprsSerial.println("AT+SAPBR=1,1");
  delay(2000);
  toSerial();



 while (!Serial);
Serial.println(F("BME680 test"));
  
 Wire.begin();
if (!bme.begin()) {
Serial.println("Could not find a valid BME680 sensor, check wiring!");
 while (1);
 } else Serial.println("Found a sensor");
 

  // Setare oversampling senzor si initializare filtru
  bme.setTemperatureOversampling(BME680_OS_8X);
  bme.setHumidityOversampling(BME680_OS_2X);
  bme.setPressureOversampling(BME680_OS_4X);
  bme.setIIRFilterSize(BME680_FILTER_SIZE_3);
   bme.setGasHeater(320, 150); // 320*C for 150 ms
 CalculeazaReferintaAer(); 

}






void loop()
{
 // initializare serviciu http
   gprsSerial.println("AT+HTTPINIT");
   delay(200); 
   toSerial();
 
     DateTime now = rtc.now();  // citire informatii de la Real time clock

  //declaratii variabile pentru data si timp.
String anul=String(now.year(),DEC);
String luna=String(now.month(),DEC);
String ziua=String(now.day(),DEC);
String ora=String(now.hour(),DEC);
String minutul=String(now.minute(),DEC);
String secunda=String(now.second(),DEC);
String dataValue=anul+"-"+luna+"-"+ziua;
String oraValue=ora+minutul+secunda+".00000";

//afisare data pentru debug
Serial.println(dataValue);
//afisare ora pentru debug
Serial.println(oraValue);
  delay(3000);
  




  Serial.print("Temperatura = ");
  Serial.print(bme.temperature);
  Serial.println(" *C");
  Serial.print("Presiunea = ");
  Serial.print(bme.pressure / 100.0);
  Serial.println(" hPa");
  Serial.print("Umiditatea = ");
  Serial.print(bme.humidity);
  Serial.println(" %");
  Serial.print("RezistentaAer = ");
  Serial.print(bme.gas_resistance);
  Serial.println(" Ohms");
  Serial.print("Altitudine aproximativa = ");
  Serial.print(bme.readAltitude(PRESIUNE_LA_NIVELUL_MARII));
  Serial.println(" m");
  Serial.println();




     float umiditatea_curenta = bme.readHumidity();
      float presiunea_curenta = bme.readPressure();
  float temperatura_curenta = bme.temperature;
 float rezistenta_curenta = bme.gas_resistance;
 

  //Calculate humidity contribution to IAQ index
  if (umiditatea_curenta >= 38 && umiditatea_curenta <= 42)
    indice_umiditate = 0.25*100; // Humidity +/-5% around optimum 
  else
  { //sub-optimal
    if (umiditatea_curenta < 38) 
      indice_umiditate = 0.25/referinta_umiditate*umiditatea_curenta*100;
    else
    {
      indice_umiditate = ((-0.25/(100-referinta_umiditate)*umiditatea_curenta)+0.416666)*100;
    }
  }
  
  //Calculeaza contributia indicelui aerulului la CalitateaAerului
 int limita_inferioara_aer = 10000;   // Limita inferioara calitate aer interior
  int limita_superioara_aer = 250000;  // Limita superioara calitate aer interior

  Serial.println(referinta_aer);
indice_aer = (((referinta_aer + (3*bme.gas_resistance))/4) /200000)*75;
  Serial.println(indice_aer);


  
  //Combine results for the final IAQ index value (0-100% where 100% is good quality air)
  float calitate_aer = indice_umiditate + indice_aer;
  Serial.println("Calitatea aerului = "+String(calitate_aer,1)+"% derived from 25% of Humidity reading and 75% of Gas reading - 100% is good quality air");
  Serial.println("Valoarea umiditatii raportat la 100 a fost : "+String(indice_umiditate/100)+" din 0.25");
  Serial.println("Valoarea aerului raportat la 100 a fost : "+String(indice_aer/100)+" din 0.75");
  if (bme.readGas() < 120000) Serial.println("***** Calitate redusa a aerului *****");
  Serial.println();
  if ((CalculeazaReferintaAer_index++)%10==0) CalculeazaReferintaAer(); 
  Serial.println(CalculeazaCalitateAer(calitate_aer));
  Serial.println("------------------------------------------------");
delay(2000);


     



  //Formare parametrii pentru metoda GET a scriptului ScriereInBazaDeDate.php de pe server
String string1 = "AT+HTTPPARA=\"URL\""  ; 
String string2 = ",";
String string3 = "\"http://www.airsense.ml/ScriereBD.php?data=\"";

String string4 = "&ora=";
String string5 = "&temperatura=";
String string6 = "&presiune=";
String string7 = "&umiditate=";
String string8 = "&rezistenta=";
String string9 = "&calitateaer=";
String string10 = "\"";

          Serial.println("String 1 is:"+string1);
    delay(2000);

    
  
String string11 = string1 + string2 + string3 + dataValue + string4 + oraValue + string5 + String(temperatura_curenta) + string6 + String(presiunea_curenta) + string7 + String(umiditatea_curenta)+ string8 + String(rezistenta_curenta)+ string9 + calitate_aer + string10;
delay(2000);
gprsSerial.println(string11);
delay(2000);
 
   toSerial();
delay(2000);

   gprsSerial.println("AT+HTTPACTION=0");
delay(2000);
   toSerial();
   delay(2000);
  }


  void toSerial()
{
  while(gprsSerial.available()!=0)
  {
    Serial.write(gprsSerial.read());
  }


  
  }


  void CalculeazaReferintaAer(){
  // Initializare si inca, then use combination of relative humidity and gas resistance to estimate indoor air quality as a percentage.
  Serial.println("Citire valoare de referinta noua pentru aer");
  int citiriSenzor = 10;
  for (int i = 0; i <= citiriSenzor; i++){ // read gas for 10 x 0.150mS = 1.5secs
    referinta_aer += bme.readGas();
  }
  referinta_aer = referinta_aer / citiriSenzor;
}

String CalculeazaCalitateAer(float score){
  String TextCalitateAer = "Calitatea Aerului este ";
  score = (100-score)*5;
  if      (score >= 301)                  TextCalitateAer += "Periculoasa";
  else if (score >= 201 && score <= 300 ) TextCalitateAer += "Foarte slaba";
  else if (score >= 176 && score <= 200 ) TextCalitateAer += "Rea";
  else if (score >= 151 && score <= 175 ) TextCalitateAer += "Nepotrivita";
  else if (score >=  51 && score <= 150 ) TextCalitateAer += "Moderata";
  else if (score >=  00 && score <=  50 ) TextCalitateAer += "Buna";
  return TextCalitateAer;



}
