package main

import (
	"crypto/hmac"
	"crypto/sha256"
	"encoding/base64"
	"encoding/json"
	"fmt"
	"time"
)

// See: https://talk.hyvor.com/docs/sso-stateless#user-object for available properties
type UserData struct {
	Timestamp int64  `json:"timestamp"`
	ID        int    `json:"id"`
	Name      string `json:"name"`
	Email     string `json:"email"`
}

func getSsoHash() map[string]string {
	// Construct the user object
	userData := UserData{
		Timestamp: time.Now().Unix(),
		ID:        1,
		Name:      "John Doe",
		Email:     "john@doe.com",
	}

	// 1. JSON encoding
	jsonData, _ := json.Marshal(userData)

	// 2. Base64 encoding
	base64Data := base64.StdEncoding.EncodeToString(jsonData)

	// Replace this with your SSO private key from Console -> Settings -> SSO
	// Use an environment variable or a secure location to store this
	const SSO_PRIVATE_KEY = "sso_private_key"

	// Create HMAC SHA256 hash
	h := hmac.New(sha256.New, []byte(SSO_PRIVATE_KEY))
	h.Write([]byte(base64Data))
	hash := fmt.Sprintf("%x", h.Sum(nil))

	return map[string]string{
		"user": base64Data, // sso-user attribute
		"hash": hash,       // sso-hash attribute
	}
}

// testing
func main() {
	result := getSsoHash()
	jsonResult, err := json.Marshal(result)
	if err != nil {
		panic(err)
	}
	fmt.Println(string(jsonResult))
}