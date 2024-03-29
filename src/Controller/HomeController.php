<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use App\Repository\PropertyRepository;


class HomeController extends AbstractController
{
	 /**
	  * @var Environment
	  */
	private $twig;

	public function __construct(Environment $twig)
	{
		$this->twig = $twig;
	}

	/**
	 * @Route("/", name="home")
	 * @param PropertyRepository $repository
	 * @return Response
	 */
	public function index(PropertyRepository $repository):Response
	{
		$properties = $repository->findLatest();
		return $this->render('pages/home.html.twig', [
			'properties' => $properties
		]);
	}
}