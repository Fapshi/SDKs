# Example of how to initiate a payment using the fapshi python SDK
# Before running this, make sure to have requests installed. Also, add your apiuser and apikey to your fapshi.py file
import fapshi

payment = {
    'amount': 500, #fapshi
    'email': 'myuser@email.com',
    'externalId': '12345',
    'userId': 'abcde',
    'redirectUrl': 'https://mywebsite.com',
    'message':'python SDK testing' 
}

resp = fapshi.initiate_pay(payment)
print(resp)