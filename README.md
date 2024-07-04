# NextPHP Rest Package

The [NextPHP Rest](https://packagist.org/packages/nextphp/rest) package provides powerful routing capabilities and HTTP handling for PHP developers. This package supports all RESTful methods (GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD, TRACE, CONNECT, PRI) and various response formats such as JSON, XML, HTML, TEXT, and CSV. It simplifies the creation of APIs by allowing developers to define routes and controllers using attributes, ensuring a clean and efficient codebase.

This package is part of the [NextPHP Framework](https://github.com/nextphp-projects/nextphp), a modern and lightweight PHP framework designed for performance and scalability. [NextPHP](https://nextphp.io) aims to provide a comprehensive suite of tools and libraries to streamline the development process.

## Features

- Support for all RESTful methods (GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD, TRACE, CONNECT, PRI)
- Response formats: JSON, XML, HTML, TEXT, CSV
- Attribute-based route definitions
- Middleware support
- Easy integration with existing projects

## Installation

### Installing via Composer

To install the NextPHP Rest package, you need to add it to your project using Composer.

```bash
composer require nextphp/rest
```


# Example Project using NextPHP Rest
This is an example project demonstrating the usage of the NextPHP Rest package, which includes routing and HTTP handling capabilities.

Basic Usage
Defining Routes
Define routes using attributes to map HTTP methods to controller actions.

## Usage

### Using Controller

```php
<?php
namespace Example\Controller;

use NextPHP\Rest\Http\Get;
use NextPHP\Rest\Http\Post;
use NextPHP\Rest\Http\Put;
use NextPHP\Rest\Http\Delete;
use NextPHP\Rest\Http\Patch;
use NextPHP\Rest\Http\RouteGroup;
use NextPHP\Rest\Http\Middleware;

#[RouteGroup('/api/users')]
class UserController
{
    #[Get('/')]
    public function getAllUsers()
    {
        // logic to get all users
    }

    #[Post('/')]
    public function createUser()
    {
        // logic to create a user
    }

    #[Put('/{id}')]
    public function updateUser($id)
    {
        // logic to update a user
    }

    #[Delete('/{id}')]
    public function deleteUser($id)
    {
        // logic to delete a user
    }

    #[Patch('/{id}')]
    public function partiallyUpdateUser($id)
    {
        // logic to partially update a user
    }
}
```

### Advanced Entity Usage
### Middleware Usage

Define middleware using attributes to apply them to routes. You can apply middleware to an entire controller class with `#[Middleware(AuthMiddleware::class)]`, or to individual routes with method-specific attributes.

```php
#[RouteGroup('/api')]
#[Middleware(AuthMiddleware::class)]
class UserController
{
    #[Get('/users')]
    #[Middleware(AuthMiddleware::class)]
    public function getAllUsers()
    {
        // logic to get all users
    }
}    
```

### Generate AuthMiddleware Class
AuthMiddleware handle JWT authentication check for HTTP request and response.

```php
<?php
namespace Example;

use NextPHP\Rest\Http\Request;
use NextPHP\Rest\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class AuthMiddleware
 * 
 * A simple implementation of a PSR-7 http message interface and PSR-15 http handlers.
 *
 * Middleware for handling JWT authentication.
 *
 * @package NextPHP\Rest\Middleware
 */
class AuthMiddleware
{
    /**
     * Handles the incoming request and checks for JWT authentication.
     *
     * @param Request $request The HTTP request.
     * @param Response $response The HTTP response.
     * @param callable $next The next middleware or controller.
     * @return Response The modified response.
     */
    public function handle(Request $request, Response $response, callable $next): Response
    {
        $authHeader = $request->getHeaders()['Authorization'] ?? '';
        if (!$authHeader) {
            return $response->withStatus(401)->withJSON(['error' => 'Unauthorized']);
        }

        list($jwt) = sscanf($authHeader, 'Bearer %s');
        if (!$jwt) {
            return $response->withStatus(401)->withJSON(['error' => 'Unauthorized']);
        }

        try {
            $decoded = JWT::decode($jwt, new Key('your-secret-key', 'HS256'));
            // Token is valid, proceed with the request
            return $next($request, $response);
        } catch (\Exception $e) {
            return $response->withStatus(401)->withJSON(['error' => 'Unauthorized']);
        }
    }
}

```

### Service Layer Example
Services provide business logic and interact with repositories.

```php
<?php
namespace Example;

#[Service(description: 'User management service')]
class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Transactional]
    public function registerUser(array $userData): User
    {
        $user = new User();
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->password = password_hash($userData['password'], PASSWORD_DEFAULT);

        $userArray = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ];

        $this->userRepository->save($userArray);

        return $user;
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUserById(int $id): ?User
    {
        $userArray = $this->userRepository->find($id);
        if (!$userArray) {
            return null;
        }

        $user = new User();
        $user->id = $userArray['id'];
        $user->name = $userArray['name'];
        $user->email = $userArray['email'];
        $user->password = $userArray['password'] ?? '';

        return $user;
    }

    public function updateUser(int $id, array $data): ?User
    {
        $user = $this->getUserById($id);
        if (!$user) {
            return null;
        }

        foreach ($data as $key => $value) {
            if (property_exists($user, $key)) {
                $user->$key = $value;
            }
        }

        $userArray = get_object_vars($user);
        $this->userRepository->update($id, $userArray);

        return $user;
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);
        if (!$user) {
            return false;
        }

        $this->userRepository->delete($id);

        return true;
    }
}
```

### Example Project
Example for your Project Structure

```code
example/
├── src/
│   ├── Entity/User.php
│   ├── Repository/UserRepository.php
│   ├── Service/UserService.php
│   ├── Resource/UserResource.php
├── example.php
├── composer.json
└── README.md
```

### Example or example.php
To use the NextPHP Rest package, you can create an index.php file and use the router to handle various HTTP requests. Here is an example of how you can do this:

Example index.php

```php

<?php

require_once __DIR__ . '/vendor/autoload.php';

use NextPHP\Rest\DI\Container;
use NextPHP\Rest\Router;
use NextPHP\Rest\Http\Request;
use NextPHP\Rest\Http\Response;
use NextPHP\App\Resource\UserResource;
use NextPHP\App\Resource\PostResource;

$container = new Container();

$router = new Router([
    'baseUri' => '/nextphp-beta',
    'allowedOrigins' => [
        'http://allowed-origin.com' => ['GET', 'POST'],
        'http://another-allowed-origin.com' => ['GET', 'PUT'],
        '*' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD', 'TRACE', 'CONNECT', 'PRI']
    ]
], $container);

// DI
$router->registerRoutesFromController(UserResource::class);
$router->registerRoutesFromController(PostResource::class);

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$request = new Request($method, $uri, getallheaders(), file_get_contents('php://input'), $_GET, $_POST);
$response = new Response();

$response = $router->dispatch($request, $response);

if ($response) {
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $name => $value) {
        header("$name: $value");
    }
    echo $response->getBody();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error', 'message' => 'No response returned.']);
}

```

## Contributing

We welcome contributions! Here’s how you can help:

- **Report Issues:** Found a bug? Report it on GitHub.
- **Suggest Features:** Have an idea? Share it with us.
- **Submit Pull Requests:** Improve the codebase.
- **Enhance Documentation:** Help us improve our docs.

For more details, see our [Contribution Guidelines](Contributing.md).

## Resources

- [Official Website](https://nextphp.io)
- [GitHub Repository](https://github.com/nextphp-projects/nextphp)
- [Documentation](https://github.com/nextphp-projects/nextphp)

## Join Our Community

- **Twitter:** Follow us on [Twitter](https://twitter.com/NextPHPOfficial)
- **Discord:** Join our [Discord](https://discord.gg/nextphp) community.

## Contact Us

- **Email:** support@nextphp.io
- **Forum:** [NextPHP Mastodon](https://mastodon.social/@nextphp)
- **GitHub Issues:** [NextPHP GitHub](https://github.com/nextphp-projects/nextphp/issues)

Thank you for being part of the NextPHP community!

<br><br><hr><br>

### FAQ

### Q: How do I define a route?

A: Use the #[Get], #[Post], #[Put], #[Delete], #[Patch], etc. attributes to define a method as a route handler. Use #[RouteGroup] to define a common prefix for a group of routes.

For more details, see our FAQ.