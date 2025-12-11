<x-layout>
    <x-slot:title>Home</x-slot:title>

    <section 
        class="text-white text-center row mb-4 rounded-4"
        style="background: linear-gradient(to bottom right, #D73F09 0%, #fff0 100%); margin-top: -.75rem; padding-top: 6rem; padding-bottom: 6rem;"
    >
        <div class="container">
            <h1 class="display-3">DamSecure IoT Portal</h1>
            <p class="lead mb-4">Connect your project to the Internet with ease</p>
            <a href="projects" class="btn btn-lg btn-light text-primary fw-bold" style="color: #D73F09 !important; box-shadow: #fff6 0px 10px 15px -3px, #fff6 0px 4px 6px -4px;">
                Get Started
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <div class="row">
        <div class="col-sm">
            <h2>What can I upload?</h2>
            <p>
                You can upload JSON data, CSV data, or raw text. JSON and CSV data will be
                parsed and displayed in a table on the website. Each upload is limited to
                65,535 characters, but you can upload as many times as needed. If you need
                to upload more than 65 KB at time, you will need to split the upload into
                several parts. Upload timestamps are automatically tracked and displayed
                alongside the data.
            </p>
        </div>
        <div class="col-sm">
            <h2>How do I upload data?</h2>
            <p>
                After logging in with your ONID and creating a project, you will be given
                an an initial API key for uploading data. Uploading is as simple as making
                an HTTP POST request to the <code>public/api/data</code> endpoint on this
                site, including a header with the key <code>Authorization</code> and
                contents matching <code>Bearer {api_key}</code> <i>(where 
                <code>{api_key}</code>, including the brackets, is replaced with the key
                you were given)</i> to indicate which project the upload is for. For more
                details, check out the <a href="docs/api">API schema</a>.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h2>How do I access my data after it's uploaded?</h2>
            <p>
                If you just need to upload data and view/share it, simply reopen the
                project on this site. If you need to manipulate/process the data, it can
                also be exported as a CSV file (coming soon) or retrieved using the
                download API key. To share the data with others, you can simply click the
                project's "permissions" button to invite collaborators or viewers by ONID.
            </p>
        </div>
        <div class="col-sm">
            <h2>How long is my data retained?</h2>
            <p>
                All data uploaded to a given project will be preserved for 2 years after
                the last upload. Once a project has received no uploads for 2 years, it
                will be auto-deleted.
            </p>
        </div>
    </div>
    <div class="row">
        <h2 class="mb-3">What does the web view look like?</h2>
        <div class="col-sm">
            <img class="img-fluid" src="img/json_table.png" alt="JSON data parsed into a table">
            <p>
                Data uploaded with a <code>Content-Type: application/json</code> header
                will be parsed as JSON and rejected if it is improperly formatted. When
                viewed, the top-level fields will be parsed to form table columns.
            </p>
        </div>
        <div class="col-sm">
            <img class="img-fluid" src="img/csv_table.png" alt="CSV data parsed into a table">
            <p>
                Data uploaded with a <code>Content-Type: text/csv</code> header
                will be parsed as CSV data using the rules of PHP's
                <code>str_getcsv()</code> function. When viewed, the fields will be parsed
                to form table columns. Since CSV fields are unnamed, columns will always
                be filled left-to-right with corresponding fields' data.
            </p>
        </div>
        <div class="col-sm">
            <img class="img-fluid" src="img/plain_table.png" alt="Raw data parsed into a table">
            <p>
                Data uploaded with a <code>Content-Type: text/plain</code> header (or no
                <code>Content-Type</code> header) will be left untouched. Upload
                timestamps will still be displayed along with the data.
            </p>
        </div>
    </div>
</x-layout>