<?php

namespace GHOME\CoreBundle\Controller;

use GHOME\CoreBundle\Entity\User;
use GHOME\CoreBundle\Form\Type\UserType;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller handling security integrity of the application.
 */
class SecurityController extends Controller
{
    /**
     * Displays a login form. Form processing is handled by the SecurityBundle.
     *
     * @Route("/login")
     * @Template
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // Get the login error if there is one.
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else
        {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
        );
    }
    
    /**
     * Displays a list of all users registered in the system.
     *
     * @param Request $request
     *
     * @Route("/config/users")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $users = $em->getRepository('GHOMECoreBundle:User')->findAll();
        
        return array('users' => $users);
    }
    
    /**
     * Displays a form to add a new user.
     *
     * @param Request $request
     *
     * @Route("/config/users/add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $user = new User();
		$form = $this->createForm(new UserType(), $user);
		
		if ($request->getMethod() === 'POST')
		{
            $form->bindRequest($request);
            
            //Encode password
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);

            if ($form->isValid())
            {
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('ghome_core_security_index'));
            }
        }
        
        return array('form' => $form->createView());
    }
    
    /**
     * Displays a form to edit an existing user.
     *
     * @param Request $request
     *
     * @Route("/config/users/edit/{id}")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('GHOMECoreBundle:User')->find($id);
        if (!$user)
        {
            throw new NotFoundHttpException();
        }
        
		$form = $this->createForm(new UserType(), $user);
		
		if ($request->getMethod() === 'POST')
		{
            $form->bindRequest($request);
            
            //Encode password.
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);

            if ($form->isValid())
            {
                $em->flush();

                return $this->redirect($this->generateUrl('ghome_core_security_index'));
            }
        }
        
        return array('form' => $form->createView());
    }
    
    /**
     * Displays a form to delete an existing user.
     *
     * @param Request $request
     *
     * @Route("/config/users/delete/{id}")
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('GHOMECoreBundle:User')->find($id);
        if (!$user)
        {
            throw new NotFoundHttpException();
        }
		
		if ($request->getMethod() === 'POST')
		{
		    if ($request->request->has('confirm'))
		    {
		        $em->remove($user);
		        $em->flush();                
            }
            
            return $this->redirect($this->generateUrl('ghome_core_security_index'));
        }
        
        return array('user' => $user);
    }
}
