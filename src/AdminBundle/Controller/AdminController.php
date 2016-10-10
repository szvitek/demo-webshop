<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 10.
 * Time: 0:46
 */

namespace AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @return string
     * @Route("admin/index", name="admin-index")
     * @Template("AdminBundle:admin:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newOrders = $em->getRepository('AppBundle:OrderSummary')->findBy(array('isCompleted' => false), array('createdAt' => 'DESC'));
        $users = $em->getRepository('UserBundle:User')->findAll();
        $lowProducts = $em->getRepository('AppBundle:Product')->findLowProducts();
        $zeroProducts = $em->getRepository('AppBundle:Product')->findBy(array('quantity' => 0));
        $products = $em->getRepository('AppBundle:Product')->findAll();


        return array(
            'newOrders' => $newOrders,
            'users' => $users,
            'lowProducts' => $lowProducts,
            'zeroProducts' => $zeroProducts,
            'products' => $products,
        );
    }
}