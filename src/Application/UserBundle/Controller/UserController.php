<?php

namespace Classifed\UserBundle\Controller;

use Classifed\UserBundle\Entity\User;
use Classifed\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserController extends Controller 
{
    /**
     * Registration for new user
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function registerAction(Request $request) 
    {
        $form = $this->createForm(new UserType());

        if ($request->getMethod() == 'POST') {
            
            $form->handleRequest($request);

            if ($form->isValid()) {
                $user = $form->getData();
                
                $factory = $this->get('security.encoder_factory');
                
                $encoder = $factory->getEncoder($user);
                
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                
                $user->setPassword($password);

                $token = $this->getToken(array($user->email, $user->created, rand()));
                
                $user->setToken($token);
                
                $saveUser = $this->baseRepository()->save($user);

                return $this->redirect($this->generateUrl('thankyou', array('email' => $user->getEmail())));
            }
        }

        return $this->render('UserBundle:User:save.html.twig', array('form' => $form->createView(), 'action' => 'Registreren'));
    }
    /**
     * Request for Password Reset
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function recoverPasswordAction(Request $request) 
    {
        $session = $request->getSession();
        
        if ($this->getUser()) {
          // return to login
        }

        $user = new User();
        
        $form = $this->createFormBuilder($user)
                ->add('email', 'email', array('attr' => array('class' => 'form-control')))
                ->add('submit', 'submit', array('attr' => array('class' => 'btn-primary gap-height')))
                ->getForm();

        if ($request->getMethod() == 'POST') {
            $email = $form->get('email')->getData();
            
            $user = $this->baseRepository()->findOneBy(array('email' => $email));
            
            if ($user) {
                $token = $this->getToken(array($user->email, time(), rand()));
                
                $user->setToken($token);
                
                $user->setStatus($user::STATUS_INACTIVE);
                
                $this->baseRepository()->save($user);

                $this->_sendMails($options, $user);

                $message = 'Please visit your email: ' . $user->getEmail() . ' and follow the instruction';
                
            } else {
                $message = 'The requested email is not found';
            }

            return $this->render('UserBundle:User:thankyou.html.twig', array('user' => $user, 'message' => $message));
        }
        return $this->render('UserBundle:User:save.html.twig', array('form' => $form->createView(), 'action' => 'Forgot Password'));
    }

    /**
     * send emails (set the twig view as required)
     * @param type $options
     * @param type $user
     */
    public function _sendMails($options, $user) {

        if (!isset($options['from'])) {
            $options['from'] = 'sudipbanerjee03@gmail.com';
        }

        if (!isset($options['to'])) {
            $options['to'] = $user->getEmail();
        }

        $mail = \Swift_Message::newInstance()
                ->setSubject($options['subject'])
                ->setFrom($options['from'])
                ->setTo($options['to'])
                ->setBody($this->renderView('UserBundle:User:email.html.twig', array('options' => $options, 'user' => $user)), 'text/html');
        $this->get('mailer')->send($mail);
    }

    /**
     * User Entity Repository shortcut
     * @return type
     */
    public function baseRepository() {
        $userRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('Application\UserBundle\Entity\User');
        return $userRepository;
    }

        
   /**
    * Get Token
    * @param type $option
    * @return type
    */ 
    public function getToken($option = array())
    { 
        $stringTokenOption = implode(':', $option);// default params email, created, random number
        
        return base64_encode($stringTokenOption);
    }
}
