<?php

namespace AppBundle\Controller;

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
    public function indexAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('isFeatured' => true));

        return array(
            'products' => $products
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
        $category = $em->getRepository('AppBundle:Category')->findBy(array('slug' => $slug));
        if (!$category) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $products = $em->getRepository('AppBundle:Product')->findBy(array('category' => $category));

        return array(
            'products' => $products
        );
    }

    /**
     * @return array
     * @Template(":default:_categories.html.twig")
     */
    public function _categoriesAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        return array(
            'categories' => $categories
        );
    }
}
