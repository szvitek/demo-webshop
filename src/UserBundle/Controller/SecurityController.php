<?php
/**
 * Created by PhpStorm.
 * User: szvitek
 * Date: 07/08/16
 * Time: 01:50
 */

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Template("UserBundle:security:login.html.twig")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        );
    }

    /**
     * @throws \Exception
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception('this should never be reacher!');
    }
}