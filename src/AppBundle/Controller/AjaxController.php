<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 05.
 * Time: 18:10
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/cart/add")
     */
    public function itemAddAction(Request $request)
    {
        if ($request->isXmlHttpRequest()){
            $id = $request->request->get('id');
            $quantity = $request->request->get('quantity');

            $cart = $request->getSession()->get('cart', array());

            if ( !is_array($cart) ){
                $cart = array();
            }

            if ( array_key_exists($id, $cart) ) {
                $cart[$id] += $quantity;
            }else {
                $cart[$id] = $quantity;
            }

            $request->getSession()->set('cart', $cart);

            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('id' => array_keys($cart)));


            return new JsonResponse(array(
                'status' => 'OK',
                'cartLength' => count($cart),
                'modal' => $this->renderView(':default:_modalCart.html.twig', array( 'products' => $products))
            ));

        }else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/cart/remove")
     */
    public function itemRemoveAction(Request $request)
    {
        if ($request->isXmlHttpRequest()){
            $id = $request->request->get('id');
            $cart = $request->getSession()->get('cart');

            if (count($cart) == 1){
                $request->getSession()->set('cart', null);
                $products = array();
                $cart = array();
            } else {
                unset($cart[$id]);
                $request->getSession()->set('cart', $cart);
                $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('id' => array_keys($cart)));
            }

            return new JsonResponse(array(
                'status' => 'OK',
                'cartLength' => count($cart),
                'modal' => $this->renderView(':default:_modalCart.html.twig', array( 'products' => $products))
            ));


        }else {
            return $this->redirectToRoute('homepage');
        }
    }
}