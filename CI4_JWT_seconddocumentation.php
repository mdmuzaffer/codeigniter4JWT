
1. Getting All client details
==================================

Method :- GET
Url :- http://localhost/jwtci4/public/client
Header:-  Content-Type:application/json
Accept:application/json
Authorization:Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im11emFmZmVyQGdtYWlsLmNvbSIsImlhdCI6MTYxMzE5NDk4OCwiZXhwIjoxNjEzMTk4NTg4fQ.MRliP8TIxZZdcJtHT4n11XSXlbFwNw35quEujka80ns

Data:- {
    "message": "Clients retrieved successfully",
    "clients": [
        {
            "id": "1",
            "name": "Ms. Reba Hamill",
            "email": "leonard76@schinner.com",
            "retainer_fee": "22152787",
            "updated_at": null,
            "created_at": "2021-01-21 23:15:54"
        },
        {
            "id": "11",
            "name": "test 1",
            "email": "test@gmail.com",
            "retainer_fee": "889654",
            "updated_at": null,
            "created_at": "2021-01-22 00:38:09"
        }
    ]
}

=================================



2. Register auth user details
==================================

Method :- POST
Url :- http://localhost/jwtci4/public/auth/register
Header:-  No

Body:- name:Muzaffer
email:muzaffer10@gmail.com
password:muzaffer@123

Data :-

{
    "message": "User authenticated successfully",
    "user": {
        "id": "4",
        "name": "Muzaffer",
        "email": "muzaffer10@gmail.com",
        "updated_at": null,
        "created_at": "2021-02-13 11:17:25"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im11emFmZmVyMTBAZ21haWwuY29tIiwiaWF0IjoxNjEzMTk1MjQ1LCJleHAiOjE2MTMxOTg4NDV9.1fuqKDyIY5tg0w2CXwMDRetTbFr6QDfzBm7WYuPGJ4w"
}


=====================================


3. Login auth user
==================================

Method :- POST
Url :- http://localhost/jwtci4/public/auth/login
Header:-  No

Body:- email:muzaffer10@gmail.com
password:muzaffer@123

Data :-

{
    "message": "User authenticated successfully",
    "user": {
        "id": "4",
        "name": "Muzaffer",
        "email": "muzaffer10@gmail.com",
        "updated_at": null,
        "created_at": "2021-02-13 11:17:25"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im11emFmZmVyMTBAZ21haWwuY29tIiwiaWF0IjoxNjEzMjAyNDQ0LCJleHAiOjE2MTMyMDYwNDR9.kDzBRRlVKaeYElDLt_q2D6HCONKhEL1qFqI7d_zZieg"
}


4. Add clients
==================================
Method :- POST
Url :- http://localhost/jwtci4/public/client
Header:-  Header:-  Content-Type:application/json
Accept:application/json
Authorization:Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im11emFmZmVyQGdtYWlsLmNvbSIsImlhdCI6MTYxMzE5NDk4OCwiZXhwIjoxNjEzMTk4NTg4fQ.MRliP8TIxZZdcJtHT4n11XSXlbFwNw35quEujka80ns


Body:- name:test
email:test@gmail.com
retainer_fee:889654

Data :-
{
    "message": "Client added successfully",
    "client": {
        "id": "13",
        "name": "Test",
        "email": "test71@gmail.com",
        "retainer_fee": "8896541",
        "updated_at": null,
        "created_at": "2021-02-13 13:25:09"
    }
}

5. Display client details using ID
==================================
Method :- GET
Url :- http://localhost/jwtci4/public/client/11
Header:-  Content-Type:application/json
Accept:application/json
Authorization:Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im11emFmZmVyQGdtYWlsLmNvbSIsImlhdCI6MTYxMzE5NDk4OCwiZXhwIjoxNjEzMTk4NTg4fQ.MRliP8TIxZZdcJtHT4n11XSXlbFwNw35quEujka80ns


Body:- NO

Data :-
{
    "message": "Client retrieved successfully",
    "client": {
        "id": "11",
        "name": "test 1",
        "email": "test@gmail.com",
        "retainer_fee": "889654",
        "updated_at": null,
        "created_at": "2021-01-22 00:38:09"
    }
}


6. Update client details using ID
==================================
Method :- POST
Url :- http://localhost/jwtci4/public/client/11
Header:-  Content-Type:application/json
Accept:application/json
Authorization:Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im11emFmZmVyQGdtYWlsLmNvbSIsImlhdCI6MTYxMzE5NDk4OCwiZXhwIjoxNjEzMTk4NTg4fQ.MRliP8TIxZZdcJtHT4n11XSXlbFwNw35quEujka80ns

Body:- name:test 1
email:test@gmail.com
retainer_fee:889654

Data :-
{
    "message": "Client updated successfully",
    "client": {
        "id": "11",
        "name": "test 1",
        "email": "test@gmail.com",
        "retainer_fee": "889654",
        "updated_at": null,
        "created_at": "2021-01-22 00:38:09"
    }
}

7. Update client details using ID
==================================
Method :- POST
Url :- http://localhost/jwtci4/public/client/11
Header:-  Content-Type:application/json
Accept:application/json
Authorization:Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im11emFmZmVyQGdtYWlsLmNvbSIsImlhdCI6MTYxMzE5NDk4OCwiZXhwIjoxNjEzMTk4NTg4fQ.MRliP8TIxZZdcJtHT4n11XSXlbFwNw35quEujka80ns

Body:- name:test 1
email:test@gmail.com
retainer_fee:889654

Data :-
{
    "message": "Client updated successfully",
    "client": {
        "id": "11",
        "name": "test 1",
        "email": "test@gmail.com",
        "retainer_fee": "889654",
        "updated_at": null,
        "created_at": "2021-01-22 00:38:09"
    }
}


8. Delete client details using ID
==================================
Method :- DELETE
Url :- http://localhost/jwtci4/public/client/11
Header:-  Content-Type:application/json
Accept:application/json
Authorization:Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im11emFmZmVyMTBAZ21haWwuY29tIiwiaWF0IjoxNjEzMjAyNDQ0LCJleHAiOjE2MTMyMDYwNDR9.kDzBRRlVKaeYElDLt_q2D6HCONKhEL1qFqI7d_zZieg

Body:- name:test 1
email:test@gmail.com
retainer_fee:889654

Data :-
{
    "message": "Client updated successfully",
    "client": {
        "id": "11",
        "name": "test 1",
        "email": "test@gmail.com",
        "retainer_fee": "889654",
        "updated_at": null,
        "created_at": "2021-01-22 00:38:09"
    }
}



If you protectecet url or not see this documentation

================================

As you can see, the JWT Helper is first loaded, then the getJWTFromRequest and validateJWTFromRequest functions are used to ensure that the request is from an authenticated user with a valid token.


Register your JWTAuthentication filter and specify the route you want it to protect. This is done in the App/Config/Filters.php file. Update the $aliases and $filters array as follows:

-------------------------------------------------

<?php 
namespace Config;

use App\Filters\JWTAuthenticationFilter;
use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'honeypot' => \CodeIgniter\Filters\Honeypot::class,
        'auth' => JWTAuthenticationFilter::class // add this line
    ];

    // global filters
    // method filters
    public $filters = [
      'auth' => [
        'before' => [
            'client/*',
            'client'
      ],
    ]
  ];
}

--------------------------------------





