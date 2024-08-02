import json
import time
import base64
import hmac
import hashlib

def get_sso_user():

    # Construct the user object
    # See: https://talk.hyvor.com/docs/sso-stateless#user-object for available properties
    user = {
        "timestamp": int(time.time()),
        "id": 1,
        "name": "John Doe",
        "email": "john@doe.com",
        "picture_url": None,
        "website_url": None,
    }

    # JSON encode the user object
    user_json = json.dumps(user)

    # Base64 encode the JSON string
    user_base64 = base64.b64encode(user_json.encode()).decode()

    # Replace this with your SSO private key from Console -> Settings -> SSO
    # Use an environment variable or a secure location to store this
    PRIVATE_KEY = "sso_private_key"

    # HMAC SHA256 hash the base64 encoded string
    hash = hmac.new(
        PRIVATE_KEY.encode(),
        user_base64.encode(),
        digestmod=hashlib.sha256,
    ).hexdigest()

    return {
        "user": user_base64,
        "hash": hash,
    }


print(json.dumps(get_sso_user()))

