import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;
import java.nio.charset.StandardCharsets;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.util.Base64;
import java.util.HashMap;
import java.util.Map;
import org.json.JSONObject;

public class SsoHashGenerator {

    // Your SSO Private Key from Console -> Settings -> SSO
    // Use an environment variable or a secure location to store this
    private static final String SSO_PRIVATE_KEY = "sso_private_key";

    public static Map<String, String> getSsoHash() throws NoSuchAlgorithmException, InvalidKeyException {
        
        // Construct the user data using HashMap
        // See: https://talk.hyvor.com/docs/sso-stateless#user-object for available properties
        Map<String, Object> userData = new HashMap<>();
        userData.put("timestamp", System.currentTimeMillis() / 1000);
        userData.put("id", 1);
        userData.put("name", "John Doe");
        userData.put("email", "john@doe.com");

        // 1. JSON encoding
        JSONObject jsonObject = new JSONObject(userData);
        String jsonData = jsonObject.toString();

        // 2. Base64 encoding
        String base64Data = Base64.getEncoder().encodeToString(jsonData.getBytes(StandardCharsets.UTF_8));

        // Create HMAC SHA256 hash
        Mac sha256Hmac = Mac.getInstance("HmacSHA256");
        SecretKeySpec secretKey = new SecretKeySpec(SSO_PRIVATE_KEY.getBytes(StandardCharsets.UTF_8), "HmacSHA256");
        sha256Hmac.init(secretKey);
        byte[] hashBytes = sha256Hmac.doFinal(base64Data.getBytes(StandardCharsets.UTF_8));

        // Convert byte array to hexadecimal string
        StringBuilder hashStringBuilder = new StringBuilder();
        for (byte b : hashBytes) {
            hashStringBuilder.append(String.format("%02x", b));
        }
        String hash = hashStringBuilder.toString();

        Map<String, String> result = new HashMap<>();
        result.put("user", base64Data); // sso-user attribute
        result.put("hash", hash);       // sso-hash attribute

        return result;
    }

    public static void main(String[] args) {
        try {
            Map<String, String> ssoHash = getSsoHash();
            System.out.println(new JSONObject(ssoHash).toString());
        } catch (NoSuchAlgorithmException | InvalidKeyException e) {
            e.printStackTrace();
        }
    }
}