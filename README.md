# Backend Engineer Home Assignment - Bunq 

A simple chat backend implemented in PHP with slim. The dependencies are handled by **composer**. The project is tested with PHP 7.4.3 and Sqlite 3.31.1.

## Install the Application

First change directory into the proejct root directory then install the dependencies with 

```bash
composer install
```
To run the program:

```bash
composer start
```

## Access the REST API
By default the backend listens to *localhost:8080*.

### User related endpoints
1. Retrieve a list of all users
	* endpoint: */users* 
	* method: HTTP GET
	* parameter: None
	* Response:
    ```json
    {
	"statusCode": 200,
	"data": [
	    {
		"id": 1,
		"username": "John Wick",
		"firstName": "Keanu",
		"lastName": "Reeves"
	    }
	    ...
	    ]
    }
    ```
2. Retrieve one user with a specific id 
	* endpoint: */users/{id}*
	* method: HTTP GET
	* parameter: id
	* Response:
	```json
	{
	  "statusCode": 200,
          "data": {
            "id": 1,
            "username": "trump",
	    "firstName": "Stupid",
	    "lastName": "Shit"
	  }
	}
	```
3. New user
	* endpoint: */user*
	* method: HTTP POST
	* parameter: None 
	* request:
	```json
	{
	    "id": 2,
	    "username": "biden",
	    "first_name": "foolish",
	    "last_name": "bitch"
	}
	```
	* response: None

	
### Message related endpoints
1. Retrieve message history of a user
	* endpoint: */chat/{id}*
	* method: HTTP GET	
	* parameter: id
	* Response:
	```json
	{
	  "data": [
	    {
	      "id": 1,
	      "sender": 1,
	      "receiver": 2,
	      "content": "hello world!",
	      "timestamp": 1622750782
	    },
	    {
	      "id": 2,
	      "sender": 2,
	      "receiver": 1,
	      "content": "Hey",
	      "timestamp": 1622751611
	    }
	  ]
	}
	```

2. Retrieve message after a center timestamp
	* endpoint: */chat/{id}/{timestamp}*
	* method: HTTP GET
	* parameter: 
		* id: user id
		* timestamp: unix format time stamp
	* Response:
	```json
	{
	  "statusCode": 200,
	  "data": [
	    {
	      "id": 2,
	      "sender": 2,
	      "receiver": 1,
	      "content": "Hey",
	      "timestamp": 1622751611
	    }
	  ]
	}
	```
3. New message
	* endpoint: */chat*
	* method: HTTP POST
	* parameter: None
	* request body:
	```json
	{
	    "id": 2,
	    "sender": 2,
	    "receiver": 1,
	    "content": "Hey",
	    "timestamp": 1622751611
	}
	```
	* response: None
	
4. Delete message
	* endpoint */chat/{id}*
	* method: HTTP DELETE
	* parameter: 
		* id: message id
	* response: None

