Create a Secured RESTful API with CodeIgniter and JSON Web Tokens JWT
===============================================================================

Documentation URL - https://www.twilio.com/blog/create-secured-restful-api-codeigniter-php

The API built at the end of this tutorial will have the following functionalities:

1. Register a new user
2. Authenticate an existing user
3. Add a new client
4. Edit the details of an existing client
5. View all clients
6. View a single client by ID
7. Delete a single client by ID
8. If you want to use route with out token then user own controller like Home or Auth controller

Getting Started
----------------------
composer create-project codeigniter4/appstarter ci-secure-api

// move into the project
cd ci-secure-api

// run the application
php spark serve

Environment Variables Preparation
=========================================

$ cp env .env  // open it and set development model
CI_ENVIRONMENT = development


database.default.hostname = localhost
database.default.database = YOUR_DATABASE
database.default.username = YOUR_DATABASE_USERNAME
database.default.password = YOUR_DATABASE_PASSWORD
database.default.DBDriver = MySQLi # this is the driver for a MySQL connection. There are also drivers available for postgres & SQLite3.

Migrations and seeders
======================================

php spark migrate:create

add_client
add_user

Next, update the content of the add_client migration file as follows:
------------------

<?php
use CodeIgniter\Database\Migration;

class AddClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'unique' => true
            ],
            'retainer_fee' => [
                'type' => 'INT',
                'constraint' => 100,
                'null' => false,
                'unique' => true
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
        'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('client');
    }

    public function down()
    {
        $this->forge->dropTable('client');
    }
}

----------------------


Next, open the add_user migration file and replace its content with the following:
========================================================================================

--------------------

<?php

use CodeIgniter\Database\Migration;

class AddUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'unique' => true
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'unique' => true
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user');
    }

    public function down()
    {
        $this->forge->dropTable('user');
    }
}

------------------

php spark migrate

php spark make:seeder

The CLI will ask for a name called the 'ClientSeeder'. A ClientSeeder.php file will be created in the App/Database/Seeds directory. Open the file and replace its contents with the following:

------------------------------
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ClientSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 10; $i++) { //to add 10 clients. Change limit as desired
            $this->db->table('client')->insert($this->generateClient());
        }
    }

    private function generateClient(): array
    {
        $faker = Factory::create();
        return [
            'name' => $faker->name(),
            'email' => $faker->email,
            'retainer_fee' => random_int(100000, 100000000)
        ];
    }
}

-----------------------------

php spark db:seed ClientSeeder

Open the App/Models directory and create the following files:  

1. UserModel.php
2. ClientModel.php 


--------------------------------------------

<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
    protected $table = 'user';
    protected $allowedFields = [
        'name',
        'email',
        'password',
    ];
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    private function getUpdatedDataWithHashedPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $plaintextPassword = $data['data']['password'];
            $data['data']['password'] = $this->hashPassword($plaintextPassword);
        }
        return $data;
    }

    private function hashPassword(string $plaintextPassword): string
    {
        return password_hash($plaintextPassword, PASSWORD_BCRYPT);
    }
                                      
    public function findUserByEmailAddress(string $emailAddress)
    {
        $user = $this
            ->asArray()
            ->where(['email' => $emailAddress])
            ->first();

        if (!$user) 
            throw new Exception('User does not exist for specified email address');

        return $user;
    }
}

-----------------------------------------


Add the following code to the ClientModel.php file:

--------------------------------------------------

<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ClientModel extends Model
{
    protected $table = 'client';
    protected $allowedFields = [
        'name',
        'email',
        'retainer_fee'
    ];
    protected $updatedField = 'updated_at';

    public function findClientById($id)
    {
        $client = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$client) throw new Exception('Could not find client for specified ID');

        return $client;
    }
}


-----------------------------------------------------

JWT Implementation
===============================

composer require firebase/php-jwt

Once the installation is complete, add the following to your .env file:
----------
#JWT_SECRET_KEY key is the secret key used by the application to sign JWTS. Pick a stronger one for production.
JWT_SECRET_KEY=kzUf4sxss4AeG5uHkNZAqT1Nyi1zVfpz 
#JWT_TIME_TO_LIVE indicates the validity period of a signed JWT (in milliseconds)
JWT_TIME_TO_LIVE=3600


Next, create a helper function to get the secret key in the Services class. Go to App/Config/Services.php  and add the following:

public static function getSecretKey(){
    return getenv('JWT_SECRET_KEY');
} 

Create JWT Helper
-------------------
To help with the generation and verification of tokens, a Helper file will be created. This allows us to separate concerns in our application. In the App/Helpers directory create a file name jwt_helper.php. Your helper file should look like this:


----------------

<?php

use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;

function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) { //JWT is absent
        throw new Exception('Missing or invalid JWT in request');
    }
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return explode(' ', $authenticationHeader)[1];
}

function validateJWTFromRequest(string $encodedToken)
{
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
    $userModel = new UserModel();
    $userModel->findUserByEmailAddress($decodedToken->email);
}

function getSignedJWTForUser(string $email)
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'email' => $email,
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];

    $jwt = JWT::encode($payload, Services::getSecretKey());
    return $jwt;
}

----------------


Create Authentication Filter
=============================

In the App/Filters directory create a file named JWTAuthenticationFilter.php . This filter will allow the API check for the JWT before passing the request to the controller. If no JWT is provided or the provided JWT is expired, an HTTP_UNAUTHORIZED (401) response is returned by the API with an appropriate error message. Add the following to your file:

-----------------------------------------------

<?php

namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;

class JWTAuthenticationFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $authenticationHeader = $request->getServer('HTTP_AUTHORIZATION');

        try {

            helper('jwt');
            $encodedToken = getJWTFromRequest($authenticationHeader);
            validateJWTFromRequest($encodedToken);
            return $request;

        } catch (Exception $e) {

            return Services::response()
                ->setJSON(
                    [
                        'error' => $e->getMessage()
                    ]
                )
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);

        }
    }

    public function after(RequestInterface $request,
                          ResponseInterface $response,
                          $arguments = null)
    {
    }
}

-----------------------------------------------


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


NOTE: The Debug toolbar will be preloaded by default. There are known conflicts as the DebugToolbar is still under construction. To disable it, comment out the 'toolbar' item in the $globals array.

By adding these, the before function in JWTAuthenticationFilter.php will be called anytime a request is sent to an endpoint starting with the client. This means that the controller will only receive/handle the request if a valid token is present in the request header.  

Even though we don’t have any controller, we can check to see that our application is working so far. Open Postman and make a GET request to http://localhost:8080/client. You should see something similar to the screenshot below:

https://twilio-cms-prod.s3.amazonaws.com/images/sxeOq9LTTbNZ7nUhf46hx7SIjzICBLARWlYEYNFrowZ197.width-800.png

Next, open the App/Controllers/BaseController.php file and add the following function:

--------------------------

public function getResponse(array $responseBody,
                            int $code = ResponseInterface::HTTP_OK)
{
    return $this
        ->response
        ->setStatusCode($code)
        ->setJSON($responseBody);
}

-------------------------------------

This function will be used by your controllers to return JSON responses to the client.

NOTE: Don’t forget to import the ResponseInterface.

use CodeIgniter\HTTP\ResponseInterface;

To get around this, we’ll write a function that checks both fields in a request to get its content. Add the following to App/Controllers/BaseController.php:


------------------------------------------------

public function getRequestInput(IncomingRequest $request){
    $input = $request->getPost();
    if (empty($input)) {
        //convert request body to associative array
        $input = json_decode($request->getBody(), true);
    }
    return $input;
}

-----------------------------------------------

NOTE: Don’t forget to import the IncomingRequest class.
use CodeIgniter\HTTP\IncomingRequest;



Next, declare a function that runs the validation service against the $input from our previous function. This function is almost the same as the inbuilt validate function except that instead of running the check against the IncomingRequest, we run it against the input we captured from the getRequestInput function.


----------------------------

public function validateRequest($input, array $rules, array $messages =[]){
    $this->validator = Services::Validation()->setRules($rules);
    // If you replace the $rules array with the name of the group
    if (is_string($rules)) {
        $validation = config('Validation');

        // If the rule wasn't found in the \Config\Validation, we
        // should throw an exception so the developer can find it.
        if (!isset($validation->$rules)) {
            throw ValidationException::forRuleNotFound($rules);
        }

        // If no error message is defined, use the error message in the Config\Validation file
        if (!$messages) {
            $errorName = $rules . '_errors';
            $messages = $validation->$errorName ?? [];
        }

        $rules = $validation->$rules;
    }
    return $this->validator->setRules($rules, $messages)->run($input);
}
--------------------------------------

NOTE: Don’t forget to import the necessary classes.

use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Services;

With this in place, let’s add the logic to register and authenticate users.

Authentication Controller

Next, create a file name Auth.php in the App/Controllers directory. Update the file as shown below:


------------------------------------------

<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

class Auth extends BaseController
{
    /**
     * Register a new user
     * @return Response
     * @throws ReflectionException
     */
    public function register()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[user.email]',
            'password' => 'required|min_length[8]|max_length[255]'
        ];

 $input = $this->getRequestInput($this->request);
        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $userModel = new UserModel();
       $userModel->save($input);
     

       

return $this
            ->getJWTForUser(
                $input['email'],
                ResponseInterface::HTTP_CREATED
            );

    }

    /**
     * Authenticate Existing User
     * @return Response
     */
    public function login()
    {
        $rules = [
            'email' => 'required|min_length[6]|max_length[50]|valid_email',
            'password' => 'required|min_length[8]|max_length[255]|validateUser[email, password]'
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

$input = $this->getRequestInput($this->request);


        if (!$this->validateRequest($input, $rules, $errors)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
       return $this->getJWTForUser($input['email']);

       
    }

    private function getJWTForUser(
        string $emailAddress,
        int $responseCode = ResponseInterface::HTTP_OK
    )
    {
        try {
            $model = new UserModel();
            $user = $model->findUserByEmailAddress($emailAddress);
            unset($user['password']);

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        'access_token' => getSignedJWTForUser($emailAddress)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}
--------------------------------------------------------------------------

Registration
To successfully register a new user the following fields are required:
A name.
An email address in a valid format that is not less than 8 characters and not more than 255 characters.
A password that is not less than 8 characters and not more than 255 characters.

The incoming request is checked against the specified rules. Invalid requests are discarded with an HTTP_BAD_REQUEST code (400) and an error message. If the request is valid, the user data is saved and a token is returned along with the user’s saved details (excluding the password). The HTTP_CREATED (201) response lets the client know that a new resource has been created.

Making a POST request to the register endpoint (http://localhost:8080/auth/register) with a valid name, email address and password will result in a similar response to the one shown below:


https://twilio-cms-prod.s3.amazonaws.com/images/Z8ssC86QUet6HT5MZj6m7o-6_7wXNHhZ-pg-R10W79aELR.width-800.png


User Validation
===========================

Create a new directory called Validation in the app directory. Inside of the app/Validation folder, create a file named UserRules.php and add the following code to the file:

---------------------------

<?php

namespace App\Validation;

use App\Models\UserModel;
use Exception;

class UserRules
{
    public function validateUser(string $str, string $fields, array $data): bool
    {
        try {
            $model = new UserModel();
            $user = $model->findUserByEmailAddress($data['email']);
            return password_verify($data['password'], $user['password']);
        } catch (Exception $e) {
            return false;
        }
    }
}
-------------------------------------


Next, open the  App/Config/Validation.php file and modify the $ruleSets array to include your UserRules. $ruleSets should look like this:


public $ruleSets = [
    \CodeIgniter\Validation\Rules::class,
    \CodeIgniter\Validation\FormatRules::class,
    \CodeIgniter\Validation\FileRules::class,
    \CodeIgniter\Validation\CreditCardRules::class,
    \App\Validation\UserRules::class,
];

---------------------

With the custom validation rules in place, the authentication request works as expected. Test this sending a POST HTTP request to thehttp://localhost:8080/auth/login endpoint with the details of the user-created earlier:


https://twilio-cms-prod.s3.amazonaws.com/images/__rLNcoMj051Lqozo9myb0Du4CwGDBph6oiGO1MpIWlELW.width-800.png

Create Client Controller
================================
For the client controller, we will specify the routes in the app/Config/Routes.php file. Open the file and add the following routes:

$routes->get('/', 'Home::index');
$routes->get('client', 'Client::index');
$routes->post('client', 'Client::store');
$routes->get('client/(:num)', 'Client::show/$1');
$routes->post('client/(:num)', 'Client::update/$1');
$routes->delete('client/(:num)', 'Client::destroy/$1');
$routes->get('test', 'Client::test');
$routes->get('test1', 'Auth::test1');


By doing this, your API is able to handle requests with the same endpoint but different HTTP verbs accordingly.

Next, in the App/Controllers directory, create a file called Client.php. The contents of the file should be as follows:

-----------------------------

<?php

namespace App\Controllers;

use App\Models\ClientModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Client extends BaseController
{
    /**
     * Get all Clients
     * @return Response
     */
    public function index()
    {
        $model = new ClientModel();
        return $this->getResponse(
            [
                'message' => 'Clients retrieved successfully',
                'clients' => $model->findAll()
            ]
        );
    }

    /**
     * Create a new Client
     */
    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[client.email]',
            'retainer_fee' => 'required|max_length[255]'
        ];

 $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $clientEmail = $input['email'];

        $model = new ClientModel();
        $model->save($input);
        

        $client = $model->where('email', $clientEmail)->first();

        return $this->getResponse(
            [
                'message' => 'Client added successfully',
                'client' => $client
            ]
        );
    }

    /**
     * Get a single client by ID
     */
    public function show($id)
    {
        try {

            $model = new ClientModel();
            $client = $model->findClientById($id);

            return $this->getResponse(
                [
                    'message' => 'Client retrieved successfully',
                    'client' => $client
                ]
            );

        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find client for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
}
==========================================

The index, store, and show functions are used to handle requests to view all clients, add a new client, and show a single client respectively.

Next, create two functions update and destroy. The update function will be used to handle requests to edit a client. None of the fields are required hence any expected value that isn’t provided in the request is removed before updating the client in the database. The destroy function will handle requests to delete a particular client.



=============================================



public function update($id)
    {
        try {

            $model = new ClientModel();
            $model->findClientById($id);

          $input = $this->getRequestInput($this->request);

          

            $model->update($id, $input);
            $client = $model->findClientById($id);

            return $this->getResponse(
                [
                    'message' => 'Client updated successfully',
                    'client' => $client
                ]
            );

        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    public function destroy($id)
    {
        try {

            $model = new ClientModel();
            $client = $model->findClientById($id);
            $model->delete($client);

            return $this
                ->getResponse(
                    [
                        'message' => 'Client deleted successfully',
                    ]
                );

        } catch (Exception $exception) {
            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

With this in place, our API is set for consumption. Restart your application and test it by sending requests (via Postman, cURL, or your preferred application)

Add Access Token
===========================

Once you are done with the registration and login process, copy the value of the access_token from the response. Next, click on the Authorization tab and select Bearer Token from the dropdown and paste the value of the access_token copied earlier:

https://twilio-cms-prod.s3.amazonaws.com/images/1Eg7fjJhfibBDcYK0N9A6JY8kmv13Mc9ewRnI-QTFd422w.width-800.png

1. Create New Client
-----------------------
To create a new client, send a POST HTTP request to http://localhost:8080/client:

https://twilio-cms-prod.s3.amazonaws.com/images/YJ9waXS0mNhogV4LAnqP3kStLd-mpnWmvCRE-YLDBhDIBF.width-800.png


Get All Clients
---------------------
To fetch the list of Clients created so far, send a GET HTTP request to http://localhost:8080/client:

https://twilio-cms-prod.s3.amazonaws.com/images/z0VineD0cPLMseIM8z9asYjhNPwDP-aqzUpEdwApJI89Xk.width-800.png


========================================================================
For more details of API see the other CI4_JWT_seconddocumentation
Thanks for visite
=============================

















