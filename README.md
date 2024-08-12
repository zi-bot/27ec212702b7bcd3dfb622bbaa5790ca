# Simple Asset Management API

This API provides basic CRUD operations for managing assets.

## Getting Started

### Prerequisites
Make sure you have the following installed:

- PHP (version 8.3.7+)
- PostgreSQL (or any other supported database)
- Git
- Docker (Optional)

### Installation
1. Clone the repository:

``` bash
git clone https://github.com/zi-bot/27ec212702b7bcd3dfb622bbaa5790ca.git
```
2. Navigate to the project directory:

```bash
cd 27ec212702b7bcd3dfb622bbaa5790ca
```
3. Install dependencies:

```bash
make install
```
4. Update dependencies:
```bash
make update
```
4. Start the server:
```bash
make serve-all
```
The server should now be running at http://localhost:8000.
The server should now be running at http://localhost:8001.

##### You can also hit endpoint directly with file [test.http](./test.http)

## API Endpoints

### Login
```http
POST /login
```
#### Request
```http request
POST http://localhost:8000/login
```
#### Body

| Parameter             | Type  | Description   |
|:----------------------|:------|:--------------|
| `username` (Required) | `str` | Username      |
| `password` (Required) | `str` | User password |

#### Response
```json
HTTP/1.1 200 OK
Host: localhost:8000
Date: Mon, 12 Aug 2024 03:06:40 GMT
Connection: close
X-Powered-By: PHP/8.3.7
Content-Type: application/json

{
  "status": "success",
  "message": "ok",
  "data": {
    "username": "andi",
    "user_id": 1,
    "access_token": {{token}}
  }
}
```
### Register
```http request
POST /register
```
Create new user
#### Request:
```http request
POST http://localhost:8000/login
```
#### Body
| Parameter             | Type  | Description   |
|:----------------------|:------|:--------------|
| `username` (Required) | `str` | Username      |
| `password` (Required) | `str` | User password |
#### Example body request
````json
{
  "username": "andi",
  "password": "testPassword"
}
````
#### Response:
```json
HTTP/1.1 201 Create
Host: localhost:8000
Date: Mon, 12 Aug 2024 03:11:38 GMT
Connection: close
X-Powered-By: PHP/8.3.7
Content-Type: application/json

{
  "status": "success",
  "message": "User registered successfully")",
  "data": null
}
```
### List Assets
```http
GET /assets
```
Returns a list of assets.

#### Request:
```http
GET http://localhost:8001/assets
```
#### Response:
```json
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:21:15 GMT
Content-Length: 901
Connection: close
GET http://localhost:8001/assets

HTTP/1.1 200 OK
Host: localhost:8001
Date: Sun, 11 Aug 2024 14:30:42 GMT
Connection: close
X-Powered-By: PHP/8.3.7
Content-Type: application/json

{
    "status": "success",
    "message": "ok",
    "data": [
        {
            "id": 1,
            "name": "Lemari",
            "value": 12.5,
            "owner_id": 1,
            "owner_name": "andi"
        },
        {
            "id": 2,
            "name": "Lemari",
            "value": 12.5,
            "owner_id": 1,
            "owner_name": "andi"
        }
    ]
}

```
### Detail Assets
```http
GET /assets/:id
```
Returns asset with specific id.

#### Parameters

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `id` (Required) | `int` | Asset Id|

#### Request:

```http
GET http://localhost:8080/assets/1
```
#### Response:

```json
HTTP/1.1 200 OK
Host: localhost:8001
Date: Sun, 11 Aug 2024 14:42:06 GMT
Connection: close
X-Powered-By: PHP/8.3.7
Content-Type: application/json

{
    "status": "success",
    "message": "ok",
    "data": {
        "id": 1,
        "name": "Lemari",
        "value": 12.5,
        "owner_id": 1,
        "owner_name": "andi"
    }
}
```
### Create Asset
```http
POST /assets
```
Creates a new asset.

#### Request Body

```json
{
    "value": 12.5,
    "name": "Lemari"
}
```
#### Example Request :

```json
POST http://localhost:8001/assets
Content-Type: application/json
Authorization: Bearer {{token}}
Content-Length: 39
User-Agent: IntelliJ HTTP Client/PhpStorm 2024.1.5
Accept-Encoding: br, deflate, gzip, x-gzip
Accept: */*

{
    "value": 12.5,
    "name": "Lemari"
}
```
#### Response:

```json
POST http://localhost:8080/assets
HTTP/1.1 201 Created
Host: localhost:8001
Date: Sun, 11 Aug 2024 14:53:31 GMT
Connection: close
X-Powered-By: PHP/8.3.7
Content-Type: application/json

{
    "status": "success",
    "message": "Asset created"
}
```
## Error Handling
### 400 Bad request
```json
HTTP/1.1 400 Bad Request
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:34:22 GMT
Content-Length: 83
Connection: close

{
  "error": "error message"
}
```
### 404 Bad request
```json
HTTP/1.1 404 Not Found
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:34:22 GMT
Content-Length: 83
Connection: close

{
  "error": "record not found"
}
```
### 500 Bad request
```json
HTTP/1.1 500 Internal server error
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:34:22 GMT
Content-Length: 83
Connection: close

{
  "error": "Internal server error"
}
```