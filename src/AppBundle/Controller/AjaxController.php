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
     * @Route("/cart/add", name="cart-add")
     */
    public function itemAddAction(Request $request)
    {
        if ($request->isXmlHttpRequest()){
            $id = $request->request->get('id');
            $quantity = $request->request->get('quantity');

            $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
            if ($product->getQuantity() < $quantity) {
                return new JsonResponse(array(
                    'status' => 'fail',
                    'message' => 'There are not enough quantity in the stock'
                ),409);
            }

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

            $this->calculateTotal($request, $products, $cart);

            return new JsonResponse(array(
                'status' => 'ok',
                'message' => 'success',
                'cartLength' => count($cart),
                'modal' => $this->renderView(':partial:_modalCart.html.twig', array( 'products' => $products))
            ));

        }else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/cart/remove", name="cart-remove")
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
                $request->getSession()->remove('total');
            } else {
                unset($cart[$id]);
                $request->getSession()->set('cart', $cart);
                $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('id' => array_keys($cart)));
                $this->calculateTotal($request,$products, $cart);
            }

            return new JsonResponse(array(
                'status' => 'ok',
                'message' => 'success',
                'cartLength' => count($cart),
                'modal' => $this->renderView(':partial:_modalCart.html.twig', array( 'products' => $products))
            ));


        }else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/cart/update", name="cart-update")
     */
    public function updateCartAction(Request $request)
    {
        if ($request->isXmlHttpRequest()){
            $cart = json_decode($request->request->get('cart'), true);
            $request->getSession()->set('cart', $cart);

            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('id' => array_keys($cart)));

            $this->calculateTotal($request, $products, $cart);

            return new JsonResponse(array(
                'status' => 'ok',
                'message' => 'success',
            ));
        }else {
                return $this->redirectToRoute('homepage');
        }
    }

    private function calculateTotal(Request $request, $products, $cart)
    {
        $total = 0;
        foreach ($products as $product){
            $id = $product->getId();
            $price = $product->getPrice();
            $quantity = $cart[$id];
            $total += $quantity*$price;
        }
        $total = $request->getSession()->set('total', $total);
        return $total;
    }
}