<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;

use App\Models\User; 
use App\Models\Unit; 
use App\Models\Company; 
use App\Models\CompanyUser; 

class AuthController extends Controller
{


    public function unauthorized()
    {
        //return response()->json(['error' => 'Unauthorized'], 401);
        return response()->json(['error' => 'Unauthorized'], 401);
    }

     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(Request $request) {
        $resp = ['error' => '']; 
        $validator = Validator::make($request->all(), [
            'name' =>'required|min:2',
            'email' =>'required|email|unique:users,email',
            'cpf' => 'required:digits:11|unique:users,cpf',
            'password' => 'required',
            //'phone' => 'sometimes|required|min:8|regex:/(01)[0-9]{9}/',
            'phone' => 'sometimes|required|min:8',
            'company_id' => 'required|integer|exists:companies,id',
            'password_confirm' => 'required|same:password', 
        ]);



        if($validator->fails()){
            $resp['error'] = $validator->errors();
            return $resp;
        }


        $name = $request->input('name'); 
        $email = $request->input('email'); 
        $cpf = $request->input('cpf');
        $password = $request->input('password'); 
        $hash = password_hash($password,PASSWORD_DEFAULT); 
        if($request->input('phone')){
            $phone = $request->input('name'); 
        }
        $company_id = $request->input('company_id'); 

        $user = new User; 
        $user->name = $name; 
        $user->email = $email; 
        $user->cpf = $cpf; 
        $user->password = $hash;
        if($phone){
            $user->phone = $phone; 
        }    
        $user->save();  
        
        $companyusers = CompanyUser::firstOrCreate([
            'company_id' => $company_id, 
            'user_id' => $user->id,
        ]); 

       
        $token = auth()->attempt([
            'email' => $email, 
            'password' => $password, 
        ]);
        if(!$token){
            $resp['error'] = 'Ocorreu um error'; 
             return $resp; 
        }
        $user = auth()->user(); 
        $units = Unit::select(['id','name'])
                       ->where('company_id',$company_id)
                       ->where('owner_id',$user->id); 
        $resp['user'] = $user;
        $resp['user'] ['units'] = $units; 
        $resp['token'] = $token; 
        return $resp; 
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
       $resp = ['error' => ''];              
       $validator = Validator::make($request->all(), [
            'company' => 'required|integer|exists:companies,id', 
            'email' =>'required|email|exists:users,email',
            'password' => 'required',                
        ]);
    
        if($validator->fails()){
            $resp['error'] = $validator->errors();
            return $resp;
        } 

        $company_id = $request->input('company');
        $company = Company::find($company_id);  
        if (!$company){
            $resp['erro'] = "Falha ao recuperar empresa";
            return $resp; 
        }

       $credentials = request(['email', 'password']);     
       if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        
        $companyUser = CompanyUser::where('company_id',$company_id)
                                  ->where('user_id',$user->id)
                                  ->first();
         
        if (!$companyUser || !isset($companyUser->company_id)){
            $resp['erro'] = "Usuário não esta relacionado a empresa"; 
            return $resp; 
        }
        $user->company_id = $company_id; 
        $user->save(); //Atualiza o usuário com o id da companhia conectada  

        $units = $user->units()
                      ->where('owner_id',$user->id)
                      ->where('company_id',$company->id)
                      ->get();
        //$resp['user'] =  response()->json(auth()->user());   
        $resp['user'] = $user; 
        //$resp['token'] = $this->respondWithToken($token);
        $resp['user']['token'] = $token;
        $resp['user']['units'] = $units; 
        //$resp['user']['units'] = Unit::where('owner_id',Auth()->user()->id)->get();
        return $resp; 
    }


    public function validateToken() {
        $resp = ['error' => ''];
        $user = auth()->user();
        $units = $user->units()
                      ->where('owner_id',$user->id)
                      ->where('company_id',$user->company_id);
        $resp['user'] = $user; 
        $resp['user']['units'] = $units;

        return $resp; 
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
