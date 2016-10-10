<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 08.
 * Time: 20:17
 */

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\UserInfoType;

class UserController extends Controller
{
    /**
     * @return array
     * @Route("/profile", name="profile-show")
     * @Template("UserBundle:profile:show.html.twig")
     */
    public function profileShowAction()
    {
        $orders = $this->getDoctrine()->getRepository('AppBundle:OrderSummary')->findOrdersByUser($this->getUser());

        return array(
            'orders' => $orders
        );
    }

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/profile/edit", name="profile-edit")
     * @Template("UserBundle:profile:edit.html.twig")
     */
    public function profileEditAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserInfoType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $user->setFullName($form->get('fullName')->getData());
            $user->setAddress($form->get('address')->getData());
            $user->setCity($form->get('city')->getData());
            $user->setCounty($form->get('county')->getData());
            $user->setZip($form->get('zip')->getData());
            $user->setCountry($form->get('country')->getData());

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('profile-show');
        }

        return array(
            'form' => $form->createView()
        );
    }
}