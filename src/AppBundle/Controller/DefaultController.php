<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template(":default:index.html.twig")
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('isFeatured' => true));

        return array(
            'products' => $products,
        );
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
}
