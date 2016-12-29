<?php
namespace Dolf\SSO\example\SSOBroker;

use Dolf\SSO\SSOLaravel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;


class MyAuthController extends Controller
{
    /**
     * @var SSOLaravel
     */
    public $SSOLaravel;

    /**
     * MyAuthController constructor.
     * @param SSOLaravel $SSOLaravel
     */
    public function __construct(SSOLaravel $SSOLaravel)
    {
        $this->SSOLaravel = $SSOLaravel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ssoIndex(Request $request)
    {
        $ssoAction  = $request->get('action');
        $returnUrl  = $request->get('returnUrl');
        $signature  = $request->get('signature');
        $multipass  = $request->get('multipass');

        if ($this->SSOLaravel->checkSignature($signature, $multipass) === true) {
            if ($ssoAction == 'login') {
                $this->SSOLaravel->ssoLogin($multipass, $returnUrl);
            }
            if ($ssoAction == 'logout') {
                $this->SSOLaravel->ssoLogout($returnUrl);
            }
            return redirect()->away($returnUrl);
        }

        return redirect()->back();
    }

    /**
     * @return string
     */
    public function ssoServerAction($action)
    {
        $destination = URL::previous();
        if (strpos($destination, "sso") !== false) {
            $destination = env('BROKER_URL');
        }
        $returnUrl  = ['destination' => $destination];
        $action     = ['action' => $action];

        $url = env('SERVER_URL') . "/sso" . "?" . http_build_query($returnUrl + $action);

        return redirect()->away($url);
    }
}