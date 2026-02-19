#include <HTTPClient.h>
#include <WiFi.h>

#define API_URL "https://eecs.engineering.oregonstate.edu/education/damsecure/public/api/data"
#define TOKEN   ""
#define SSID    ""
#define PASSWORD ""

const char* ca = \
"-----BEGIN CERTIFICATE-----\n"\
"..."\
"-----END CERTIFICATE-----\n";

int getNextNumber() {
    static int number = 0;
    return number++;
}

void connectToWiFi() {
    WiFi.disconnect(true);
    WiFi.begin(SSID, PASSWORD);
    Serial.print("Connecting to WiFi");
    while (WiFi.status() != WL_CONNECTED) {
        Serial.print('.');
        delay(500);
    }
}

void setup() {
    Serial.begin(115200);

    connectToWiFi();
    HTTPClient http;
    http.begin(API_URL, ca);

    http.addHeader("Accept", "application/json");
    http.addHeader("Content-Type", "text/plain");
    http.addHeader("Authorization", "Bearer " TOKEN);

    int status = http.POST(String(getNextNumber()));

    Serial.print("Status: ");
    Serial.println(status);

    Serial.print("Response: ");
    Serial.println(http.getString());

    http.end();
    WiFi.disconnect();
}
