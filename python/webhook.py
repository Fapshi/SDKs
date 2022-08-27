# app.py
#
# Use this sample code to handle webhook events in your integration.
#
# 1) Paste this code into a new file (app.py)
#
# 2) Install dependencies
#   pip3 install flask
#
# 3) Run the server on http://localhost:3000
#   python3 -m flask run --port=3000

import json
import os
import fapshi

from flask import Flask, jsonify, request

app = Flask(__name__)

@app.route('/fapshi-webhook', methods=['POST'])
def webhook():
    # Get the transaction status from fapshi's API to be sure of its source
    event = fapshi.payment_status(request.data.transId)
    if event['statusCode'] != 200:
        return jsonify(success=False)
        
    # Handle the event
    if event['status'] == 'SUCCESSFUL':
      # Then define and call a function to handle a SUCCESSFUL payment
      print(event, 'successful')
    elif event['status'] == 'FAILED':
      # Then define and call a function to handle a FAILED payment
      print(event, 'failed')
    elif event['status'] == 'EXPIRED':
    #   Then define and call a function to handle an expired transaction
      print(event, 'expired')
    # ... handle other event types
    else:
      print('Unhandled event status: {}'.format(event['status']))

    return jsonify(success=True)