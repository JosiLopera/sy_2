<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction(Request $request)
    {
    	$image = new Image();
    	$uploadForm = $this->createForm(new ImageType(), $image);

    	$uploadForm->handleRequest($request);

    	if ($uploadForm->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($image);
    		$em->flush();
    	}

    	$params = array(
    			"uploadForm" => $uploadForm->createView()
    		);
        return $this->render('default/upload.html.twig', $params);
    }
}
