require 'json'
require 'base64'
require 'openssl'

def get_sso_hash

    # Construct the user object
    # See: https://talk.hyvor.com/docs/sso-stateless#user-object for available properties
    user_data = {
        timestamp: Time.now.to_i,
        id: 1,
        name: 'John Doe',
        email: 'john@doe.com',
    }

    # JSON encode the user object
    user_data = user_data.to_json

    # Base64 encode the JSON string
    # Add this as the `sso-user` attribute
    user_data = Base64.encode64(user_data)

    # Replace this with your SSO private key from Console -> Settings -> SSO
    # Use an environment variable or a secure location to store this
    sso_private_key = 'sso_private_key'

    # Generate a hash using HMAC-SHA256
    # Add this as the `sso-hash` attribute
    hash = OpenSSL::HMAC.hexdigest('SHA256', sso_private_key, user_data)

    {
        user: user_data,
        hash: hash,
    }
end

puts get_sso_hash.to_json