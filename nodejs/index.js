// Example of how to initiate a payment using the fapshi Nodejs SDK
// Before running this, make sure to have axios installed and add your apiuser and apikey in the fapshi.js file
const fapshi = require('./fapshi');

(async function main(){
    const payment = {
        amount: 500, //fapshi
        email: 'myuser@email.com',
        externalId: '12345',
        userId: 'abcde',
        redirectUrl: 'https://mywebsite.com',
        messaga: 'testing SDK nodejs'
    }
    const resp = await fapshi.initiatePay(payment)
    console.log(resp)
})()
