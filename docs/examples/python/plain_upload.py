import os
import random
import time
from urllib.request import urlopen, Request


def get_temp():
    return random.randint(50, 80)


def upload(url, token, payload):
    request = Request(
        url=url,
        data=payload.encode("utf-8"),
        method="POST",
        headers={
            "Accept": "application/json",
            "Authorization": f"Bearer {token}",
            "Content-Type": "text/plain"
        }
    )

    with urlopen(request) as response:
        print(f"Status: {response.status}")
        print(f"Response: {response.read().decode("utf-8")}")


def main():
    API_DOMAIN = os.environ.get("API_DOMAIN")
    API_TOKEN = os.environ.get("API_TOKEN")

    if not API_DOMAIN or not API_TOKEN:
        print("API_DOMAIN and API_TOKEN environment variables must be set", file=sys.stderr)
        sys.exit(1)

    url = API_DOMAIN.rstrip("/") + "/api/data"

    while True:
        upload(url, API_TOKEN, f"John Doe|{get_temp()}|some other text here")
        time.sleep(2)


if __name__ == '__main__':
    main()
