package fapshi

import "fmt"

func main() {
	data := FapshiPayment{
		Amount:      1000,
		Email:       "myuser@email.com",
		ExternalId:  "12345",
		UserId:      "abcde",
		RedirectUrl: "https://mywebsite.com",
		Message:     "testing SDK golang",
	}

	resp, err := InitiatePay(data)
	if err != nil {
		fmt.Println(err)
	}
	fmt.Println(resp)
}
