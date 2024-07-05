import java.nio.charset.StandardCharsets;
import java.security.SecureRandom;
import java.util.Base64;
import javax.crypto.Cipher;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;
import org.json.JSONObject;

/* 
 * This code depends on org.json library
 */

public class EncryptionUtils {


    public static String encrypt() {
        /**
        * This is the base64 encoded key from Console -> Settings -> API -> Encryption Key
        * Ideally, this should be stored in a secure location and not in the codebase. ex: env variable
        */
        String ENCRYPTION_KEY = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4=";
        byte[] key = Base64.getDecoder().decode(ENCRYPTION_KEY);

        JSONObject data = new JSONObject();
        data.put("timestamp", System.currentTimeMillis() / 1000); // Current UNIX timestamp in seconds
        data.put("page-id", "my-page-id-3");

        String json = data.toString();

        // Generate a random IV (Initialization Vector) for each encryption
        byte[] iv = new byte[16];
        new SecureRandom().nextBytes(iv);

        try {
            Cipher cipher = Cipher.getInstance("AES/CBC/PKCS5Padding");
            SecretKeySpec secretKeySpec = new SecretKeySpec(key, "AES");
            IvParameterSpec ivParameterSpec = new IvParameterSpec(iv);

            cipher.init(Cipher.ENCRYPT_MODE, secretKeySpec, ivParameterSpec);
            byte[] encrypted = cipher.doFinal(json.getBytes(StandardCharsets.UTF_8));

            // Combine encrypted data and IV into a single string separated by ":"
            String encryptedData = Base64.getEncoder().encodeToString(encrypted);
            String ivString = Base64.getEncoder().encodeToString(iv);
            return encryptedData + ":" + ivString;
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }

    public static void main(String[] args) {
        String encryptedData = encrypt();
        System.out.println(encryptedData);
    }
}