<x-layout>
    <x-slot:title>API Schema</x-slot:title>
    <x-slot:includes>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.31.2/swagger-ui.css">
        <link rel="stylesheet" href="../resources/css/swagger-dark.css">
    </x-slot:includes>

    <div id="swagger"></div>

    <script crossorigin src="https://unpkg.com/swagger-ui-dist@5.31.2/swagger-ui-bundle.js"></script>
    <script>
        // Define Request Snippet for displaying Python code example
        // https://swagger.io/docs/open-source-tools/swagger-ui/customization/plug-points/#request-snippets
        const SnippedGeneratorPythonPlugin = {
            fn: {
                requestSnippetGenerator_python: (request) => {
                    const headers = request.get("headers");
                    let reqBody = request.get("body")
                    if (reqBody)
                        reqBody = JSON.stringify(reqBody)

                    const showBody = reqBody || (
                        request.get("method") !== "GET" && request.get("method") !== "DELETE"
                    );

                    const stringBody = "(\n" +
                        (
                            (reqBody || "")
                            .replace(/\\n/g, "\n")
                            .replace(/\n/g, "\"\n")
                            .substring(1)
                            .split("\n")
                            .map(l => `    "${l}`)
                            .join("\n")
                        )
                        + "\n  ).encode(\"utf-8\"),";

                    return (
`from urllib.request import urlopen, Request

request = Request(
  url="${request.get("url")}"${showBody ? `,
  data=${reqBody ? stringBody : "\"\""}` : "," }
  method="${request.get("method").toUpperCase()}"${headers && headers.size ? `,
  headers={
    ${request.get("headers").map((val, key) => `"${key}": "${val}"`).valueSeq().join(",\n    ")}
  }` : ""}
)

with urlopen(request) as response:
  print(f"Status: {response.status}")
  
  # If uploading succeeds, this gives the ID for updating/deleting the upload
  print(f"Response: {response.read().decode("utf-8")}")`
                    );
                }
            }
        }
        
        // Define Request Snippet for displaying Arduino code example
        // https://swagger.io/docs/open-source-tools/swagger-ui/customization/plug-points/#request-snippets
        const SnippedGeneratorArduinoPlugin = {
            fn: {
                requestSnippetGenerator_arduino: (request) => {
                    const hostname = new URL(request.get("url")).hostname
                    const headers = request.get("headers");
                    const api_token = request.get('headers').get('Authorization');

                    let reqBody = request.get("body")
                    if (reqBody)
                        reqBody = JSON.stringify(reqBody)

                    const showBody = reqBody || (
                        request.get("method") !== "GET" && request.get("method") !== "DELETE"
                    );

                    const stringBody = ((reqBody || "")
                        .replace(/\\n/g, "\n")
                        .replace(/\n/g, "\"\n")
                        .substring(1)
                        .split("\n")
                        .map((l, i) => i > 0 ? `                           "${l}` : l)
                        .join("\n")
                        .slice(0, -1)
                        );

                    return (
`#include <HTTPClient.h>
#include <NetworkClientSecure.h>

const char *rootCACertificate = ""; // TODO: Get root CA certificate for ${hostname}
const char *damsecure_url = "${request.get("url")}";
const char *api_token = "${api_token ? api_token + '";' : '"; // TODO: Get API token'}${showBody ? `
const char *request_body = "${stringBody}";` : '' }

void makeRequest() {
  NetworkClientSecure *client = new NetworkClientSecure;

  if (!client) {
    Serial.println("Unable to allocate client");
    return;
  }

  client->setCACert(rootCACertificate);
  _sendRequest(client, data);
  delete client;
}

void _sendRequest(NetworkClientSecure *client) {
  // Using a different scoping block (function) for HTTPClient https to make sure it is
  // destroyed before NetworkClientSecure *client is
  HTTPClient https;

  Serial.println("Connecting to DamSecure...");
  if (!https.begin(*client, damsecure_url)) {
    Serial.println("Unable to connect to DamSecure");
    return;
  }

  Serial.println("Making ${request.get('method')} request...");
  https.addHeader("Authorization", api_token);
  ${
    request.get("headers")
      .map((val, key) => `https.addHeader("${key}", "${val}");`)
      .valueSeq()
      .filter(val => !val.startsWith('https.addHeader("Authorization",'))
      .join("\n  ")}
  int httpCode = https.${request.get('method')}(${showBody ? 'request_body' : ''});

  processResponse(&https, httpCode);
  https.end();
}

void processResponse(HTTPClient *https, int httpCode) {
  // httpCode will be negative on error
  if (httpCode <= 0) {
    Serial.print("${request.get('method')} failed, error: ");
    Serial.println(https->errorToString(httpCode).c_str());
    return;
  }
    
  // HTTP request has been sent and server response header has been handled
  Serial.printf("${request.get('method')} response code: %d\\n", httpCode);

  // server accepted request
  if (
    httpCode == HTTP_CODE_OK ||
    httpCode == HTTP_CODE_CREATED ||
    httpCode == HTTP_CODE_MOVED_PERMANENTLY
  ) {
    String payload = https->getString();
    Serial.print("${request.get('method')} accepted; response: ");
    Serial.println(payload);
  }
}

void main() {
  Serial.begin(115200);

  // TODO: Connect to Internet (WiFi, Ethernet, etc.)

  makeRequest();
}`
                    );
                }
            }
        }

        fetch("../docs/openapi.json")
            .then(res => res.text())
            .then(json => {
                const spec = JSON.parse(json);

                spec.servers = [{
                    url: "{{ config('app.url') }}/public/api"
                }]

                SwaggerUIBundle({
                    dom_id: "#swagger",
                    defaultModelRendering: "model",
                    defaultModelExpandDepth: 2, // Expand models in route bodies
                    defaultModelsExpandDepth: -1, // Hide models at the bottom of the page
                    plugins: [
                        SnippedGeneratorPythonPlugin,
                        SnippedGeneratorArduinoPlugin
                    ],
                    requestSnippetsEnabled: true,
                    requestSnippets: {
                        generators: {
                            curl_bash: { title: "cURL (bash)", syntax: "bash" },
                            curl_powershell: { title: "cURL (PowerShell)", syntax: "powershell" },
                            curl_cmd: {},
                            python: { title: "Python", syntax: "python" },
                            arduino: { title: "ESP-32 (Arduino)", syntax: "c" }
                        },
                        defaultExpanded: true,
                        languages: ['python', "arduino", 'curl_bash', 'curl_powershell']
                    },
                    spec
                });
            })
    </script>
</x-layout>
