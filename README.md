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
git clone https://github.com/zi-bot/simple-gin-rest.git
```
2. Navigate to the project directory:

```bash
cd simple-gin-rest
```
3. Install dependencies:

```bash
make tidy
```
4. Start the server:
```bash
make run
```
The server should now be running at http://localhost:8080.

### Run with Docker
1. Build the image:
```bash
make docker-build
```
2. Run the container:
```bash
make docker-run
```
### Run Testing
```bash
make test
```
##### You can also hit endpoint directly with file [test.http](./test.http)

## API Endpoints

### List Assets
```http
GET /assets?page=<page_number>&limit=<items_per_page>
```
Returns a list of assets with pagination support.

#### Parameters

| Query | Type | Description |
| :--- | :--- | :--- |
| `page` (Optional) | `int` | Page number (default: 1)|
| `limit` (optional)| `int` | Number of items per page (default: 10) |

#### Request:

```http
GET http://localhost:8080/assets?page=1&limit=5
```
#### Response:

```json
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:21:15 GMT
Content-Length: 901
Connection: close
{
  "data": [
    {
      "id": 1,
      "name": "Chair",
      "type": "Furniture",
      "acquisition_date": null
    },
    {
      "id": 2,
      "name": "Desk",
      "type": "Furniture",
      "acquisition_date": null
    },
    {
      "id": 3,
      "name": "Laptop",
      "type": "Electronics",
      "acquisition_date": null
    },
    {
      "id": 4,
      "name": "Phone",
      "type": "Electronics",
      "acquisition_date": null
    },
    {
      "id": 5,
      "name": "Table",
      "type": "Furniture",
      "acquisition_date":"2024-07-14T00:00:00Z"
    }
  ],
  "pagination": {
    "limit": 5,
    "page": 1,
    "total": 10
  }
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
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:21:15 GMT
Content-Length: 901
Connection: close
{
  "data": 
    {
      "id": 1,
      "name": "Chair",
      "type": "Furniture",
      "acquisition_date": null
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
  "name": "Chair",
  "type": "Furniture",
  "value":100.1,
  "acquisition_date":null
}
```
#### Example Request :

```json
POST http://localhost:8080/assets
Content-Type: application/json

{
  "name": "Chair",
  "type": "Furniture",
  "value":100.1,
  "acquisition_date":null
}
```
#### Response:

```http
HTTP/1.1 201 Created
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:19:54 GMT
Content-Length: 40
Connection: close

{
  "message": "Asset created successfully"
}
```
### Update Asset
```http
PUT /assets/:id
```
Updates an existing asset by ID.

#### Parameters

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `id` (Required) | `int` | Asset Id|

#### Request Body

```json
{
  "name": "Chair",
  "type": "Furniture",
  "value":100.1,
  "acquisition_date":"2024-07-13"
}
```
#### Example Request:

```http
PUT http://localhost/assets/6
Content-Type: application/json

{
  "name": "Chair Updated",
  "type": "Furniture",
  "value": 100.1,
  "acquisition_date": "2024-07-13"
}
```
#### Response:

```json
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:24:59 GMT
Content-Length: 114
Connection: close
{
  "id": 6,
  "name": "Chair Updated",
  "type": "Furniture",
  "value": 100.1,
  "acquisition_date": "2024-07-13"
}
```
### Delete Asset
```http
DELETE /assets/:id
```
Deletes an asset by ID.

#### Parameters

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `id` (Required) | `int` | Asset Id|

#### Example Request:
```http
DELETE http://localhost:8080/assets/6
```
#### Response:
```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Date: Sun, 14 Jul 2024 09:30:04 GMT
Content-Length: 40
Connection: close

{
  "message": "Asset deleted successfully"
}
```
#### Error Handling
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