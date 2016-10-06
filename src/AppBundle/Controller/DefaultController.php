<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template(":default:index.html.twig")
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
     * @Template(":default:index.html.twig")
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
     * @Template(":default:product.html.twig")
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
     * @Template(":default:_categories.html.twig")
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
     * @Template(":default:_modalCart.html.twig")
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
