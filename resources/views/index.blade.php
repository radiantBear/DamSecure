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

    <x-sample-data />

    <h2>FAQ</h2>

    <h3 class="mt-3 mb-2">General</h3>
    <div class="accordion my-2" id="faqAccordion">
        <x-accordion-item show accordionId="faqAccordion" id="collapseWhatUpload">
            <x-slot:header>What can I upload?</x-slot:header>

            You can upload JSON data, CSV data, or raw text. JSON and CSV data will be
            parsed and displayed in a table on the website. Upload timestamps are
            automatically tracked and displayed alongside the data.
        </x-accordion-item>
        <x-accordion-item accordionId="faqAccordion" id="collapseUploadLimits">
            <x-slot:header>Are there any limits on uploads?</x-slot:header>

            There is no limit to the total number of uploads you can make. However, each
            upload is limited to 65,535 characters. If you need to upload more than 65 KB
            at time, you will need to split the upload into several parts.
        </x-accordion-item>
        <x-accordion-item accordionId="faqAccordion" id="collapseRetention">
            <x-slot:header>How long is my data retained?</x-slot:header>

            All data uploaded to a given project will be preserved for 2 years after the
            last upload. Once a project has received no uploads for 2 years, it will be
            automatically deleted.
        </x-accordion-item>
    </div>
    
    <h3 class="mt-3 mb-2">Usage</h3>
    <div class="accordion" id="usageAccordion">
        <x-accordion-item accordionId="usageAccordion" id="collapseHowUpload">
            <x-slot:header>How do I upload data?</x-slot:header>

            After logging in with your ONID and creating a project, you will be given an
            initial API key for uploading data. Uploading is as simple as making an HTTP
            POST request to the <code>public/api/data</code> endpoint on this site,
            including a header with the key <code>Authorization</code> and contents
            matching <code>Bearer {api_key}</code> <i>(where <code>{api_key}</code>,
            including the brackets, is replaced with the key you were given)</i> to
            indicate which project the upload is for. For more details, check out the
            <a href="docs/api">API schema</a> or
            <a href="https://github.com/radiantBear/DamSecure/tree/main/docs/examples" target="_blank">
                code examples
            </a>.
        </x-accordion-item>
        <x-accordion-item accordionId="usageAccordion" id="collapseAccessUpload">
            <x-slot:header>How do I access my data after it's uploaded?</x-slot:header>

            If you just need to upload data and view/share it, simply reopen the project
            on this site. If you need to manipulate/process the data, it can also be
            exported as a CSV file (coming soon) or retrieved using the download API key.
            To share the data with others, you can simply click the project's
            "permissions" button to invite collaborators or viewers by ONID.
        </x-accordion-item>
        <x-accordion-item accordionId="usageAccordion" id="collapseHostDownload">
            <x-slot:header>Can I just host my data to download?</x-slot:header>

            Yes! If you just need to test that your project can download and parse your
            data, you can do this without worrying about manually uploading data. After
            creating you project in DamSecure, simply use the Test Data section to
            configure the data that <code>GET public/api/data/test</code> will serve.
        </x-accordion-item>
    </div>
</x-layout>
