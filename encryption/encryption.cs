#r "nuget: Newtonsoft.Json, 13.0.3"

using System;
using System.Security.Cryptography;
using System.Text;

public class Program
{

    public static string Encrypt()
    {
        // This is the base64 encoded key.
        // Ideally, this should be stored in a secure location and not in the codebase. ex: environment variable
        string base64Key = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4=";
        byte[] key = Convert.FromBase64String(base64Key);

        // Data to encrypt (similar to JavaScript object)
        var data = new {
            timestamp = DateTimeOffset.UtcNow.ToUnixTimeSeconds(),
            page_id = "my-page-cs"
        };
        string jsonData = Newtonsoft.Json.JsonConvert.SerializeObject(data);

        // Generate a random IV (Initialization Vector)
        byte[] iv = new byte[16];
        using (RandomNumberGenerator rng = RandomNumberGenerator.Create())
        {
            rng.GetBytes(iv);
        }

        // Encrypt the data
        byte[] encrypted;
        using (Aes aesAlg = Aes.Create())
        {
            aesAlg.Key = key;
            aesAlg.IV = iv;
            aesAlg.Mode = CipherMode.CBC;
            aesAlg.Padding = PaddingMode.PKCS7;

            ICryptoTransform encryptor = aesAlg.CreateEncryptor(aesAlg.Key, aesAlg.IV);

            using (var msEncrypt = new System.IO.MemoryStream())
            {
                using (var csEncrypt = new CryptoStream(msEncrypt, encryptor, CryptoStreamMode.Write))
                {
                    using (var swEncrypt = new System.IO.StreamWriter(csEncrypt))
                    {
                        swEncrypt.Write(jsonData);
                    }
                    encrypted = msEncrypt.ToArray();
                }
            }
        }

        // Combine encrypted data and IV (base64 encoded)
        string encryptedBase64 = Convert.ToBase64String(encrypted);
        string ivBase64 = Convert.ToBase64String(iv);
        return encryptedBase64 + ":" + ivBase64;
    }
}

string encryptedData = Program.Encrypt();
Console.WriteLine(encryptedData);