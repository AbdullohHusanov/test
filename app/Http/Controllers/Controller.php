<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Test task API Documentation",
 *     description="Test task API Documentation Description",
 *     @OA\Contact(name="Swagger API Team")
 * )
 * @OA\Server(
 *     url="http://localhost:8000/",
 *     description="Local API server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"  
 * )
 */
abstract class Controller
{
    //
}
