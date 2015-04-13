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

    		$image->getTempFile()->move(
	            $image->getPathtoUploads()."/originals", 
	            $image->getFilename()
	        );

	        $img = new \abeautifulsite\SimpleImage(
	            $image->getPathtoUploads()."/originals/".$image->getFilename()
	        );

	        $img->best_fit(600,600)->save(
	            $image->getPathtoUploads()."/mediums/".$image->getFilename()
	        );

	        $img->thumbnail(60,60)->save(
	            $image->getPathtoUploads()."/thumbnails/".$image->getFilename()
	        );
    	}



    	$params = array(
    			"uploadForm" => $uploadForm->createView()
    		);
        return $this->render('default/upload.html.twig', $params);
    }
}
