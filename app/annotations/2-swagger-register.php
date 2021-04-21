<?php
/**
 * @OA\Post(
 *     path="/auth/register",
 *     summary="Registers the new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *              type="object",
 *              @OA\Property( property="name",type="string"),
 *              @OA\Property( property="email",type="string"),
 *              @OA\Property( property="contact",type="string"),
 *              @OA\Property( property="password",type="string"),
 *              example={"name":"testUseer","email": "admin@gmail.com","contact":"9999999999", "password": "123456"}
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Signup done successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity, Params missing or invalid credentials ",
 *     )
 * )
 */
