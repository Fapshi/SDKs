package fapshi

import (
	"encoding/json"
	"fmt"
	"io"
	"log"
	"net/http"
)

type FapshiWebhookResponse struct {
	TransId          string `json:"transId"`
	Status           string `json:"status"`
	Medium           string `json:"medium"`
	ServiceName      string `json:"serviceName"`
	Amount           int64  `json:"amount"`
	Revenue          int64  `json:"revenue"`
	PayerName        string `json:"payerName"`
	Email            string `json:"email"`
	RedirectUrl      string `json:"redirectUrl"`
	ExternalId       string `json:"externalId"`
	UserId           string `json:"userId"`
	Webhook          string `json:"webhook"`
	FinancialTransId string `json:"financialTransId"`
	DateInitiated    string `json:"dateInitiated"`
	DateConfirmed    string `json:"dateConfirmed"`
}

func FapshiWebhook(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, "Route "+r.Method+": "+r.URL.Path+" not found", http.StatusNotFound)
		return
	}
	body, err := io.ReadAll(r.Body)
	if err != nil {
		http.Error(w, err.Error(), http.StatusBadRequest)
		return
	}
	var resp FapshiWebhookResponse
	json.Unmarshal(body, &resp)
	event, err := PaymentStatus(resp.TransId)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	switch event.(FapshiWebhookResponse).Status {
	case "SUCCESSFUL":
		fmt.Println(event, "SUCCESS")
		// Do something
	case "FAILED":
		fmt.Println(event, "FAILED")
		// Do something
	case "EXPIRED":
		fmt.Println(event, "EXPIRED")
		// Do something
	}
}

func Server() {
	mux := http.NewServeMux()

	mux.HandleFunc("/webhook", FapshiWebhook)

	log.Println("🚀 Server running on port 8080 ...")
	http.ListenAndServe(":8080", mux)
}
