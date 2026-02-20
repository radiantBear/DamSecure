# Usability Testing

## Prompts
You are working on a project that requires you to upload data to the Internet for
further analysis. While solving the following problems, please use the Internet and
reference documentation the same way you would when completing this project indepentently.

1.  You have heard that the [the DamSecure portal][homepage] provides a
    way for you to upload data and view it. Please visit the link and find a way to set
    it up such that you could upload data.
    - How confident were you that creating a new project was the right action?
    - What would have boosted your confidence?
1. You are working with a teammate whose ONID is `bairdn`. Please find a way to give them
    access to work with you.
    - How hard was it to decide which role they should receive?
1. You also have a stakeholder whose ONID is `beaverb`. Please find a way to give them
    access to view the data, but minimize the level of permissions they have since they
    may accidently create problems if given too much power.
    - How confident are you that they don't have more permissions than they need?
1. What URL do you need to make requests to in order to upload data?
    - *Observe what order they search for the needed information in*
    - How intuitive was it to check the API schema and fit the URL parts together?
1. Please modify this code snippet so that it correctly uploads data where you can see it:
    - Python
        ```py
        from urllib.request import urlopen, Request

        API_DOMAIN = "https://eecs.engineering.oregonstate.edu/education/damsecure/public"

        request = Request(
            url=API_DOMAIN + "/api/data",
            data="Hello world!".encode("utf-8"),
            method="POST",
            headers={
                "Accept": "application/json",
                "Content-Type": "text/plain"
            }
        )

        with urlopen(request) as response:
            print(f"Status: {response.status}")
            print(f"Response: {response.read().decode("utf-8")}")
        ```
    - Or Arduino
        ```cpp
        #define API_URL "https://eecs.engineering.oregonstate.edu/education/damsecure/public/api/data"

        const char* ca = "..." // OK to treat this as a valid CA certificate

        void setup() {
            Serial.begin(115200);

            connectToWiFi();
            HTTPClient http;
            http.begin(API_URL, ca);

            http.addHeader("Accept", "application/json");
            http.addHeader("Content-Type", "text/plain");

            int status = http.POST("Hello world!", 12);

            Serial.print("Status: ");
            Serial.println(status);

            Serial.print("Response: ");
            Serial.println(http.getString());

            http.end();
            WiFi.disconnect();
        }
        ```
    - *Will judge difficulty by how many questions/attempts it takes to correctly modify
        code*
1. Please open the page on [the DamSecure portal][homepage] that allows you to view this
    upload.
    - How confident were you that repoening the Project page would display this data? What
        would've helped you understand this quicker?
1. You need to load all the data you've uploaded into a program to analyze it. Please make
    any necessary changes in [the DamSecure portal][homepage] and to this code so that it
    downloads the records:
    ```py
    from urllib.request import urlopen, Request

    API_DOMAIN = "https://eecs.engineering.oregonstate.edu/education/damsecure/public"

    request = Request(
        url=url + "/api/data",
        method="GET",
        headers={
            "Accept": "application/json",
        }
    )

    with urlopen(request) as response:
        body = json.loads(response.read().decode("utf-8")) # Decode and parse the JSON array
        
        print(f"Status: {response.status}")
        print("Response:")
        for line in body:
            print(line)
    ```
1. Your teammate just quit. To preserve the integrity of your data, please revoke their
    access to it.
    - How confident are you that they can no longer access data in any way?
    - What about if they still have a copy of the API tokens?

## Interviewees
- Find 10 users

[homepage]: https://eecs.engineering.oregonstate.edu/education/damsecure/public/