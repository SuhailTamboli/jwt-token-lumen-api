<?php

namespace App\Http\Controllers;

use Illuminate\Http\Exception\HttpResponseException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;

class LoginController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function login(Request $request) {
        try {
            $this->validate($request, [
                'email' => 'required|email|max:255', 'password' => 'required',
            ]);
        } catch (HttpResponseException $ex) {
            return response()->json([
                        'error' => [
                            'message' => 'Invalid auth',
                            'status_code' => IlluminateResponse::HTTP_BAD_REQUEST
                        ]], IlluminateResponse::HTTP_BAD_REQUEST, $headers = []
            );
        }

        $credentials = $this->getCredentials($request);

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                            'error' => [
                                'message' => 'Invalid credentials',
                                'status_code' => IlluminateResponse::HTTP_UNAUTHORIZED
                            ]], IlluminateResponse::HTTP_UNAUTHORIZED, $headers = []
                );
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([
                        'error' => [
                            'message' => 'could_not_create_token',
                            'status_code' => IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR
                        ]], IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR, $headers = []
            );
        }
        // all good so return the token
        //compact('token')
        return response()->json(['name' => 'suhail', 'token' => $token, 'error' => []]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request) {
        return $request->only('email', 'password');
    }

    //
}
