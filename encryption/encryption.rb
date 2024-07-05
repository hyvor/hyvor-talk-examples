require 'openssl'
require 'json'
require 'base64'

def encrypt
    # This is the base64 encoded key.
    # Ideally, this should be stored in a secure location and not in the codebase. ex: environment variable
    base64_key = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4="
    key = Base64.decode64(base64_key)

    # Data to encrypt
    data = {
        timestamp: Time.now.to_i,
        page_id: "my-page-rb"
    }
    json_data = data.to_json

    # Generate a random IV (Initialization Vector)
    iv = OpenSSL::Random.random_bytes(16)

    # Encrypt the data
    cipher = OpenSSL::Cipher.new('AES-256-CBC')
    cipher.encrypt
    cipher.key = key
    cipher.iv = iv

    encrypted = cipher.update(json_data) + cipher.final

    # Combine encrypted data and IV (base64 encoded)
    encrypted_base64 = Base64.strict_encode64(encrypted)
    iv_base64 = Base64.strict_encode64(iv)
    
    "#{encrypted_base64}:#{iv_base64}"
end

puts encrypt