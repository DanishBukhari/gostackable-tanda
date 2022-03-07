<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Jobs\EmailOtpJob;
use App\Jobs\SmsOtpJob;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Services\AuthService;
use App\Services\MonoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RegisterController extends Controller
{
    private $authService;
    private $monoService;

    public function __construct()
    {
        $this->authService = resolve(AuthService::class);
        $this->monoService = resolve(MonoService::class);
    }

    /**
     * Register new user and send otp to them
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUser(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->authService->registerUser($request->validated());
        if (empty($user)) {
            abort(400, "User creation failed, please try again");
        }
        // Send OTP verification to user
        $this->dispatchOtpToUser($user);
        //Mono Changes
        $payload = [
            "first_name"=> $user->first_name,
            "last_name"=> $user->last_name,
            "address"=> [
                "address_line1"=> "12 banana island",
                "lga"=> "lag",
                "city"=> "lagos",
                "state"=> "lagos"
            ],
            "entity"=> "INDIVIDUAL",
            "bvn"=> $this->generateBvn($user->id),
            "identity"=> [
                "type"=> "NIN",
                "number"=> $this->generateBvn($user->id),
                "url"=> "https://eu-west-2.amazonaws.com/identity-FunVlKB_OKMHuQFLCT5ek-test_1.png"
            ]
        ];
        $account_id = $this->monoService->CreateAccountHolder($payload)["data"]["id"];

        $virtual_account_payload = [
            "account_holder"=>$account_id
        ];

        $virtual_account_id = $this->monoService->CreateVirtualAccount($virtual_account_payload)["data"]["id"];

        $data = $this->monoService->GetVirtualAccount($virtual_account_id)["data"];
        $data["user_id"] = $user->id;

        VirtualAccount::create($data);

        return $this->createdResponse("User created successfully");

    }
    private function generateBvn($id){
        $bvn = $id."";
        for ($i=strlen($id);$i<11;$i++){
            $bvn .= "0";
        }
        return $bvn;
    }

    /**
     * Dispatch both SMS and Email OTP to User
     *
     * @param User $user
     */
    private function dispatchOtpToUser(User $user)
    {
        $numberOfDigits = 5;
        $activationCode = substr(str_shuffle("0123456789"), 0, $numberOfDigits);
        Cache::put($user->id . "_activation_code", $activationCode, now()->addMinutes(15));
        SmsOtpJob::dispatch($user, $activationCode);
        EmailOtpJob::dispatch($user, $activationCode);
    }
}
