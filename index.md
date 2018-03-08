# General Documentation
Contains documentation from here and there… About everything for now, until I care to split different subjects into their own files.


# Routing

Routing in Alpaca is very easy. You only need to specify what controller and method each route should call. You also define if route is meant for GET or POST requests. That’s about it.


## Defining Routes

Defining routes consists of couple parts. First, you define if your route is GET or POST route. Then you define the route itself, and the counterpart which is controller and it’s method.

For example:

```php
'&: company/about' => 'CompanyController@about'
```

So… This is just as simple than it seems. Or easier actually. First, we see an ‘&’ sign, which simply means that we want this route to be a GET route. Then we add a semicolon and the route (the following whitespace is optional but recommended). On the other side we have the target of the route or *action*. This consists of controller and method separated by ‘@’ sign.

Available request methods are currently:

```
& = GET
$ = POST
```


## Parameters

Of course you can pass parameters to your controller. To use parameters, you need to 
define them in your routes.

For example:

```php
'&: users/{userId}/posts/{postId}' => 'UserController@posts'
```

The pieces wrapped in brackets are parameters. Parameters are passed to your method, in this case, *about()* method, as function arguments in the exact order they are defined in the route.

The *about()* method from the example above:

```php
public function about($companyId, $postId) { ... };
```

Post requests are a bit different. For post request you define your route just as a GET route, but instead of ‘&’ selector you use ‘$’. Accessing the data is the part where it differs from parameters. 

You can get the data passed within a POST request using an instance of **Request** object which is automatically passed to your every method that is defined to be using POST method in routes.

As the instance of the Request object is passed to the method as the first argument, possible parameters coming from the route, come after the object (see example below).

For example:

```php
// The route in the config/routes.php
'$: company/{?companyId}/update' => 'CompanyController@update'

// The html-form in the vie that sends the POST request
<form action="company/12/update" method="POST">
  <input type="text" name="company_name" />
  <input type="email" name="founded_at" />

  <button type="submit">Submit</button>
</form>

// The method within CompanyController
public function update(Request $request, $companyId)
{ 
  if ($companyId == 1) {

    // Accessing the post data through data()-method
    $name = $request->data('company_name');
    $foundedAt = $request->data('founded_at');

    // Or you can get all the data in an array
    $companyData = $request->data();
  }
}
```

As you can see in the example above, the *data()*-method of the Request object, returns the specified data piece or if you don’t pass any arguments to the method, you get all the data at once.

## Optional parameters
If you define a parameter to be optional, it simply means that the route is accessible whether the user did or didn’t pass a parameter within the route. To define an optional parameter, use a question mark in front of the parameter name.


```php
'&: users/{?userId}' => 'UserController@index'
```

In the controller, you can use opt() helper function to prevent unwanted errors and set a default value if the parameter is not defined, which should be normal with optional parameters.



# Helpers

Alpaca provides pack of useful helper functions.

## Opt()
```php
mixed opt(variable, [default=null]);
```

Returns the given variable or default value if the variable is not defined. By default, the default value is null, but you can define it to anything you want by passing it as second argument.

Examples:

```php
// No default value
$showSidebar = opt($request->data('showSidebar'));

// Default value is integer, 1
$currentPageId = opt($request->data('showSidebar'), 1);
```


## Array_unset()
```php
array array_unset(array, [index=0]);
```

Removes an element from given array and reindexes remaining elements to start from 0. By default, the index is zero so it removes the first element.

Examples:

    // Returns [[0] => 'Samsung', [1] => 'Banana']
    $arrayWithoutApple = opt(['Apple', 'Samsung', 'Banana']);
    
    // Returns [[0] => 'Apple', [1] => 'Banana']
    $arrayWithoutSamsung = opt(['Apple', 'Samsung', 'Banana'], 1);

