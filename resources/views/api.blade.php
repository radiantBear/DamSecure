<x-layout>
    <x-slot:title>API Schema</x-slot:title>
    <x-slot:includes>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.31.2/swagger-ui.css">
        <link rel="stylesheet" href="../resources/css/swagger-dark.css">
    </x-slot:includes>

    <div id="swagger"></div>

    <script crossorigin src="https://unpkg.com/swagger-ui-dist@5.31.2/swagger-ui-bundle.js"></script>
    <script>
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
                    requestSnippetsEnabled: true,
                    requestSnippets: {
                        generators: {
                            curl_bash: { title: "cURL (bash)", syntax: "bash" },
                            curl_powershell: { title: "cURL (PowerShell)", syntax: "powershell" },
                            curl_cmd: {},
                        },
                        defaultExpanded: true,
                        languages: ['curl_bash', 'curl_powershell']
                    },
                    spec
                });
            })
    </script>
</x-layout>
