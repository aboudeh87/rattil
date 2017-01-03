# Rattil API Documentation

To send authenticated request, add `Authorization` header to the request with 
`Bearer {token}` value.

To get `JSON` responses, add `Accept` header with `application/json` value.

All the URIs in this documentation has a prefix: `/api/v1/`.

APIs That need methods (`PUT`, `PATCH`), it should sent as `POST` request
and include the needed method as an extra parameter `_method`.

### Index

- [Responses](#responses)
- [Authentication](#authentication)
    * [Login](#login)
    * [Logout](#logout)
    * [Register](#register)
    * [Reset password request](#reset-password-request)
    * [Social media authentication](#social-media-authentication)
- [Suwar](#suwar)
    * [Suwar list](#suwar-list)
    * [Show Sura information](#show-sura-information)
- [Recitations](#recitations)
    * [recitation list of an user](#recitation-list-of-an-user)
    * [Recitation list of followed people](#recitation-list-of-followed-people)
    * [Latest recitations list](#latest-recitations-list)
    * [Popular recitations list](#popular-recitations-list)
    * [Post new Recitation](#post-new-recitation)
    * [Update Recitation](#update-recitation)
    * [Delete Recitation](#delete-recitation)
    * [Listen Recitation](#listen-recitation)
    * [Show Recitation information](#show-recitation-information)
    * [Search Recitations](#search-recitations)
- [Profiles](#profiles)
    * [Update profile](#update-profile)
    * [Upload a profile image](#upload-a-profile-image)
    * [Delete profile image](#delete-profile-image)
    * [Show profile information](#show-profile-information)
    * [Search users](#search-users)
- [Follow system](#follow-system)
    * [Follow an user](#follow-an-user)
    * [Un-Follow an user](#un-Follow-an-user)
    * [Delete a follower](#delete-a-follower)
    * [Pending requests list](#pending-requests-list)
    * [Accept following request](#accept-following-request)
    * [Decline following request](#decline-following-request)
    * [The followers list of an user](#the-followers-list-of-an-user)
    * [The following list of an user](#the-following-list-of-an-user)
- [Likes system](#likes-system)
    * [Like a recitation](#like-a-recitation)
    * [Un-Like a recitation](#un-like-a-recitation)
- [Favorites system](#favorites-system)
    * [Favorite a recitation](#favorite-a-recitation)
    * [Un-Favorite a recitation](#un-favorite-a-recitation)
    * [Favorites list of an user](#favorites-list-of-an-user)
- [Comments](#comments)
    * [Comments list](#comments-list)
    * [Add a new comment](#add-a-new-comment)
    * [Delete a comment](#delete-a-comment)
- [Reports](#reports)
    * [Report a model](#report-a-model)
- [Assists APIs](#assists-apis)
    * [Languages list](#languages-list)
    * [Countries list](#countries-list)
    * [Narrations list](#narrations-list)
    * [Reasons list](#reasons-list)
    
---
    
#### Responses

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

**Validation error response:**
```json
{
    "success": false,
    "message": "Error text message",
    "errors": {
        "field-name": [ // array of errors messages
            "Error text message"
        ]
    }
}
```

---

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

---

# Suwar

#### Suwar list

* _url:_ `/suwar`
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response with pagination like:

```json
[   // arrau of models
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

---

# Recitations

#### recitation list of an user

* _url:_ `/recitations/list/{model?}` // `{model}` is the user `ID` or `username`. if you leave it empty will return the recitations of logged in user. 
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response with pagination like:

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
        "avatar": null,
        "certified": false
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

#### Recitation list of followed people

* _url:_ `/recitations/following`
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response pagination like:

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
        "avatar": null,
        "certified": false
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

#### Latest recitations list

* _url:_ `/recitations/latest`
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response pagination like:

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
        "avatar": null,
        "certified": false
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

#### Popular recitations list

* _url:_ `/recitations/popular`
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response pagination like:

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
        "avatar": null,
        "certified": false
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

#### Post new Recitation

* _url:_ `/recitations`
* _method_: `POST`
* Authentication ***required***
* _parameters_:
    * `sura_id` ***required*** // The ID of recited Sura
    * `narration_id` ***required*** // The ID of recite's narration
    * `from_verse` ***required*** // ID of start verse
    * `to_verse` ***required*** // ID of end verse
    * `file` ***required*** // file of recitation
    * `description` ***optional*** // The comment on the recitation
    * `mentions` ***optional*** // should be array of users IDs That mentioned in the post
* _response_: normal `Success response`.

#### Update Recitation

* _url:_ `/recitations/{model}` // `{model}` is the `ID` of recitation
* _method_: `POST`
* Authentication ***required***
* _parameters_:
    * `sura_id` ***required*** // The ID of recited Sura
    * `narration_id` ***required*** // The ID of recite's narration
    * `from_verse` ***required*** // ID of start verse
    * `to_verse` ***required*** // ID of end verse
    * `description` ***optional*** // The comment on the recitation
    * `mentions` ***optional*** // should be array of users IDs That mentioned in the post
* _response_: normal `Success response`.

#### Delete Recitation

* _url:_ `/recitations/{model}` // `{model}` is the `ID` of recitation
* _method_: `Delete`
* Authentication ***required***
* _response_: normal `Success response`.

#### Listen Recitation

This end point increase the number of listeners of recitation.

* _url:_ `/recitations/{model}/listen` // `{model}` is the `ID` of recitation
* _method_: `POST`
* _response_: normal `Success response`.

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
    "avatar": null,
    "certified": false
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
  "verified": false,
  "commentsCount": 0,
  "favoritesCount": 0,
  "likesCount": 0,
  "listenersCount": 0,
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

#### Search Recitations 

* _url:_ `/recitations/search`
* _method_: `POST`
* _parameters_:
    * `keyword` ***required*** // minimum 2 characters 
    * `sura_id` ***optional*** // could be an `array` or `string` with commas between Suwar IDs
    * `narration_is` ***optional*** // could be an `array` or `string` with commas between narrations IDs
* Authentication ***Required***
* _response_: a `JSON` response pagination like:

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
        "avatar": null,
        "certified": false
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

---

## Profiles 

#### Update profile

* _url:_ `/profiles`
* _method_: `POST`
* _parameters_:
    * `name` ***required*** // maximum 255 characters 
    * `image` ***optional*** // profile image file
    * `bio` ***optional*** //  Maximum 500 characters
    * `language_key` ***optional*** // should be a key exists in languages database
    * `country` ***optional*** // should be a key exists in countries database
    * `city` ***optional***
    * `gender` ***optional*** // available values: [`male`|`female`]
    * `email_notification` ***optional*** // boolean
    * `private` ***optional*** // boolean
* Authentication ***Required***
* _response_: normal `Success response`.


#### Upload a profile image

* _url:_ `/profiles/{model}/avatar` // `{model}` is the `username` or `ID` of logged in user
* _method_: `POST`
* _parameters_:
    * `image` ***required*** // image file
* Authentication ***Required***
* _response_: normal `Success response`.


#### Delete profile image

* _url:_ `/profiles/{model}/avatar` // `{model}` is the `username` or `ID` of logged in user
* _method_: `DELETE`
* Authentication ***Required***
* _response_: normal `Success response`.


#### Show profile information

* _url:_ `/profiles/{model?}` // `{model}` is the `user ID` or `username`. if you leave it empty will return the profile of logged in user.
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response like: 

```json
{
    "id": 4,
    "name": "AbdulKader Zein Eddin",
    "username": "abd",
    "avatar": "http://rattil.app/public/profiles/a87ff679a2f3e71d9181a67b7542122c.jpg",
    "bio": "Bio text",
    "language_key": "en",
    "country": "SA",
    "city": "Al Riyad",
    "gender": "male",
    "email_notification": 1, // This variable will return only if the profile is for logged in user
    "private": 0, // This variable will return only if the profile is for logged in user
    "followed": 0, // 0 = Not followed, 2 = Pending acceptance, 1 = followed
    "followers_count": 0,
    "following_count": 0,
    "favorites_count": 0,
    "recitations_count": 0
}
```

#### Search users

* _url:_ `/users/search`
* _method_: `GET`
* _parameters_:
    * `query` ***required*** // minimum `2` characters. search keyword 
    * `certified` ***optional*** // `boolean`
* Authentication ***Required***
* _response_: a `JSON` response like: 

```json
{
  "total": 3,
  "limit": 15,
  "currentPage": 1,
  "data": [
    {
      "id": 7,
      "name": "Abdul Kader Zein Eddin",
      "username": "abd",
      "avatar": null,
      "country": "tr",
      "recitations_count": 0
    }
  ]
}
```

---

## Follow system 

#### Follow an user

* _url:_ `/follows/{model}` // `{model}` is the `User ID` or `username` to follow
* _method_: `POST`
* Authentication ***Required***
* _response_: normal `Success response`.

#### Un-Follow an user

* _url:_ `/follows/{model}` // `{model}` is the `User ID` or `username` to un-follow
* _method_: `DELETE`
* Authentication ***Required***
* _response_: normal `Success response`.


#### Delete a follower

* _url:_ `/follows/{model}/follower` // `{model}` is the `User ID` or `username` to delete
* _method_: `DELETE`
* Authentication ***Required***
* _response_: normal `Success response`.

#### Pending requests list

Return a list of un-confirmed "following requests" of a private user.

* _url:_ `/profiles/{model}/followers/pending` // `{model}` is the `User ID` or `username` of logged in user
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response like: 

```json
{
  "total": 1,
  "limit": 15,
  "currentPage": 1,
  "data": [
    {
      "id": 7, // the request ID
      "user_id": 2, // the user ID
      "name": "Mohammad",
      "username": "mhd",
      "avatar": null,
      "certified": 0,
      "date": 1482620154 // request date
    }
  ]
}
```

#### Accept following request

* _url:_ `/profiles/{model}/followers/pending/{id}` // `{model}` is the `User ID` or `username` of logged in user, {id} is the Following request ID.
* _method_: `POST`
* Authentication ***Required***
* _response_: normal `Success response`.

#### Decline following request

* _url:_ `/profiles/{model}/followers/pending/{id}` // `{model}` is the `User ID` or `username` of logged in user, {id} is the Following request ID.
* _method_: `DELETE`
* Authentication ***Required***
* _response_: normal `Success response`.

#### The followers list of an user

* _url:_ `/profiles/{model}/followers` // `{model}` is the `username` or `ID` of user.
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response like: 

```json
{
    "total": 1,
    "limit": 15,
    "currentPage": 1,
    "data": [ // Array of users
        {
            "id": 4,
            "name": "aboudeh",
            "username": "abd",
            "avatar": "http://rattil.app/public/profiles/a87ff679a2f3e71d9181a67b7542122c.jpg",
            "country": null,
            "recitations_count": 1
        }
    ]
}
```

#### The following list of an user

* _url:_ `/profiles/{model}/following` // `{model}` is the `username` or `ID` of user.
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response like: 

```json
{
    "total": 1,
    "limit": 15,
    "currentPage": 1,
    "data": [ // Array of users
        {
            "id": 4,
            "name": "aboudeh",
            "username": "abd",
            "avatar": "http://rattil.app/public/profiles/a87ff679a2f3e71d9181a67b7542122c.jpg",
            "country": null,
            "recitations_count": 1
        }
    ]
}
```

---

## Likes system

#### Like a recitation

* _url:_ `/likes/recitation/{model}` // `{model}` is the `ID` of recitation
* _method_: `POST`
* Authentication ***Required***
* _response_: normal `Success response`.

#### Un-Like a recitation

* _url:_ `/likes/recitation/{model}` // `{model}` is the `ID` of recitation
* _method_: `DELETE`
* Authentication ***Required***
* _response_: normal `Success response`.


## Favorites system

#### Favorite a recitation

* _url:_ `/favorites/{model}` // `{model}` is the `ID` of recitation
* _method_: `POST`
* Authentication ***Required***
* _response_: normal `Success response`.

#### Un-Favorite a recitation

* _url:_ `/favorites/{model}` // `{model}` is the `ID` of recitation
* _method_: `DELETE`
* Authentication ***Required***
* _response_: normal `Success response`.


#### Favorites list of an user

* _url:_ `/profiles/{model}/favorites` // `{model}` is the `username` or `ID` of user.
* _method_: `GET`
* Authentication ***Required***
* _response_: a `JSON` response like: 

```json
{
    "total": 1,
    "limit": 15,
    "currentPage": 1,
    "data": [ // Array of favorites recitations
{
      "id": 2,
      "slug": "xxxx",
      "user": {
        "id": 4,
        "name": "abdulkader",
        "username": "abd",
        "avatar": "http://rattil.app/public/profiles/a87ff679a2f3e71d9181a67b7542122c.jpg",
        "country": null,
        "recitations_count": 1
      },
      "narration": {
        "id": 1,
        "name": "Test",
        "weight": 2
      },
      "fromVerse": {
        "id": 1,
        "number": 1,
        "text": "ssss",
        "cleanText": "ssss",
        "characters": "sss"
      },
      "toVerse": {
        "id": 2,
        "number": 2,
        "text": "dddd",
        "cleanText": "dddd",
        "characters": "dddd"
      },
      "date": 1476232607,
      "commentsCount": 0,
      "favoritesCount": 0,
      "likesCount": 0
    }    ]
}
```

---

## Comments

#### Comments list

return the comments of a recitation

* _url:_ `/recitations/{model}/comments` // `{model}` is the `ID` of recitation
* _method_: `GET`
* Authentication ***required***
* _response_: a `JSON` response like: 

```json
{
    "total": 1,
    "limit": 15,
    "currentPage": 1,
    "data": [ // Array of comments
    {
      "id": 1,
      "text": "Test Comment",
      "url": null,
      "verified": false,
      "user": {
        "id": 4,
        "name": "abdulkader",
        "username": "abd",
        "avatar": "http://rattil.app/public/profiles/a87ff679a2f3e71d9181a67b7542122c.jpg",
        "verified": false
      },
      "date": 1476232607
    }
  ]
}
```

#### Add a new comment

* _url:_ `/recitations/{model}/comments` // `{model}` is the `ID` of recitation
* _method_: `POST`
* Authentication ***required***
* _parameters_:
    * `text` ***required*** // Comment text
    * `file` ***optional*** // audio file. Only for **certified** users
* _response_: normal `Success response`.

#### Delete a comment

* _url:_ `/comments/{model}` // `{model}` is the `ID` of Comment
* _method_: `DELETE`
* Authentication ***required***
* _response_: normal `Success response`.

---

## Reports

#### Report a model

* _url:_ `/{type}/{model}/report` // `type` is the type of model [`recitations`|`comments`], `{model}` is the `ID` of model
* _method_: `POST`
* Authentication ***required***
* _parameters_:
    * `reason_id` ***required*** // Comment text
    * `message` ***optional*** // description message, `max:500` characters
* _response_: normal `Success response`.

---

## Assists APIs

#### Languages list

* _url:_ `/assists/languages`
* _method_: `GET`
* Authentication ***required***
* _response_: a `JSON` response like: 

```json
[
    {
        "key": "en",
        "name": "English"
    },
    {
        "key": "ar",
        "name": "العربية"
    }
]
```

#### Countries list

* _url:_ `/assists/countries`
* _method_: `GET`
* Authentication ***required***
* _response_: a `JSON` response like: 

```json
[
    {
        "key": "sa",
        "name": "Saudi Arabia"
    },
    {
        "key": "tr",
        "name": "Turkey"
    }
]
```

#### Narrations list

* _url:_ `/assists/narrations`
* _method_: `GET`
* Authentication ***required***
* _response_: a `JSON` response like: 

```json
[
    {
        "id": 1,
        "weight": 1,
        "name": "Test narration"
    }
]
```

#### Reasons list

Get the list of report's reasons.  
It is related to the model that you want to report it.

* _url:_ `/assists/reasons/{type}` //available [`comments`|`recitations`]
* _method_: `GET`
* Authentication ***required***
* _response_: a `JSON` response like: 

```json
[
    {
        "id": 1,
        "name": "Reason of report"
    }
]
```
