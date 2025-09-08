#include <CO2Sensor.h>

#include <DFRobot_OxygenSensor.h>
#include "CO2Sensor.h"

// Sensor pins
#define MG811_PIN A1
#define MQ7_PIN A2

#define Oxygen_IICAddress ADDRESS_3
#define COLLECT_NUMBER 10

#define NUM_SAMPLES 10
int mq7_samples[NUM_SAMPLES], mg811_samples[NUM_SAMPLES];
int mq7_index = 0, mg811_index = 0;

CO2Sensor co2Sensor(MG811_PIN, 0.99, 100);
DFRobot_OxygenSensor oxygen;

void setup() {
  Serial.begin(9600);
  Serial2.begin(9600);
  Wire.begin();

  co2Sensor.calibrate();

  while (!oxygen.begin(Oxygen_IICAddress)) {
    Serial.println("Oxygen sensor not detected!");
    delay(1000);
  }

  Serial.println("Sensors initialized...");
}

int smoothReading(int* samples, int& index, int raw) {
  samples[index] = raw;
  index = (index + 1) % NUM_SAMPLES;

  long sum = 0;
  for (int i = 0; i < NUM_SAMPLES; i++) {
    sum += samples[i];
  }
  return sum / NUM_SAMPLES;
}

void loop() {
  // Sensor reading
  int mq7_raw = analogRead(MQ7_PIN);
  int mg811_raw = co2Sensor.read();

  int mq7_value = smoothReading(mq7_samples, mq7_index, mq7_raw);
  int co2_value = smoothReading(mg811_samples, mg811_index, mg811_raw);

  float o2_value = oxygen.getOxygenData(COLLECT_NUMBER);

  // Print to Serial Monitor
  Serial.print("MQ7: "); Serial.print(mq7_value);
  Serial.print(" | CO2: "); Serial.print(co2_value);
  Serial.print(" | O2: "); Serial.print(o2_value); Serial.println(" %");

  // Send to ESP32
  Serial2.print(mq7_value); Serial2.print(",");
  Serial2.print(co2_value); Serial2.print(",");
  Serial2.println(o2_value, 2);

  delay(1000);
}
