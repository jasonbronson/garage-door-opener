
/*
* Compatible with 1.6.1 Arduino software
* Author Jbronson 2015
*/

#include <SPI.h>
#include <Dhcp.h>
#include <Dns.h>
#include <Ethernet.h>
#include <EthernetClient.h>
#include <EthernetServer.h>
#include <EthernetUdp.h>
#include <SD.h>

boolean reading = false;

////////////////////////////////////////////////////////////////////////
//CONFIGURE
////////////////////////////////////////////////////////////////////////
  byte ip[] = { 192, 168, 1, 50 };   //ip address to assign the arduino
  byte gateway[] = { 192, 168, 1, 1 }; //ip address of the gateway or router

  //Rarly need to change this
  byte subnet[] = { 255, 255, 255, 0 };

  // if need to change the MAC address (Very Rare)
  byte mac[] = {  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xBE }; //Mac

  EthernetServer server = EthernetServer(80); //port 80
  const int switchPin = 10; 
  int door;
  
  
////////////////////////////////////////////////////////////////////////

void setup(){
  //Pins 10,11,12 & 13 are used by the ethernet shield

  pinMode(2, OUTPUT);
  pinMode(3, OUTPUT);
  pinMode(4, OUTPUT);
  pinMode(5, OUTPUT);
  pinMode(6, OUTPUT);
  pinMode(7, OUTPUT);
  pinMode(8, OUTPUT);
  pinMode(9, OUTPUT);

  Serial.begin(9600);
  pinMode(switchPin, INPUT);        // switchPin is an input
  digitalWrite(switchPin, HIGH);    // Activate internal pullup resistor
  
  Serial.println(F("Starting ethernet..."));
  Ethernet.begin(mac, ip, gateway, subnet);
  server.begin();
  Serial.println(F("Network Ready"));
  
}

void loop(){
 
  // listen for incoming clients, and process qequest.
  checkForClient();
  
}

void checkForClient(){

  EthernetClient client = server.available();

  if (client) {

    // an http request ends with a blank line
    boolean currentLineIsBlank = true;
    boolean sentHeader = false;
    int count = 1;
    boolean reading = false;
    
    while (client.connected()) {
     if (client.available()) {   // client data available to read
      
      char c = client.read();
      
      if(!sentHeader){
       
          client.println("HTTP/1.1 200 OK");
          client.println("Content-Type: text/html");
          client.println("Connection: close");
          client.println(); 
          sentHeader = true;
          Serial.println("Header Sent ");
      }
      
      if (c == '\n' && currentLineIsBlank) {
          break;
      }
      
      Serial.println(c);
      
      if(c == '?'){ 
          reading = true; //found the ?, begin reading the info
          Serial.println("Found ? ");
      }
        
      if(reading){
        
         switch (c) {
            case '6':
              Serial.println("Trigging pin 6 ");
                triggerPin(6, client);
              break;
            case '9':
              door = digitalRead(switchPin);
              if(door == 1){
                Serial.println("door open");
                client.print("Garage door open");
              }else{
                Serial.println("door closed");
                client.print("Garage door closed");
              }
              delay(1);
              
            break;
          }
        Serial.println("Reading...");      
      }
      
      
      
      
     } //client available
      
    } // while loop

    delay(1); // give the web browser time to receive the data
    client.stop(); // close the connection:

  } 

}

void triggerPin(int pin, EthernetClient client){

  client.print("Garage Door triggered <br>");
  
  digitalWrite(pin, HIGH);
  delay(1000);
  digitalWrite(pin, LOW);
  delay(1000);
}
