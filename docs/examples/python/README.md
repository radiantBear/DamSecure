# Python Examples

These examples show how different DamSecure features can be used via Python on a normal
(i.e. non-embedded) computer with widely used Python packages.

Every script requires an `API_DOMAIN` environment variable that indicates the base URL for
the API, such as `https://eecs.engineering.oregonstate.edu/education/damsecure/public`.
They also require an `API_TOKEN` environment variable with a valid token for your project
(with the correct upload vs download permissions).

In Bash, these environment variables can be easily set when calling the program, e.g. like
this:

```bash
API_DOMAIN="https://eecs.engineering.oregonstate.edu/education/damsecure/public" API_TOKEN="43|5dMoMkrPAcoMiaANzEO9xNkwHYm3kCmZ73VsizEK484bfb6b" python3 json_upload.py
```
