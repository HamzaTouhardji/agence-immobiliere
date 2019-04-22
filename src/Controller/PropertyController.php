<?php

namespace App\Controller;

use App\Entity\Property;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;


class PropertyController extends AbstractController
{

	/**
	 * @var PropertyRepository
	 */

	/**
	 * @var ObjectManager
	 */
	private $repository;
		
	public function __construct(PropertyRepository $repository, ObjectManager $em)
	{
		$this->repository = $repository;
		$this->em = $em;
	}

	
	/**
	 * @Route("/biens", name="property.index")
	 * @return Response
	 */
	public function index():Response
	{

		/*$property = new Property();
		$property
		->setTitle('Mon premier bien')
				->setRooms(4)
				->setPrice(200000)
				->setBedrooms(3)
				->setDescription('Une petite description')
				->setSurface(60)
				->setFloor(4)
				->setHeat(1)
				->setCity('Lyon')
				->setAddress('23 rue louie loucheur')
				->setPostalCode(69009);

		$em = $this->getDoctrine()->getManager();
		$em->persist($property);
		$em->flush();*/

		return $this->render('property/index.html.twig', [
			'current_menu' => 'properties'
		]);
	}

	/**
	 * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
	 * param Property $property
	 * @return Response
	 */
	public function show(Property $property, string $slug):Response
	{
		if ($property->getSlug() !== $slug) {
			return $this->redirectToRoute('property.show', [
				'id' => $property->getId(),
				'slug' => $property->getSlug()
			], 301);
		}
		return $this->render('property/show.html.twig', [
			'property' =>$property,
			'current_menu' => 'properties'
		]);
	}
}

