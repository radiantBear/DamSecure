<x-layout>
    <x-slot:title>API Schema</x-slot:title>
    <x-slot:includes>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css">
        <link rel="stylesheet" href="/resources/css/swagger-dark.css">
    </x-slot:includes>

    <div id="swagger"></div>

    <script crossorigin src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js"></script>
    <script>
        SwaggerUIBundle({
            url: "../../docs/openapi.yaml",
            dom_id: "#swagger",
        });
    </script>
</x-layout>
