#include <HTTPClient.h>
#include <NetworkClientSecure.h>
const char *rootCACertificate = R"string_literal(
-----BEGIN CERTIFICATE-----
MIIF3jCCA8agAwIBAgIQAf1tMPyjylGoG7xkDjUDLTANBgkqhkiG9w0BAQwFADCB
iDELMAkGA1UEBhMCVVMxEzARBgNVBAgTCk5ldyBKZXJzZXkxFDASBgNVBAcTC0pl
cnNleSBDaXR5MR4wHAYDVQQKExVUaGUgVVNFUlRSVVNUIE5ldHdvcmsxLjAsBgNV
BAMTJVVTRVJUcnVzdCBSU0EgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwHhcNMTAw
MjAxMDAwMDAwWhcNMzgwMTE4MjM1OTU5WjCBiDELMAkGA1UEBhMCVVMxEzARBgNV
BAgTCk5ldyBKZXJzZXkxFDASBgNVBAcTC0plcnNleSBDaXR5MR4wHAYDVQQKExVU
aGUgVVNFUlRSVVNUIE5ldHdvcmsxLjAsBgNVBAMTJVVTRVJUcnVzdCBSU0EgQ2Vy
dGlmaWNhdGlvbiBBdXRob3JpdHkwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIK
AoICAQCAEmUXNg7D2wiz0KxXDXbtzSfTTK1Qg2HiqiBNCS1kCdzOiZ/MPans9s/B
3PHTsdZ7NygRK0faOca8Ohm0X6a9fZ2jY0K2dvKpOyuR+OJv0OwWIJAJPuLodMkY
tJHUYmTbf6MG8YgYapAiPLz+E/CHFHv25B+O1ORRxhFnRghRy4YUVD+8M/5+bJz/
Fp0YvVGONaanZshyZ9shZrHUm3gDwFA66Mzw3LyeTP6vBZY1H1dat//O+T23LLb2
VN3I5xI6Ta5MirdcmrS3ID3KfyI0rn47aGYBROcBTkZTmzNg95S+UzeQc0PzMsNT
79uq/nROacdrjGCT3sTHDN/hMq7MkztReJVni+49Vv4M0GkPGw/zJSZrM233bkf6
c0Plfg6lZrEpfDKEY1WJxA3Bk1QwGROs0303p+tdOmw1XNtB1xLaqUkL39iAigmT
Yo61Zs8liM2EuLE/pDkP2QKe6xJMlXzzawWpXhaDzLhn4ugTncxbgtNMs+1b/97l
c6wjOy0AvzVVdAlJ2ElYGn+SNuZRkg7zJn0cTRe8yexDJtC/QV9AqURE9JnnV4ee
UB9XVKg+/XRjL7FQZQnmWEIuQxpMtPAlR1n6BB6T1CZGSlCBst6+eLf8ZxXhyVeE
Hg9j1uliutZfVS7qXMYoCAQlObgOK6nyTJccBz8NUvXt7y+CDwIDAQABo0IwQDAd
BgNVHQ4EFgQUU3m/WqorSs9UgOHYm8Cd8rIDZsswDgYDVR0PAQH/BAQDAgEGMA8G
A1UdEwEB/wQFMAMBAf8wDQYJKoZIhvcNAQEMBQADggIBAFzUfA3P9wF9QZllDHPF
Up/L+M+ZBn8b2kMVn54CVVeWFPFSPCeHlCjtHzoBN6J2/FNQwISbxmtOuowhT6KO
VWKR82kV2LyI48SqC/3vqOlLVSoGIG1VeCkZ7l8wXEskEVX/JJpuXior7gtNn3/3
ATiUFJVDBwn7YKnuHKsSjKCaXqeYalltiz8I+8jRRa8YFWSQEg9zKC7F4iRO/Fjs
8PRF/iKz6y+O0tlFYQXBl2+odnKPi4w2r78NBc5xjeambx9spnFixdjQg3IM8WcR
iQycE0xyNN+81XHfqnHd4blsjDwSXWXavVcStkNr/+XeTWYRUc+ZruwXtuhxkYze
Sf7dNXGiFSeUHM9h4ya7b6NnJSFd5t0dCy5oGzuCr+yDZ4XUmFF0sbmZgIn/f3gZ
XHlKYC6SQK5MNyosycdiyA5d9zZbyuAlJQG03RoHnHcAP9Dc1ew91Pq7P8yF1m9/
qS3fuQL39ZeatTXaw2ewh0qpKJ4jjv9cJ2vhsE/zB+4ALtRZh8tSQZXq9EfX7mRB
VXyNWQKV3WKdwrnuWih0hKWbt5DHDAff9Yk2dDLWKMGwsAvgnEzDHNb842m1R0aB
L6KCq9NjRHDEjf8tM7qtj3u1cIiuPhnPQCjY/MiQu12ZIvVS5ljFH4gxQ+6IHdfG
jjxDah2nGN59PRbxYvnKkKj9
-----END CERTIFICATE-----
)string_literal"; // Root certificate for upload_url. Valid until 2038
const char *upload_url = "https://eecs.engineering.oregonstate.edu/education/damsecure/public/api/data";
const char *api_token = "Bearer 1|lBIopC6bcRzL7Jb2yopy74yTJkVltziyLVNX8V3Gc4eb41df";

#include <WiFi.h>
#define EAP_IDENTITY "onid@oregonstate.edu"
#define EAP_USERNAME "onid@oregonstate.edu"
#define EAP_PASSWORD "eduroam-password"
const char *ssid = "eduroam";


void connectWiFi();

void uploadData(int data);
void sendRequest(NetworkClientSecure *client, int data);
void processResponse(HTTPClient *https, int httpCode);


void setup() {
    Serial.begin(115200);
    delay(10);
    
    connectWiFi();
}


void loop() {
    static int iterations = 1;
    iterations++;
    
    uploadData(iterations);

    delay(1000 * 15);
}


void connectWiFi() {
    int counter = 0;

    Serial.printf("Connecting to network: %s\n", ssid);

    WiFi.disconnect(true);
    WiFi.mode(WIFI_STA);
    WiFi.begin(ssid, WPA2_AUTH_PEAP, EAP_IDENTITY, EAP_USERNAME, EAP_PASSWORD);

    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
        counter++;
        if (counter >= 60) {
            // Unable to connect after 30 seconds timeout - reset board
            ESP.restart();
        }
    }

    Serial.println("WiFi connected");
}


void uploadData(int data) {
    NetworkClientSecure *client = new NetworkClientSecure;

    if (!client) {
        Serial.println("Unable to allocate client");
        return;
    }

    client->setCACert(rootCACertificate);
    sendRequest(client, data);
    delete client;
}


void sendRequest(NetworkClientSecure *client, int data) {
    // Using a different scoping block (function) for HTTPClient https to make sure it is
    // destroyed before NetworkClientSecure *client is
    HTTPClient https;

    Serial.println("Connecting to DamSecure...");
    if (!https.begin(*client, upload_url)) {
        Serial.println("Unable to connect to DamSecure");
        return;
    }

    Serial.println("Making POST request...");
    https.addHeader("Accept", "application/json");
    https.addHeader("Authorization", api_token);
    https.addHeader("Content-Type", "text/plain");
    int httpCode = https.POST(String(data));

    processResponse(&https, httpCode);
    https.end();
}


void processResponse(HTTPClient *https, int httpCode) {
    // httpCode will be negative on error
    if (httpCode <= 0) {
        Serial.print("POST failed, error: ");
        Serial.println(https->errorToString(httpCode).c_str());
        return;
    }
    
    // HTTP request has been sent and server response header has been handled
    Serial.printf("POST response code: %d\n", httpCode);

    // file found at server
    if (
        httpCode == HTTP_CODE_OK ||
        httpCode == HTTP_CODE_CREATED ||
        httpCode == HTTP_CODE_MOVED_PERMANENTLY
    ) {
        String payload = https->getString();
        Serial.print("POST accepted, id: ");
        Serial.println(payload);
    }
}
