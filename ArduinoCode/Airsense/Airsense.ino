
#include <SoftwareSerial.h>
#include <Wire.h>
#include "RTClib.h"
#include <Adafruit_Sensor.h>
#include "Adafruit_BME680.h"
#define BME_SCK 13
#define BME_MISO 12
#define BME_MOSI 11
#define BME_CS 10
#define SEALEVELPRESSURE_HPA (1013.25)

SoftwareSerial gprsSerial(7, 8);
RTC_DS1307 rtc;    // Creeare obiect rtc pentru ceasul in timp real
Adafruit_BME680 bme; // Constructor senzor

void setup() {
Serial.begin(19200); 
gprsSerial.begin(19200);


#ifdef AVR
  Wire.begin();
#else
  Wire1.begin(); // initializare conexiune cu interfata ceas RTC
#endif

  rtc.begin(); // initializare rtc


bme.begin();  //initializare senzor

  
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

    //Setare oversampling senzor si initializare filtru
  bme.setTemperatureOversampling(BME680_OS_8X);
  bme.setHumidityOversampling(BME680_OS_2X);
  bme.setPressureOversampling(BME680_OS_4X);
  bme.setIIRFilterSize(BME680_FILTER_SIZE_3);
   bme.setGasHeater(320, 150); // 320*C for 150 ms

 
}





void loop() {

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




// initializare serviciu http
   gprsSerial.println("AT+HTTPINIT");
   delay(200); 
   toSerial();




//afisare data pentru debug
Serial.println(dataValue);
//afisare ora pentru debug
Serial.println(oraValue);
  delay(3000);


float pondere_umiditate = 0.25; // so hum effect is 25% of the total air quality score
float pondere_aer = 0.75; // so gas effect is 75% of the total air quality score
float indice_umiditate, indice_aer;

float referinta_umiditate = 40;
 

 float umiditatea_curenta = bme.readHumidity();
     float presiunea_curenta = bme.readPressure()/100;
float temperatura_curenta = bme.temperature;
 float rezistenta_curenta = bme.gas_resistance;



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
 
   Serial.println("indice_umiditate=");
Serial.println(indice_umiditate);

  indice_aer = (rezistenta_curenta / 250000)*75;

   
   Serial.println("indice aer=");
  
  Serial.println(indice_aer);
  
    float calitate_aer = indice_umiditate + indice_aer;

   Serial.println("calitate_aer=");
  
  Serial.println(calitate_aer);


  //Formare parametrii pentru metoda GET a scriptului ScriereBD.php de pe server
String string1 = "AT+HTTPPARA=\"URL\""  ;  
String string2 = ",";
String string3 = "\"http://www.airsense.ml/ScriereBD.php?data=";
String string4 = "&ora=";
String string5 = "&temperatura=";
String string6 = "&presiune=";
String string7 = "&umiditate=";
String string8 = "&rezistenta=";
 String string9 = "&calitateaer=";
String string10 = "\"";



String string11 = string1+ string2 + string3 + dataValue + string4 + oraValue + string5 + bme.temperature + string6 + (presiunea_curenta * 0.75006156130264) + string7 + bme.readHumidity()+ string8 + bme.gas_resistance+ string9 + String(calitate_aer,1) + string10;

    delay(1000);
  Serial.println(string11);
  delay(1000);

//TRIMITERE REQEST HTTP parametrizat cu datele care se scriu in baza de date//
gprsSerial.println(string11);

   delay(1000);
   toSerial();

gprsSerial.println("AT+HTTPACTION=0");
   delay(1000);
   toSerial();



}


void toSerial()
{
  while(gprsSerial.available()!=0)
  {
    Serial.write(gprsSerial.read());
  }
  
}
