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
* Authentication ***Required***
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
* Authentication ***Required***
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

# Recitations

#### My recitation list

* _url:_ `/recitations/my`
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response like:

```json
{
  "total": 1, // total of user recitation
  "limit": 15, // number of recitations per page
  "currentPage": 1, // current page number
  "data": [ // array of recitation
    {
      "id": 2, // ID of recitation
      "slug": "xxxx",
      "user": { // The owner of recitation
        "id": 4,
        "name": "AbdulKader",
        "username": "abd",
        "avatar": null
      },
      "narration": { // Narration information
        "id": 1,
        "name": "Test",
        "weight": 2
      },
      "fromVerse": { // Start verse
        "id": 1,
        "number": 1,
        "text": "ssss",
        "cleanText": "ssss",
        "characters": "sss"
      },
      "toVerse": { // end Verse
        "id": 2,
        "number": 2,
        "text": "dddd",
        "cleanText": "dddd",
        "characters": "dddd"
      },
      "date": 1476232607, // Created date
      "commentsCount": 0, // number of comments
      "favoritesCount": 0, // number of favorites
      "likesCount": 0 // number of likes
    }
  ]
}
```

#### Show Recitation information

* _url:_ `/recitations/{model}` // `{model}` is The ID of Recitation
* _method_: `GET`
* Authentication ***required***
* _response_: a `JSON` response like:

```json
{
  "id": 2,
  "slug": "xxxx",
  "user": { // Owner information
    "id": 4,
    "name": "AbdulKader",
    "username": "abd",
    "avatar": null
  },
  "narration": { // Narration information
    "id": 1,
    "name": "Test",
    "weight": 2
  },
  "fromVerse": { // Start verse information
    "id": 1,
    "number": 1,
    "text": "ssss",
    "cleanText": "ssss",
    "characters": "sss"
  },
  "toVerse": { // end verse information
    "id": 2,
    "number": 2,
    "text": "dddd",
    "cleanText": "dddd",
    "characters": "dddd"
  },
  "date": 1476232607, // Created date timestamp
  "commentsCount": 0,
  "favoritesCount": 0,
  "likesCount": 0,
  "description": "xxxxx",
  "url": "xxxx",
  "sura": { // Sura information
    "id": 1,
    "name": "test",
    "verseCount": 2,
    "revealed": "labels.medinan",
    "chronologicalOrder": 1
  },
  "mentions": [], // Array of Mentioned users
  "verses": [ // Array of recited verses
    {
      "id": 1,
      "number": 1,
      "text": "ssss",
      "cleanText": "ssss",
      "characters": "sss"
    },
    {
      "id": 2,
      "number": 2,
      "text": "dddd",
      "cleanText": "dddd",
      "characters": "dddd"
    }
  ]
}
```

