import os
import random
import time
from urllib.request import urlopen, Request


def get_next_number():
    """
    Just need to create some example data
    """
    number = 0
    while True:
        number += 1
        yield number


def upload(url, token, payload):
    """
    Actually perform the upload
    """
    request = Request(
        url=url + "/api/data",
        data=payload.encode("utf-8"), # Python's Request requires data to be binary-encoded, not just a Python string
        method="POST",
        headers={
            "Accept": "application/json",       # Tells DamSecure to respond with JSON, not HTML
            "Authorization": f"Bearer {token}", # Tells DamSecure what project this is for
            "Content-Type": "text/plain"        # Tells DamSecure this is plaintext data
        }
    )

    with urlopen(request) as response:
        print(f"Status: {response.status}")

        # If uploading succeeds, this gives the ID for updating/deleting the upload
        print(f"Response: {response.read().decode("utf-8")}")


def main():
    """
    Just some setup... This can be ignored for learning how to interact with DamSecure
    """
    API_DOMAIN = os.environ.get("API_DOMAIN")
    API_TOKEN = os.environ.get("API_TOKEN")

    if not API_DOMAIN or not API_TOKEN:
        print("API_DOMAIN and API_TOKEN environment variables must be set", file=sys.stderr)
        sys.exit(1)

    while True:
        upload(API_DOMAIN.rstrip("/"), API_TOKEN, f"John Doe|{get_next_number()}|some other text here")
        time.sleep(2)


if __name__ == '__main__':
    main()
