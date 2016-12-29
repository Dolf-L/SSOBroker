<?php
namespace Dolf\SSO;

use Dolf\SSO\Managers\UserManagerInterface;
use Dolf\SSO\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Logging\Log;


/**
 * Class SSOLaravel
 * @package App\Extensions
 */
class SSOLaravel
{
    /**
     * @var
     */
    protected $broker;

    /**
     * @var
     */
    protected $userManager;

    /**
     * @var
     */
    protected $userRepo;

    /**
     * @var
     */
    protected $validator;

    /**
     * @var
     */
    protected $log;


    /**
     * SSOLaravel constructor.
     *
     * @param SSOBroker                 $broker
     * @param UserManagerInterface      $userManager
     * @param UserRepositoryInterface   $userRepo
     * @param SSOValidator              $validator
     * @param Log                       $log
     */
    public function __construct(
        SSOBroker                $broker,
        UserManagerInterface     $userManager,
        UserRepositoryInterface  $userRepo,
        SSOValidator             $validator,
        Log                      $log
    )
    {
        $this->broker       = $broker;
        $this->userManager  = $userManager;
        $this->userRepo     = $userRepo;
        $this->validator    = $validator;
        $this->log          = $log;
    }

    /**
     * Decrypt request from server, login user, redirect back to server
     */
    public function ssoLogin($multipass, $returnUrl)
    {
         if  (empty($decryptedMulti = $this->broker->decryptData($multipass))) {
             $this->log->info('Data can not be decrypted');
         }
        $validation     = $this->validator->validateData($decryptedMulti);

        if ($validation->fails()) {
            $this->log->info('Data did not pass the validation');
        }

        if ($user = $this->userExist($decryptedMulti)) {
            $user =  $this->userManager->updateUser($user, $decryptedMulti);
        } else {
            $user = $this->userManager->createUser($decryptedMulti);
        }

        Auth::login($user);

        return redirect()->away($returnUrl);
    }

    /**
     * @param $returnUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ssoLogout($returnUrl)
    {
        Auth::logout();

        return redirect()->away($returnUrl);
    }

    /**
     * @param $signature
     * @param $multipass
     * @return bool
     */
    public function checkSignature($signature, $multipass)
    {
        return $this->broker->checkSignature($signature, $multipass);
    }


    /**
     * Check by email if user exist+
     *
     * @param $passport
     * @return bool
     */
    protected function userExist($passport)
    {
        $customerEmail = $passport->customer_email;

        $user = $this->userRepo->getUserByEmail($customerEmail) ?: false;

        return $user;
    }
}