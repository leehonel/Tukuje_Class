#include <Adafruit_Fingerprint.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

LiquidCrystal_I2C lcd(0x27, 16, 2); // set the LCD address to 0x27 for a 16 chars and 2 line display


// For UNO and others without hardware serial, we must use software serial...
// pin #2 is IN from sensor (GREEN wire)
// pin #3 is OUT from arduino  (WHITE wire)
// Set up the serial port to use softwareserial..
SoftwareSerial mySerial(10, 11);


struct Student {
  String first_name;
  String last_name;
  String reg_number;
  bool fee_balance;
  int finger_id;
};

bool match_found = false;

//*********DETAILS TEMPLATE***************
Student student1;
Student student2;
Student student3;

Student class_register[10];

Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

void mark_register(int id) {
  for (volatile int i = 0; i < 2 ; i++) {
    if (class_register[i].finger_id == id) {
      lcd.clear();
      if (class_register[i].fee_balance == true) {
        Serial.println("Kindly clear your fee balance");
        lcd.setCursor(0, 1);
        lcd.print("FEE BALANCE");
      }
      else {
        Serial.println("Cleared to sit exams");
        lcd.setCursor(0, 1);
        lcd.print("CLEARED");
      }
    }
  }
  delay(3000);
}

void setup()
{
  //*********DETAILS TEMPLATE***************
  student1.first_name = "STELLA";
  student1.last_name = "MAKENA";
  student1.reg_number = "EEEQ/04088P/2016";
  student1.fee_balance = true;
  student1.finger_id = 1;
  //***************************************
  student2.first_name = "TIMOTHY";
  student2.last_name = "STEVE";
  student2.reg_number = "EEEQ00123/2016";
  student2.fee_balance = false;
  student2.finger_id = 2;

  student2.first_name = "TIMOTHY";
  student2.last_name = "STEVE";
  student2.reg_number = "EEEQ00123/2016";
  student2.fee_balance = false;
  student2.finger_id = 2;

 student3.first_name = "DICKSON";
  student3.last_name = "WAMBAA";
  student3.reg_number = "EEEQ/00900/2016";
  student3.fee_balance = false;
  student3.finger_id = 3;
  
   student3.first_name = "DICKSON";
  student3.last_name = "WAMBAA";
  student3.reg_number = "EEEQ/00900/2016";
  student3.fee_balance = true;
  student3.finger_id = 3;
  
  
  class_register[0] = student1;
  class_register[1] = student2;
  class_register[2] = student3;
   class_register[3] = student3;
    class_register[3] = student3;
  

  Serial.begin(9600);
  lcd.init();
  lcd.backlight();

  lcd.setCursor(0, 0);
  lcd.print("EXAM AUTH SYS");

  while (!Serial);  // For Yun/Leo/Micro/Zero/...
  delay(100);
  Serial.println("\n\nAdafruit finger detect test");

  // set the data rate for the sensor serial port
  finger.begin(57600);
  delay(5);
  if (finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor!");
  } else {
    Serial.println("Did not find fingerprint sensor :(");
    while (1) {
      delay(1);
    }
  }


  finger.getTemplateCount();

  if (finger.templateCount == 0) {
    Serial.print("Sensor doesn't contain any fingerprint data. Please run the 'enroll' ");
  }
  else {
    Serial.println("Waiting for valid finger...");
    Serial.print("Sensor contains "); Serial.print(finger.templateCount); Serial.println(" templates");
  }
  delay(5000);

}

void loop()                     // run over and over again
{
  delay(100);  
  lcd.setCursor(0, 0);
  lcd.print("EXAM AUTH SYS");
  int fingerprint_id = getFingerprintID();
   
}

uint8_t getFingerprintID() {
  match_found = false;
  uint8_t p = finger.getImage();
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    case FINGERPRINT_NOFINGER:
      Serial.println("No finger detected");
      lcd.setCursor(0, 1);
      lcd.print("PLACE FINGER");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  // OK success!

  p = finger.image2Tz();
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
  p = finger.fingerFastSearch();
  if (p == FINGERPRINT_OK) {
    Serial.println("Found a print match!");
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_NOTFOUND) {
    Serial.println("Did not find a match");
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }

  // found a match!
  Serial.print("Found ID #"); Serial.print(finger.fingerID);
  Serial.print(" with confidence of "); Serial.println(finger.confidence);

  match_found = true;
  mark_register(int(finger.fingerID));
  return finger.fingerID;
}

// returns -1 if failed, otherwise returns ID #
int getFingerprintIDez() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK)  return -1;

  // found a match!
  Serial.print("Found ID #"); Serial.print(finger.fingerID);
  Serial.print(" with confidence of "); Serial.println(finger.confidence);
  return finger.fingerID;
}
