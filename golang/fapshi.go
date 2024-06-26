package fapshi

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"regexp"
)

const (
	BaseURL = "https://live.fapshi.com/"
	Apikey  = "replace_me_with_apikey"
	ApiUser = "replace_me_with_apiuser"
)

var client = &http.Client{}

type FapshiPayment struct {
	Amount      int64  `json:"amount"`
	Phone       string `json:"phone"`
	Medium      string `json:"medium"`
	Name        string `json:"name"`
	Email       string `json:"email"`
	UserId      string `json:"userId"`
	ExternalId  string `json:"externalId"`
	RedirectUrl string `json:"redirectUrl"`
	Message     string `json:"message"`
}

func request(method, path string, data interface{}) (any, error) {
	url := BaseURL + path
	jsonData, err := json.Marshal(data)
	if err != nil {
		fmt.Print(err)
		return nil, err
	}
	req, err := http.NewRequest(method, url, bytes.NewBuffer(jsonData))
	if err != nil {
		fmt.Print(err.Error())
		return nil, err
	}
	req.Header.Set("Content-Type", "application/json; charset=utf-8")
	req.Header.Add("apikey", Apikey)
	req.Header.Add("apiuser", ApiUser)

	res, err := client.Do(req)
	if err != nil {
		fmt.Print(err.Error())
		return nil, err
	}
	defer res.Body.Close()
	body, readErr := io.ReadAll(res.Body)
	if readErr != nil {
		return nil, readErr
	}
	fmt.Println(string(body))
	var resp interface{}
	json.Unmarshal(body, &resp)
	return resp, nil
}

/*
This function returns an object with the link were a user is to be redirected in order to complete his payment

Below is a parameter template. Just amount is required

	data = {
	    "amount": Integer ,
	    "email": String,
	    "userId": String,
	    "externalId": String,
	    "redirectUrl": String,
	    "message": String
	}
*/

func InitiatePay(data FapshiPayment) (any, error) {
	if data.Amount < 100 {
		return nil, fmt.Errorf("Amount should be greater than or equal to 100")
	}
	resp, err := request(http.MethodPost, "initiate-pay", data)
	if err != nil {
		return nil, err
	}
	return resp, nil
}

/*
This function directly initiates a payment request to a user's mobile device and
returns an object with a transId property that is used to get the status of the payment

Below is a parameter template. amount and phone are required

	data = {
	    "amount": Integer ,
	    "phone": String ,
	    "medium": String,
	    "name": String,
	    "email": String,
	    "userId": String,
	    "externalId": String,
	    "message": String
	}
*/
func DirectPay(data FapshiPayment) (any, error) {
	if data.Amount < 100 {
		return nil, fmt.Errorf("Amount should be greater than or equal to 100")
	}
	if data.Phone == "" {
		return nil, fmt.Errorf("Phone number is required")
	}
	match, err := regexp.MatchString(`^6[\d]{8}$`, data.Phone)
	if err != nil {
		return nil, err
	}
	if !match {
		return nil, fmt.Errorf("Invalid phone number")
	}
	resp, err := request(http.MethodPost, "direct-pay", data)
	if err != nil {
		return nil, err
	}
	return resp, nil
}

/*
This function returns an object containing the details of the transaction associated with the Id passed as parameter
*/
func PaymentStatus(transId string) (any, error) {
	if transId == "" {
		return nil, fmt.Errorf("Transaction Id is required")
	}
	match, err := regexp.MatchString(`^[a-zA-Z0-9]{1,100}$`, transId)
	if err != nil {
		return nil, err

	}
	if !match {
		return nil, fmt.Errorf("Invalid Transaction Id")
	}
	path := "payment-status/" + transId
	resp, err := request(http.MethodGet, path, nil)
	if err != nil {
		return nil, err
	}
	return resp, nil
}

/*
This function expires the transaction associated with the Id passed as parameter and returns an object containing the details of the transaction
*/
func ExpirePay(transId string) (any, error) {
	if transId == "" {
		return nil, fmt.Errorf("Transaction Id is required")
	}
	match, err := regexp.MatchString(`^[a-zA-Z0-9]{1,100}$`, transId)
	if err != nil {
		return nil, err
	}
	if !match {
		return nil, fmt.Errorf("Invalid Transaction Id")
	}
	data := map[string]string{
		"transId": transId,
	}
	resp, err := request(http.MethodPost, "expire-pay", data)
	if err != nil {
		return nil, err
	}
	return resp, nil
}

/*
This function returns an array of objects containing the transaction details of the user Id passed as parameter
*/
func UserTrans(userId string) (any, error) {
	if userId == "" {
		return nil, fmt.Errorf("User Id is required")
	}
	match, err := regexp.MatchString(`^[a-zA-Z0-9]{1,100}$`, userId)
	if err != nil {
		return nil, err
	}
	if !match {
		return nil, fmt.Errorf("Invalid User Id")
	}
	path := "transactions/" + userId
	resp, err := request(http.MethodGet, path, nil)
	if err != nil {
		return nil, err
	}
	return resp, nil
}
