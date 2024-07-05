## This is a manual testing script

# Depends on pycryptodome
# pip install pycryptodome


key = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4="
secure_attr = "TZv3+C+iLObn1gOh2VnnC5BZSNMgLHB2N7K/t1wVpKSw4j6eX++fwRGZ5akiwjlcxQ36CPi7he4u2Z+rQ/FBnQ==:v4KorkTfkpo36Yj5APRRnw=="

# split by :
encrypted, iv = secure_attr.split(':')

# decode base64
import base64
encrypted = base64.b64decode(encrypted)
iv = base64.b64decode(iv)

# decrypt
from Crypto.Cipher import AES
from Crypto.Util.Padding import unpad

cipher = AES.new(base64.b64decode(key), AES.MODE_CBC, iv)
decrypted = cipher.decrypt(encrypted)

padding_length = decrypted[-1]
decrypted = decrypted[:-padding_length]

print(decrypted.decode('utf-8'))