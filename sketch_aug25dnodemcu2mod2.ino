//#include <Adafruit_CircuitPlayground.h>
//#include <Adafruit_Circuit_Playground.h>

//#include <dummy.h>
#include <Wire.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266HTTPClient.h>
#include <Adafruit_Fingerprint.h>
#include <LiquidCrystal_I2C.h>
//#include "WiFiClientSecureBearSSL.h"

#define ledOne 7
#define ledTwo 8

String Key = "9P6BEI0JVRA4M9FJ";
const char* server = "api.thingspeak.com";

const char *ssid = "Alshabaab";  
const char *password = "1234567890";
const char *host = "http:// 192.168.43.116/Biometrics/sensor_data.php";

String fing_serial,postData;



LiquidCrystal_I2C lcd(0x27, 16, 2);
SoftwareSerial mySerial(14, 12);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

uint8_t id;
const int buttonPin = 16;     // the number of the pushbutton pin
int buttonState = 0;         // variable for reading the pushbutton status

void setup(){

  pinMode(ledOne, OUTPUT);
  pinMode(ledTwo, OUTPUT);
  
   //Serial.begin(115200);
   lcd.init();
   lcd.backlight();
   Serial.begin(9600);
   Serial.println("init");
   wifi_setup();
   sensor_setup();
   enroll();
}

uint8_t readnumber(void) {
  uint8_t num = 0;

  while (num == 0) {
    while (! Serial.available());
    num = Serial.parseInt();
  }
  return num;
}

void loop(){
   
   Serial.print("hello world!");
   datapost();
   //makeThingSpeakRequest();
}

void wifi_setup(){
  delay(1000);
  WiFi.mode(WIFI_OFF);
  delay(1000);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  //to check wifi connection
  while(WiFi.status() != WL_CONNECTED){
      delay(500);
      Serial.print(".");
      //led to blink as WiFi connects
      digitalWrite(ledOne, HIGH);
      delay(200);
      digitalWrite(ledOne, LOW);
    }
  digitalWrite(ledTwo, HIGH);
  Serial.println("WiFi connection successful!");
  WiFi.mode(WIFI_STA);
  
// lcd.setCursor(0, 0);
//  lcd. print("Connecting");
//  while (WiFi.status() != WL_CONNECTED) {
//    delay(500);
//    lcd. print(".");
//   }
//   lcd.clear();
//
//   lcd. print("Connected To ");
//   lcd.setCursor(0,1);
//   lcd. print(ssid);
//    delay(500);
  
}

void sensor_setup(){
  Serial.print("detecting lcd");
  finger.begin(9600);
  lcd.begin(6,2);
  Serial.print(":) :)");

//  lcd.setCursor(0,0);
//  lcd.print(" Hello, World!!");
//  lcd.setCursor(0,1);
//  lcd.print(" 16x2 LCD I2C");  
//  
  lcd.print("Found FINGER_");
  Serial.print("00000000000000000");
  
  if (finger.verifyPassword()) {
    lcd.clear();
  
   lcd.setCursor(0,0);
    lcd. print("Found FINGER_");
    lcd.setCursor(0,1);
   lcd. print("PRINT SENSOR");
   delay(4000);
  } else {
     lcd. print("NOTFOUND Sensor!");
    while (1) { delay(1); }
  }
 
}
void enroll() {
  // put your setup code here, to run once:
pinMode(buttonPin, INPUT);
Serial.begin(9600);
  while (!Serial);  // For Yun/Leo/Micro/Zero/...
  delay(100);
  Serial.println("\n\nAdafruit Fingerprint sensor enrollment");

  // set the data rate for the sensor serial port
  finger.begin(57600);

  if (finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor!");
  } else {
    while (1) { delay(1); }
  }

  Serial.println(F("Reading sensor parameters"));
  finger.getParameters();
  Serial.print(F("Status: 0x")); Serial.println(finger.status_reg, HEX);
  Serial.print(F("Sys ID: 0x")); Serial.println(finger.system_id, HEX);
  Serial.print(F("Capacity: ")); Serial.println(finger.capacity);
  Serial.print(F("Security level: ")); Serial.println(finger.security_level);
  Serial.print(F("Device address: ")); Serial.println(finger.device_addr, HEX);
  Serial.print(F("Packet len: ")); Serial.println(finger.packet_len);
  Serial.print(F("Baud rate: ")); Serial.println(finger.baud_rate);
}

void newenroll(){
  Serial.println("Ready to enroll a fingerprint!");
  Serial.println("Please type in the ID # (from 1 to 127) you want to save this finger as...");
  id = readnumber();
  if (id == 0) {// ID #0 not allowed, try again!
     return;
  }
  Serial.print("Enrolling ID #");
  Serial.println(id);

  while (!  getFingerprintEnroll() );
  lcd.print("Enrolled");
}

void datapost(){
  Serial.begin(9600);
  Serial.print("Enter prints");
  HTTPClient http;    //Declare object of class HTTPClient
  fing_serial = getStudentfingerID();
   lcd.clear();
   
  lcd. print("YOUR FINGERPRINT");
   lcd.setCursor(3,1);
   lcd. print("ID IS: " + fing_serial);
  delay(4000);
  

  //Post Data
  postData = "fing_serial=" + fing_serial;
//   lcd. print(postData);
  
  http.begin("http://192.168.100.4/rem/sensor_data.php");              //Specify request destination
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Specify content-type header

  int httpCode = http.POST(postData);   //Send the request
  String payload = http.getString();    //Get the response payload
 //lcd. print(postData);
  
   lcd.clear();
  //lcd. print(httpCode);   //Print HTTP return code
  //delay(1000);
  Serial.begin(9600);
 Serial.println(payload);
   lcd.clear();
  
  lcd. print(payload);    //Print  request response payload 
  delay(1000);
  http.end();  //Close connection
  
}

//void makeThingSpeakRequest() {
//  
////BearSSL::WiFiClientSecure client;
//  client.setInsecure();
//int retries = 5;
//  while (!!!client.connect(server,
//                           443) && (retries-- > 0)) {
//    Serial.print(".");
//  }
//  Serial.println();
//  if (!!!client.connected()) {
//    Serial.println("Failed to connect...");
//  }
//
//  Serial.print("Request resource: ");
//  
//
//
//  // Temperature in Celsius
//  String postStr = Key;
//   //String jsonObject = String(ID);
//                             postStr +="&field1=";
//                             postStr += String(fing_serial);
//
//                             postStr += "\r\n\r\n";
// 
//                             client.print("POST /update HTTP/1.1\n");
//                             client.print("Host: api.thingspeak.com\n");
//                             client.print("Connection: close\n");
//                             client.print("X-THINGSPEAKAPIKEY: "+Key+"\n");
//                             client.print("Content-Type: application/x-www-form-urlencoded\n");
//                             client.print("Content-Length: ");
//                             client.print(postStr.length());
//                             client.print("\n\n");
//                             client.print(postStr);
//  Serial.println(postStr);
//
//  Serial.println("\nclosing connection");
//  client.stop();
//}


int getStudentfingerID(){
  // Will return FingerprintID in int format
  // -1 indicate error code
  int p = -1;
  while (p != FINGERPRINT_OK){
    p = finger.getImage(); // Get Fingerprint Image
    switch (p) {
      case FINGERPRINT_OK:
         lcd.clear();

         lcd.setCursor(6,0);
        lcd. print("OKAY");
        delay(500);
        break;
      case FINGERPRINT_NOFINGER:
        lcd.clear();
         
         lcd.print("WaitingFinger...");
         lcd.setCursor(0,1);
         lcd.print("Press To Enroll");
  buttonState = digitalRead(buttonPin);

  // check if the pushbutton is pressed. If it is, the buttonState is HIGH:
  if (buttonState ==  HIGH) {
Serial.println("Start Enroll");
    newenroll();
  }   
        break;
      default:
        lcd.clear();
       
        lcd. print("ERR_GETIMAGE");
        break;
    }
  }

  p = finger.image2Tz(); // Convert Image
  switch (p) {
    case FINGERPRINT_OK:
      break;
    default:
      lcd.clear();
      lcd.print("ERR_CONVERT");
      return -1;
  }

  p = finger.fingerFastSearch();
  switch (p){
    case FINGERPRINT_OK:
      return finger.fingerID;
    case FINGERPRINT_NOTFOUND:
      return -1;
  }
}

uint8_t getFingerprintEnroll() {

  int p = -1;
  Serial.print("Waiting for valid finger to enroll as #"); Serial.println(id);
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    case FINGERPRINT_NOFINGER:
      Serial.println(".");
      break;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      break;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      break;
    default:
      Serial.println("Unknown error");
      break;
    }
  }

  // OK success!

  p = finger.image2Tz(1);
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  Serial.println("Remove finger");
  delay(2000);
  p = 0;
  while (p != FINGERPRINT_NOFINGER) {
    p = finger.getImage();
  }
  Serial.print("ID "); Serial.println(id);
  p = -1;
  Serial.println("Place same finger again");
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    case FINGERPRINT_NOFINGER:
      Serial.print(".");
      break;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      break;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      break;
    default:
      Serial.println("Unknown error");
      break;
    }
  }

  // OK success!

  p = finger.image2Tz(2);
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  // OK converted!
  Serial.print("Creating model for #");  Serial.println(id);

  p = finger.createModel();
  if (p == FINGERPRINT_OK) {
    Serial.println("Prints matched!");
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_ENROLLMISMATCH) {
    Serial.println("Fingerprints did not match");
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }

  Serial.print("ID "); Serial.println(id);
  p = finger.storeModel(id);
  if (p == FINGERPRINT_OK) {
    Serial.println("Stored!");
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_BADLOCATION) {
    Serial.println("Could not store in that location");
    return p;
  } else if (p == FINGERPRINT_FLASHERR) {
    Serial.println("Error writing to flash");
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }

  return true;
}
