import json
import os
from urllib.request import urlopen, Request


def download(url, token):
    request = Request(
        url=url,
        method="GET",
        headers={
            "Accept": "application/json",
            "Authorization": f"Bearer {token}"
        }
    )

    with urlopen(request) as response:
        body = json.loads(response.read().decode("utf-8"))
        
        print(f"Status: {response.status}")
        print("Response:")
        for line in body:
            print(line)


def main():
    API_DOMAIN = os.environ.get("API_DOMAIN")
    API_TOKEN = os.environ.get("API_TOKEN")

    if not API_DOMAIN or not API_TOKEN:
        print("API_DOMAIN and API_TOKEN environment variables must be set", file=sys.stderr)
        sys.exit(1)

    url = API_DOMAIN.rstrip("/") + "/api/data"

    download(url, API_TOKEN)


if __name__ == '__main__':
    main()
