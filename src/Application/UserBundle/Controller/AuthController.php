<?php
namespace Application\UserBundle\Controller;

use Application\UserBundle\Entity\User;
use Application\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AuthController extends Controller 
{
    /**
     * Login Validation and Routing
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function loginAction(Request $request) 
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $exception = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $exception = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        if ($exception && method_exists($exception, 'getMessage')) {
            $message = $exception->getMessage();
        }

        return $this->render('UserBundle:Auth:login.html.twig', array(
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $exception             
        ));
    }

    /**
     * Validate Token and Activate User
     *  
     */
    public function activateUserAction($token) 
    {
        $user = $this->baseRepository()->findOneBy(array('token' => $token));
        
        if ($user) {
                $user->setStatus($user::STATUS_ACTIVE);
                
                $this->baseRepository()->save($user);

                return $this->redirect($this->generateUrl('login'));
            }
       else {
            $message = 'Not an existing user';
        }

        return $this->render('UserBundle:User:thanks.html.twig', array('user' => $user, 'message' => $message, 'resetTokenChoice' => $resetTokenChoice));
    }


}
