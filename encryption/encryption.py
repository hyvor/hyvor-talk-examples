import time
import json
from Crypto.Cipher import AES
from Crypto.Random import get_random_bytes
from Crypto.Util.Padding import pad
import base64

# This script depends on pycryptodome
# pip install pycryptodome

def encrypt():

    # This is the base64 encoded key from Console -> Settings -> API -> Encryption Key
    # Ideally, this should be stored in a secure location and not in the codebase. ex: env variable
    key = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4="

    data = {
        # Current UNIX timestamp in seconds
        # This is used to ensure that the request is not replayed
        "timestamp": int(time.time()),

        # Below, you would set the other data you want to set
        # See our documentation what keys are required in each component
        "page-id": "my-page-id-2",
    }

    # Convert the data to a JSON string
    data = json.dumps(data)


    # Generate a random IV (Initialization Vector) for each encryption
    # This is used to ensure that the same data encrypted multiple times will have different results
    iv = get_random_bytes(16)

    # Create an AES cipher object with the key and IV
    cipher = AES.new(base64.b64decode(key), AES.MODE_CBC, iv)

    # Encrypt the data
    encrypted = cipher.encrypt(pad(data.encode('utf-8'), AES.block_size))

    # Finally, return the encrypted data (base64 encoded) and the IV (base64 encoded)
    # Connect the two with a :
    return base64.b64encode(encrypted).decode('utf-8') + ':' + base64.b64encode(iv).decode('utf-8')


print(encrypt())