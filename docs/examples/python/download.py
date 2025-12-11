import json
import os
from urllib.request import urlopen, Request


def download(url, token):
    request = Request(
        url=url + "/api/data",
        method="GET",
        headers={
            "Accept": "application/json",      # Tells DamSecure to respond with JSON, not HTML
            "Authorization": f"Bearer {token}" # Tells DamSecure what project to retrieve data for
        }
    )

    with urlopen(request) as response:
        body = json.loads(response.read().decode("utf-8")) # Decode and parse the JSON array
        
        print(f"Status: {response.status}")
        print("Response:")

        # DamSecure returned an array of all uploads for the project, so we'll print them
        # line by line
        for line in body:
            print(line)


def main():
    """
    Just some setup... This can be ignored for learning how to interact with DamSecure
    """
    API_DOMAIN = os.environ.get("API_DOMAIN")
    API_TOKEN = os.environ.get("API_TOKEN")

    if not API_DOMAIN or not API_TOKEN:
        print("API_DOMAIN and API_TOKEN environment variables must be set", file=sys.stderr)
        sys.exit(1)

    download(API_DOMAIN.rstrip("/"), API_TOKEN)


if __name__ == '__main__':
    main()
