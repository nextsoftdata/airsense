
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

Adafruit_BME680 bme; // I2C
SoftwareSerial gprsSerial(7, 8);

RTC_DS1307 rtc;    // Creeare obiect rtc pentru ceasul in timp real



void setup()
{
Serial.begin(9600); 
gprsSerial.begin(9600);


#ifdef AVR
  Wire.begin();
#else
  Wire1.begin(); // initializare conexiune cu interfata ceas RTC
#endif

  rtc.begin(); // inceput rtc

//Pentru ajustare ceas dupa ora sistemului se scot comentariile de pe linia de mai jos.
   //  rtc.adjust(DateTime(F(__DATE__), F(__TIME__)));
//Pentru ajustare ceas dupa parametrii introdusi se scot comentariile de pe linia de mai jos si se modifica parametrii.
   //rtc.adjust(DateTime(2017, 6, 4, 23, 12, 0));

  Serial.println("Initializare sistem AirSense GSM+GPRS+RTC");
  //delay(2000);
  gprsSerial.flush();
  Serial.flush();

  if (!bme.begin()) {
    Serial.println("Could not find a valid BME680 sensor, check wiring!");
    while (1);
  }


  // Set up oversampling and filter initialization
  bme.setTemperatureOversampling(BME680_OS_8X);
  bme.setHumidityOversampling(BME680_OS_2X);
  bme.setPressureOversampling(BME680_OS_4X);
  bme.setIIRFilterSize(BME680_FILTER_SIZE_3);
  bme.setGasHeater(320, 150); // 320*C for 150 ms


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

  
}

void loop()
{
 // initializare serviciu http
   gprsSerial.println("AT+HTTPINIT");
   delay(200); 
   toSerial();

  if (! bme.performReading()) {
    Serial.println("Failed to perform reading :(");
    return;
  }
  Serial.print("Temperature = ");
  Serial.print(bme.temperature);
  Serial.println(" *C");

  Serial.print("Pressure = ");
  Serial.print(bme.pressure / 100.0);
  Serial.println(" hPa");

  Serial.print("Humidity = ");
  Serial.print(bme.humidity);
  Serial.println(" %");

  Serial.print("Gas = ");
  Serial.print(bme.gas_resistance / 1000.0);
  Serial.println(" KOhms");

  Serial.print("Approx. Altitude = ");
  Serial.print(bme.readAltitude(SEALEVELPRESSURE_HPA));
  Serial.println(" m");

  Serial.println();
     delay(2000);

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


   
 

//Formare parametrii pentru metoda GET a scriptului DBWrite.php de pe server
String stringOne = "AT+HTTPPARA=\"URL\""  ;  

String stringTwo = ",";

String stringThree = "\"http://www.nextflood.tk/DBWrite.php?data=";

String stringFour = "&ora=";

String stringFive = "&nivel=";

String stringSix = "\"";

  String stringSeven = stringOne + stringTwo + stringThree + dataValue + stringFour + oraValue + stringFive + stringSix;

  
  Serial.println(stringSeven);

//TRIMITERE REQEST HTTP parametrizat cu datele care se scriu in baza de date//
gprsSerial.println(stringSeven);

   delay(1000);
   toSerial();


   gprsSerial.println("AT+HTTPACTION=0");
   delay(1000);
   toSerial();



String destinationNumber = "+40758108756";
String text_atentionare1="Atentie! Nivelul pe Bega este: ";
String text_atentionare2=". Urmariti evolutia urmatoare";
String text_atentionare3=". Evacuati casa si mergeti pe teren inalt";
String text_atentionare4=". Sunteti in pericol. Asteptati interventia!";
String cm="cm";









}

void toSerial()
{
  while(gprsSerial.available()!=0)
  {
    Serial.write(gprsSerial.read());
  }
  
}
