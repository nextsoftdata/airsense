
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

float hum_weighting = 0.25; // so hum effect is 25% of the total air quality score
float gas_weighting = 0.75; // so gas effect is 75% of the total air quality score

float hum_score, gas_score;
float gas_reference = 250000;
float hum_reference = 40;
int   getgasreference_count = 0;




void setup(){
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



   GetGasReference(); 
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




  //Calculate humidity contribution to IAQ index
  float current_humidity = bme.readHumidity();
  if (current_humidity >= 38 && current_humidity <= 42)
    hum_score = 0.25*100; // Humidity +/-5% around optimum 
  else
  { //sub-optimal
    if (current_humidity < 38) 
      hum_score = 0.25/hum_reference*current_humidity*100;
    else
    {
      hum_score = ((-0.25/(100-hum_reference)*current_humidity)+0.416666)*100;
    }
  }
  
  //Calculate gas contribution to IAQ index
  int gas_lower_limit = 5000;   // Bad air quality limit
  int gas_upper_limit = 50000;  // Good air quality limit 
  if (gas_reference > gas_upper_limit) gas_reference = gas_upper_limit; 
  if (gas_reference < gas_lower_limit) gas_reference = gas_lower_limit;
  gas_score = (0.75/(gas_upper_limit-gas_lower_limit)*gas_reference -(gas_lower_limit*(0.75/(gas_upper_limit-gas_lower_limit))))*100;
  
  //Combine results for the final IAQ index value (0-100% where 100% is good quality air)
  float air_quality_score = hum_score + gas_score;

  Serial.println("Air Quality = "+String(air_quality_score,1)+"% derived from 25% of Humidity reading and 75% of Gas reading - 100% is good quality air");
  Serial.println("Humidity element was : "+String(hum_score/100)+" of 0.25");
  Serial.println("     Gas element was : "+String(gas_score/100)+" of 0.75");
  if (bme.readGas() < 120000) Serial.println("***** Poor air quality *****");
  Serial.println();
  if ((getgasreference_count++)%10==0) GetGasReference(); 
  Serial.println(CalculateIAQ(air_quality_score));
  Serial.println("------------------------------------------------");
  delay(2000);




}

void toSerial()
{
  while(gprsSerial.available()!=0)
  {
    Serial.write(gprsSerial.read());
  }
  
}





String CalculateIAQ(float score){
  String IAQ_text = "Air quality is ";
  score = (100-score)*5;
  if      (score >= 301)                  IAQ_text += "Hazardous";
  else if (score >= 201 && score <= 300 ) IAQ_text += "Very Unhealthy";
  else if (score >= 176 && score <= 200 ) IAQ_text += "Unhealthy";
  else if (score >= 151 && score <= 175 ) IAQ_text += "Unhealthy for Sensitive Groups";
  else if (score >=  51 && score <= 150 ) IAQ_text += "Moderate";
  else if (score >=  00 && score <=  50 ) IAQ_text += "Good";
  return IAQ_text;
}




void GetGasReference(){
  // Now run the sensor for a burn-in period, then use combination of relative humidity and gas resistance to estimate indoor air quality as a percentage.
  Serial.println("Getting a new gas reference value");
  int readings = 10;
  for (int i = 0; i <= readings; i++){ // read gas for 10 x 0.150mS = 1.5secs
    gas_reference += bme.readGas();
  }
  gas_reference = gas_reference / readings;
}
