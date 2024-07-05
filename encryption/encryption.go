package main

import (
	"crypto/aes"
	"crypto/cipher"
	"crypto/rand"
	"encoding/base64"
	"encoding/json"
	"fmt"
	"io"
	"time"
	"bytes"
)

func main() {
	encryptedData := encrypt()
	fmt.Println(encryptedData)
}

func encrypt() string {
	// This is the base64 encoded key from Console -> Settings -> API -> Encryption Key
    // Ideally, this should be stored in a secure location and not in the codebase. ex: env variable
	base64Key := "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4="
	key, _ := base64.StdEncoding.DecodeString(base64Key)

	// Data to encrypt
	data := map[string]interface{}{
		"timestamp": time.Now().Unix(), // UNIX timestamp in seconds
		// add other data according to your use case
		"page_id":   "my-page-go",
	}
	jsonData, _ := json.Marshal(data)

	// Generate a random IV for each encryption
	iv := make([]byte, aes.BlockSize)
	if _, err := io.ReadFull(rand.Reader, iv); err != nil {
		panic(err)
	}

	// Encrypt the data
	block, _ := aes.NewCipher(key)
	paddedData := PKCS7Padding(jsonData)
	ciphertext := make([]byte, len(paddedData))
	mode := cipher.NewCBCEncrypter(block, iv)
	mode.CryptBlocks(ciphertext, paddedData)

	// Combine encrypted data and IV (base64 encoded)
	encryptedBase64 := base64.StdEncoding.EncodeToString(ciphertext)
	ivBase64 := base64.StdEncoding.EncodeToString(iv)

	return encryptedBase64 + ":" + ivBase64
}


func PKCS7Padding(ciphertext []byte) []byte {
	padding := aes.BlockSize - len(ciphertext) % aes.BlockSize
	padtext := bytes.Repeat([]byte{byte(padding)}, padding)
	return append(ciphertext, padtext...)
}