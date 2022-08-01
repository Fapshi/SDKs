
const axios = require('axios');
const baseUrl = 'https://live.fapshi.com'
const headers =  {
    apiuser: 'replace_me_with_apiuser',
    apikey: 'replace_me_with_apikey'
}

module.exports = {
    /** 
    *This function returns an object with the link were a user is to be redirected in order to complete his payment

    *Below is a parameter template. Just amount is required

        data = {
            "amount": Integer ,
            "email": String,
            "userId": String,
            "externalId": String,
            "redirectUrl": String,
            "message": String
        }
    */
    initiatePay(data){
        return new Promise(async function(resolve){
            try {

                if(!data?.amount)
                    resolve(error('amount required', 400))
                if(!Number.isInteger(data.amount))
                    resolve(error('amount must be of type integer', 400))
                if(data.amount<100)
                    resolve(error('amount cannot be less than 100 XAF', 400))

                const config = {
                    method: 'post',
                    url: baseUrl+'/initiate-pay',
                    headers: headers,
                    data: data
                }
                const response = await axios(config)
                response.data.statusCode = response.status
                resolve(response.data)
            }catch(e){
                e.response.data.statusCode = e?.response?.status
                resolve(e.response.data)
            }
        })
    },

    /** 
    * This function returns an object containing the details of the transaction associated with the Id passed as parameter
    */
    paymentStatus(transId){
        return new Promise(async function(resolve){
            try {
                if(!transId || typeof transId !== 'string')
                    resolve(error('invalid type, string expected', 400))
                if(!/^[a-zA-Z0-9]{8,9}$/.test(transId))
                    resolve(error('invalid transaction id', 400))

                const config = {
                    method: 'get',
                    url: baseUrl+'/payment-status/'+transId,
                    headers: headers
                }
                const response = await axios(config)
                response.data.statusCode = response.status
                resolve(response.data)
            }catch(e){
                e.response.data.statusCode = e?.response?.status
                resolve(e.response.data)
            }
        })
    },
    
    /** 
    * This function expires the transaction associated with the Id passed as parameter and returns an object containing the details of the transaction
    */
    expirePay(transId){
        return new Promise(async function(resolve){
            try {
                if(!transId || typeof transId !== 'string')
                    resolve(error('invalid type, string expected', 400))
                if(!/^[a-zA-Z0-9]{8,9}$/.test(transId))
                    resolve(error('invalid transaction id', 400))

                const config = {
                    method: 'post',
                    url: baseUrl+'/expire-pay',
                    data: {transId},
                    headers: headers
                }
                const response = await axios(config)
                response.data.statusCode = response.status
                resolve(response.data)
            }catch(e){
                e.response.data.statusCode = e?.response?.status
                resolve(e.response.data)
            }
        })
    },
    
    /** 
    * This function returns an array of objects containing the transaction details of the user Id passed as parameter
    */
    userTrans(userId){
        return new Promise(async function(resolve){
            try {
                if(!userId || typeof userId !== 'string')
                    resolve(error('invalid type, string expected', 400))
                if(!/^[a-zA-Z0-9-_]{1,100}$/.test(userId))
                    resolve(error('invalid user id', 400))

                const config = {
                    method: 'get',
                    url: baseUrl+'/transaction/'+userId,
                    headers: headers
                }
                const response = await axios(config)
                resolve(response.data)
            }catch(e){
                e.response.data.statusCode = e?.response?.status
                resolve(e.response.data)
            }
        })
    }

}

function error(message, statusCode){
    return {message, statusCode}
}