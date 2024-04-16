<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $roles = [
            'name' => 'required',
            'email' => 'email|unique:users',
            'password' => [
                'required','string',
                Password::min(6)->letters()->numbers()->mixedCase()->symbols()
            ],
            'c_password' => 'required|same:password',
            'access_level_id'=>'required'
        ];

        $validator = Validator::make($request->all(),$roles);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();

        $user = User::create($data);
        $user['token'] = $user->createToken('MyApp')->plainTextToken;

        return $this->showOne($user,"Usuário registado com sucesso");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login(Request $request){

        $roles = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $attributes = [
            'email' => '"email"',
            'password' => '"senha"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatório.'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            $data = [
                "errors" => $validator->errors(),
                "message" => "Preencha os campos"
            ];
            return $this->errorResponse($data,422);
        }

        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            //$success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['token'] = $request->user()->createToken('MyApp')->plainTextToken;
            $success['user'] = $user;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login successfully'
            ];

            return response()->json($response,200);
            }else{
                $response = [
                    'success' => false,
                    'message' => 'Sem autorização, verrifica as tuas credencias!'
                ];
                return $this->errorResponse($response,401);
            }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'success' => true,
            'message' => 'Logout success.'
        ],200);
    }
}
