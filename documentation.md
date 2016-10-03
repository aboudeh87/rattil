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

