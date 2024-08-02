using System;
using System.Collections.Generic;
using System.Security.Cryptography;
using System.Text;
using System.Text.Json;

public class SsoHashGenerator
{

    // Your SSO Private Key from Console -> Settings -> SSO
    // Use an environment variable or a secure location to store this
    private const string SSO_PRIVATE_KEY = "sso_private_key";

    public static Dictionary<string, string> GetSsoHash()
    {
        // Construct the user data using Dictionary
        // See: https://talk.hyvor.com/docs/sso-stateless#user-object for available properties
        var userData = new Dictionary<string, object>
        {
            { "timestamp", DateTimeOffset.UtcNow.ToUnixTimeSeconds() },
            { "id", 1 },
            { "name", "John Doe" },
            { "email", "john@doe.com" }
        };

        // 1. JSON encoding
        string jsonData = JsonSerializer.Serialize(userData);

        // 2. Base64 encoding
        string base64Data = Convert.ToBase64String(Encoding.UTF8.GetBytes(jsonData));

        // Create HMAC SHA256 hash
        using (var hmac = new HMACSHA256(Encoding.UTF8.GetBytes(SSO_PRIVATE_KEY)))
        {
            byte[] hashBytes = hmac.ComputeHash(Encoding.UTF8.GetBytes(base64Data));
            string hash = BitConverter.ToString(hashBytes).Replace("-", "").ToLower();

            return new Dictionary<string, string>
            {
                { "user", base64Data }, // sso-user attribute
                { "hash", hash }        // sso-hash attribute
            };
        }
    }

}

var ssoHash = SsoHashGenerator.GetSsoHash();
Console.WriteLine(JsonSerializer.Serialize(ssoHash));