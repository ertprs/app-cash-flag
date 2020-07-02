curl -X POST \
  https://apimbu.mercantilbanco.com:9443/mercantil-banco/desarrollo/v1/payment/getauth \
  -H 'accept: application/json' \
  -H 'apikey: mbu1' \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -H 'environment: dev' \
  -H 'postman-token: 0ab8b75f-9553-5b76-393c-5bbad671c759' \
  -H 'x-ibm-client-id: 9860e0f2-ed46-495e-a25f-ef377ea645f6' \
  -d '{
	"merchant_identify": {
		"integratorId": 31,
		"merchantId": 150332,
		"terminalId": "abcde"
	},
	"client_identify": {
		"ipaddress": "10.0.0.1",
		"browser_agent": "Chrome 18.1.3",
		"mobile": {
			"manufacturer": "Samsung",
			"model": "S9",
			"os_version": "Oreo 9.1",
			"location": {
				"lat": 37.4224764,
				"lng": -122.0842499
			}
		}
	},
	"transaction_authInfo" : {
		"trx_type": "solaut",
		"payment_method": "tdd",
		"card_number": "501878200066287386",
		"customer_id": "V18366876"
	}
}'