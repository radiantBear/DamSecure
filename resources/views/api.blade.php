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
                    console.log(reqBody)

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
                    console.log(stringBody)

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
                        SnippedGeneratorPythonPlugin
                    ],
                    requestSnippetsEnabled: true,
                    requestSnippets: {
                        generators: {
                            curl_bash: { title: "cURL (bash)", syntax: "bash" },
                            curl_powershell: { title: "cURL (PowerShell)", syntax: "powershell" },
                            curl_cmd: {},
                            python: { title: "Python", syntax: "python" },
                        },
                        defaultExpanded: true,
                        languages: ['python', 'curl_bash', 'curl_powershell']
                    },
                    spec
                });
            })
    </script>
</x-layout>
