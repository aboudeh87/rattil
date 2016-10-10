# Rattil API Documentation

To send authenticated request, add `Authorization` header to the request with 
`Bearer {token}` value.

All the URIs in this documentation has a prefix: `/api/v1/`.

***Responses:***

**Success response:**
```json
{
    "success": true,
    "message": "a text message"
}
```

**Error response:**
```json
{
    "success": false,
    "message": "Error text message"
}
```


## Authentication 

#### Login

* _url:_ `login`
* _method_: `POST`
* _parameters_:
    * `username` **required** // email or username
    * `password` **required**
* _response_: a `JSON` response like: 

```json
{
    "success": true,
    "message": "Login success message",
    "user": {
        "id": 1,
        "name": "User Name",
        "username": "username",
        "email": "user@example.com",
        "token": "Authentication token"
    }
}
```

#### Logout

* _url:_ `logout`
* _method_: `GET`
* Authentication required
* _response_: normal `Success response`. 

#### Register

* _url:_ `register`
* _method_: `POST`
* _parameters_:
    * `name` ***required*** // User full name
    * `username` ***required***
    * `email` ***required***
    * `password` ***required***
    * `password_confirmation` ***required***
* _response_: normal `Success response`.

#### Reset password request

* _url:_ `password/email`
* _method_: `POST`
* _parameters_:
    * `email` ***required***
* _response_: normal `Success response`.

#### Social media authentication

* _url:_ `social/{provider}` // available providers [`facebook`]
* _method_: `POST`
* _parameters_:
    * `oauth_token` ***required*** // The access_token returned from the provider
* _response_: a `JSON` response like:

```json
{
    "success": true,
    "message": "Login success message",
    "user": {
        "id": 1,
        "name": "User Name",
        "username": "username",
        "email": "user@example.com",
        "token": "Authentication token"
    }
}
```

# Suwar

#### Suwar list

* _url:_ `/suwar`
* _method_: `GET`
* _response_: a `JSON` response like:

```json
{
    "total": 2, // total number of suwar models
    "limit": 15, // limit per page
    "currentPage": 1, // current page number
    "data": [   // arrau of models
        {
            "id": 1, // ID of sura
            "name": "test", // name of sura
            "revealed": "medinan",
            "chronologicalOrder": 1
        },
        {
            "id": 2,
            "name": "Test 2",
            "revealed": "medinan",
            "chronologicalOrder": 3
        }
    ] 
}
```

#### Show Sura information

* _url:_ `/suwar/{model}` // `{model}` is The ID of Sura
* _method_: `GET`
* _response_: a `JSON` response like:

```json
{
  "id": 1,
  "name": "test",
  "revealed": "labels.medinan",
  "chronologicalOrder": 1,
  "verses": [ // Array of verses
     {
        "id": 1, // ID of verse
        "number": 1, // The number of verse
        "text":  "The text of verse",
        "cleanText":  "The clean text of verse",
        "characters":  "The characters of verse"
     }
  ]
}
```

