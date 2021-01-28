<?php
use Tuupola\Middleware\{
    JwtAuthentication,
    CorsMiddleware,
};

$app->add(new CorsMiddleware([
    'origin' => ['*'],
    'methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'headers.allow' => ['Authorization', 'authorization', 'If-Match', 'If-Unmodified-Since'],
    'headers.expose' => ['Etag'],
    'credentials' => true,
    'cache' => 86400,
    'error' => function ($request, $response, $arguments) {
        $data['status'] = 'error';
        $data['message'] = $arguments['message'];
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($data);
    }
]));

// $app->add(
//     new JwtAuthentication([
//         'header' => 'Authorization',
//         'path' => '/',
//         'ignore' => ['/settings'],
//         'attribute' => 'jwt',
//         'secure' => getenv('SECURE') === 'false' ? false : true,
//         'secret' => getenv('JWT_SECRET'),
//         'cookie' => 'jwt',
//         'algorithm' => ['HS256'],
//         'error' => function ($response, $arguments) {
//             $data['status'] = 'error';
//             $data['message'] = $arguments['message'];
//             return $response
//                 ->withStatus(401)
//                 ->withHeader('Content-Type', 'application/json')
//                 ->withJson($data);
//         },
//         'before' => function ($request, $arguments) {
//             define( 'ROLE', $request->getAttribute('jwt')['role'] );
//             define( 'USER', $request->getAttribute('jwt')['id'] );
//         }
//     ])
// );

// $isAdmin = function ($request, $response, $next) {
//     if(ROLE > 2){
//         return $response
//             ->withStatus(401)
//             ->withHeader('Content-Type', 'application/json')
//             ->withJson(['error' => 'Unauthorized request. You don\'t have permissions']);
//     }
//     return $next($request, $response);
// };