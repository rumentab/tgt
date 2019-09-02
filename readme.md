# TitanGate task API

### 1. Install the API
##### 1.1. Clone the git repo
##### 1.2. Run composer
`bash#> composer update`

Note: if you don't want to run tests, run

`bash#> composer update --no-dev`
##### 1.3. If you run the API on Apache server, point the document root folder to the project's root folder and enable ovverriding.
```
DocumentRoot "/path/to/the/project/root/folder"
<Directory "/path/to/the/project/root/folder">
    Require all granted
    AllowOverride All
</Directory>
```
The API's router will transform all the requested URIs from this:
http://example.com/api/user/1
into this:
http://example.com/public/index.php?path=api/user/1

##### 1.4. If you use built in php server:
 If you use built in php server, go into the project's root folder and in the terminal type:
 
 ``bash#> php -S localhost:8000 -t public``
 
 This wil start the PHP's built in server
 In order to access the API, you have to call
 http://localhost:8000?path=api/user/1
 
 ##### 1.5. Run the tests:
 From the project's root folder type:
 
 ``bash#> ./vendor/bin/phpunit --bootstrap ./vendor/autoload.php tests``
 
 You should see the something like the following output:
```
PHPUnit 8.3.4 by Sebastian Bergmann and contributors.
...................                                               19 / 19 (100%)
Time: 267 ms, Memory: 4.00 MB
OK (19 tests, 38 assertions)
```

If you see something like this, you are ready to go.

### 2. Prepare the API
##### 2.1. Configure the API:
Open the config.php file and place your data source configuration. In our case it's the sqlite dsn. Go to ./app/config/config.php and edit the value:
```
return [
    'sqlite_dsn' => 'sqlite:/path/to/the/data/source/file.sqlite3'
];
```
Note: DO NOT remove the return statement. The file should return an array.

##### 2.2. The API has a built in command to prepare the data source.
Go to the API's entry point: http://example.com/api
If you see this:
```
{
"Title": "User list/insert/edit/delete API",
"Author": "Rumen Tabakov <rumen.tabakov@gmail.com>"
}
```
you are ready to go. But if you see this:
```
{
"Title": "User list/insert/edit/delete API",
"Author": "Rumen Tabakov <rumen.tabakov@gmail.com>",
"hint": "Data source is empty. Run <code>php bin/application install</code> from the console."
}
```
it means you don't have a data source configured. Open a terminal console and go to the project root folder. Type:

``bash#> php bin/application install``

This command will create an empty data source. If you want some dunny data preinstalled, run the command with ``--with-fixtures`` parameter:

``bash#> php bin/application install --with-fixtures``

This will prepare the data source and put some dumy data in it (25 dummy users in our case). The data source will be created in the path, your data source is configured.

### 3. Usage
- Get single user by ID (**GET** request):
http://example.com/api/user/1
http://localhost:8000/?path=api/user/1

    Response: ``HTTP1.1 200 OK`` if user is found, ``HTTP1.1 404 Not Found`` if no such user.

- Get single user by name/email (**GET** request):
http://example.com/api/user/John+Doe
http://localhost:8000/?path=api/user/john.doe@gmail.com

    Response: The same as above

- Get all users (**GET** request):
    http://example.com/api/user/all

    http://localhost:8000/?path=api/user/all

- Get all users sorted (**GET** request): (available fields: id, name, email)

    http://example.com/api/user/all/name/desc - Will return all users ordered by name in descending order
    
    http://localhost:8000/?path=api/user/all/email/asc - Will return all users ordered by email in ascending order

- Get paged users (**GET** request):

    http://example.com/api/user/some/5/6
    
    5 - users to get (5 users)
    
    6 - users to skip (6 users)

- Update an user:

    You have to send a **PUT** request to the
     
    http://example.com/api/user/update/1
    
    1 - the id of the user you want to update
    
    and your PUT request should contain 'update' key like this:
```
PUT: array [
    'update' => [
        'name' => John Doe,
        'email' => john.doe@yahoo.com
    ]
]
```
For instance if you have curl enabled on your system, you could use:

``bash#> curl -X PUT http://example.com/api/user/update/1?update%5Bemail%5D=patrick.lepri%40corn.ie``

where ``update%5Bemail%5D=patrick.lepri%40corn.ie`` is the URL encoded ``update[email]=patrick.lepri@corn.ie``

This request will update the email of the user with id=1.
Response is HTTP1.1 200 OK, a JSON with two keys - error and result. Both null if everything is OK. If something wrong with parameters, a HTTP1.1 400 Bad request response will be sent.
```
{
    error: null,
    response: null
}
```

- Insert user:
To insert an user you should submit a **POST** requets to the URL

    http://example.com/api/user/create

    Your POST request should contain a create key with the user data:
```
POST: array[
    'create' => [
        'name' => 'John Doe',
        'email' => 'john.doe@gmail.com',
        'password' => 'JohnDoe_123'
    ] 
]
```
Response: HTTP1.1 201 CREATED, a JSON with keys error (null if everything is OK) and result (id of the inserted user). If something wrong with parameters, a HTTP1.1 400 Bad request response will be sent.
```
{
    error: null,
    response: {
        id: 34
    }
}
```

- Delete user:

    To delete an user, you should submit a **DELETE** request to the URL:
    
    http://example.com/api/user/delete/1
    
    ``bash#> curl -X DELETE http://titangate-task.rum/api/user/delete/1``
    
    where 1 is the id of the user you want to delete
    Response is
     
    - HTTP1.1 200 OK  - if everything is OK and the user is deleted
    - HTTP1.1 404 Not Found - if no such user
    - HTTP1.1 400 Bad Request - if any problem with parameters.
    
    Response is a JSON with two keys - error and result. Both null if everything is OK. 
```
{
    error: null,
    response: null
}
```

Enjoy :)
