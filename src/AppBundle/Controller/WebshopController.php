<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\OrderSelection;
use AppBundle\Entity\OrderSummary;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\UserInfoType;

class WebshopController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template(":webshop:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $keyword = $request->query->get('search');

        $returnArray = array();

        if ($keyword) {
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->search($keyword);

            if (!$products) {
                $returnArray['keyword'] = $keyword;
            }


        } else {
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('isFeatured' => true));
        }
        $returnArray['products'] = $products;

        return $returnArray;
    }

    /**
     * @param $slug
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/category/{slug}", name="category_list")
     * @Template(":webshop:index.html.twig")
     */
    public function listCategoryAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Category $category */
        $category = $em->getRepository('AppBundle:Category')->findOneBy(array('slug' => $slug));
        if (!$category) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $products = $em->getRepository('AppBundle:Product')->findBy(array('category' => $category));

        return array(
            'products' => $products,
            'active' => $category->getId()
        );
    }

    /**
     * @param $id
     * @return array
     * @Route("/product/{id}", name="product_show")
     * @Template(":webshop:product.html.twig")
     */
    public function productDetailsAction($id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (!$product){
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        return array(
            'product' => $product,
            'active' => $product->getCategory()->getId()
        );
    }

    /**
     * @return array
     * @Route("/checkout", name="checkout")
     * @Template(":webshop:checkout.html.twig")
     */
    public function checkoutAction(Request $request)
    {
        $cart = $request->getSession()->get('cart');

        if (count($cart) == 0){
            $products = array();
        }else {
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('id' => array_keys($cart)));
        }

        $form = $this->createForm(UserInfoType::class);
        $form->handleRequest($request);

        $cart = $request->getSession()->get('cart');

        if ($form->isSubmitted() && $form->isValid() && count($cart) > 0) {
            $em = $this->getDoctrine()->getManager();

            $summary = new OrderSummary();
            $summary->setFullName($form->get('fullName')->getData());
            $summary->setAddress($form->get('address')->getData());
            $summary->setCity($form->get('city')->getData());
            $summary->setCounty($form->get('county')->getData());
            $summary->setZip($form->get('zip')->getData());
            $summary->setCountry($form->get('county')->getData());
            $summary->setUser($this->getUser());

            $price = 0;
            foreach ( $cart as $key => $value ){
                $product = $em->getRepository('AppBundle:Product')->find($key);

                $selection = new OrderSelection();
                $selection->setPrice($product->getPrice());
                $selection->setQuantity($value);
                $price += $product->getPrice() * $value;
                $selection->setProduct($product);
                $selection->setSummary($summary);
                $em->persist($selection);

                $product->setQuantity($product->getQuantity()-$value);
                $em->persist($product);
            }
            $summary->setPrice($price);
            $em->persist($summary);

            $em->flush();

            $request->getSession()->remove('cart');

            return $this->redirectToRoute('verify');
        }

        return array(
            'products' => $products,
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("cart/reset", name="cart-reset")
     */
    public function resetCartAction(Request $request)
    {
        $request->getSession()->remove('cart');
        return $this->redirectToRoute('checkout');
    }

    /**
     * @return array
     * @Route("/verify", name="verify")
     * @Template(":webshop:verify.html.twig")
     */
    public function verifyMessageAction()
    {
        return array();
    }

    /**
     * @return array
     * @Template(":partial:_categories.html.twig")
     */
    public function _categoriesAction($active =0)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        return array(
            'categories' => $categories,
            'active' => $active
        );
    }

    /**
     * @param Request $request
     * @return array
     * @Template(":partial:_modalCart.html.twig")
     */
    public function _modalBodyAction(Request $request)
    {
        $cart = $request->getSession()->get('cart');

        if (count($cart) == 0){
            $products = array();
        }else {
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('id' => array_keys($cart)));
        }

        return array(
            'products' => $products
        );
    }
}
