import re
import requests

base_url = 'https://live.fapshi.com'
headers = {
    'apiuser':'replace_me_with_apiuser',
    'apikey':'replace_me_with_apikey'
}
invalid_str = 'invalid type, string expected'

def initiate_pay(data: dict):
    '''
        This function returns a dictionary with a link were a user is to be redirected in order to complete his payment

        required *

        data = {
            "amount": Integer *,
            "email": String,
            "userId": String,
            "externalId": String,
            "redirectUrl": String,
            "message": String
        }
    '''
    if(type(data) is not dict):
        return {'statusCode':400, 'message':'invalid type, dictionary expected'}

    key = 'amount'
    if(key not in data):
        return {'statusCode':400, 'message':'amount required'}

    if(type(data['amount']) is not int):
        return {'statusCode':400, 'message':'amount must be of type integer'}

    if(data['amount']<100):
        return {'statusCode':400, 'message':'amount cannot be less than 100 XAF'}

    url = base_url+'/initiate-pay'
    r = requests.post(url=url, json=data, headers=headers)
    resp = r.json()
    resp['statusCode'] = r.status_code
    return resp



def direct_pay(data: dict):
    '''
        This function directly initiates a payment request to a user's mobile device and
        returns a dictionary with a transId that can be used to get the status of the payment

        required *

        data = {
            "amount": Integer *,
            "phone": String  *,
            "medium": String,
            "name": String,
            "email": String,
            "userId": String,
            "externalId": String,
            "message": String
        }
    '''
    if(type(data) is not dict):
        return {'statusCode':400, 'message':'invalid type, dictionary expected'}

    key = 'amount'
    if(key not in data):
        return {'statusCode':400, 'message':'amount required'}

    if(type(data['amount']) is not int):
        return {'statusCode':400, 'message':'amount must be of type integer'}

    if(data['amount']<100):
        return {'statusCode':400, 'message':'amount cannot be less than 100 XAF'}

    key = 'phone'
    if(key not in data):
        return {'statusCode':400, 'message':'phone number required'}

    if(type(data['phone']) is not str):
        return {'statusCode':400, 'message':'phone must be of type string'}

    if(not re.search('^6[0-9]{8}$',data['phone'])):
        return {'statusCode':400, 'message':'invalid phone number'}

    url = base_url+'/direct-pay'
    r = requests.post(url=url, json=data, headers=headers)
    resp = r.json()
    resp['statusCode'] = r.status_code
    return resp



def payment_status(trans_id: str):
    '''
        This function returns a dictionary containing the details of the transaction with associated with the Id passed as parameter
    '''
    if(type(trans_id) is not str) or (not trans_id):
        return {'statusCode':400, 'message':invalid_str}

    if(not re.search('^[a-zA-Z0-9]{8,9}$',trans_id)):
        return {'statusCode':400, 'message':'invalid transaction id'}

    url = base_url+'/payment-status/'+trans_id
    r = requests.get(url=url, headers=headers)
    resp = r.json()
    resp['statusCode'] = r.status_code
    return resp



def expire_pay(trans_id: str):
    '''
        This function expires the transaction associated with the Id passed as parameter and returns a dictionary containing the details of the transaction
    '''
    if(type(trans_id) is not str) or (not trans_id):
        return {'statusCode':400, 'message':invalid_str}

    if(not re.search('^[a-zA-Z0-9]{8,9}$',trans_id)):
        return {'statusCode':400, 'message':'invalid transaction id'}

    data = {"transId":trans_id}
    url = base_url+'/expire-pay'
    r = requests.post(url=url, json=data, headers=headers)
    resp = r.json()
    resp['statusCode'] = r.status_code
    return resp



def get_user_trans(user_id: str):
    '''
        This function returns a list containing the transaction details of the user Id passed as parameter
    '''
    if(type(user_id) is not str) or (not user_id):
        return {'statusCode':400, 'message':invalid_str}

    if(not re.search('^[a-zA-Z0-9-_]{1,100}$',user_id)):
        return {'statusCode':400, 'message':'invalid user id'}

    url = base_url+'/transaction/'+user_id
    r = requests.get(url=url, headers=headers)
    resp = r.json()
    return resp
