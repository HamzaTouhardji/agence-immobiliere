<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PropertyRepository;
use App\Form\PropertyType;
use App\Entity\Property;


class AdminPropertyController extends AbstractController
{

	/**
	 * @var PropertyRepository
	 */
	private $repository;

	/**
	 * @var ObjectManager
	 */
	private $em;

	public function __construct(PropertyRepository $repository, ObjectManager $em)
	{
		$this->repository = $repository;
		$this->em = $em;
	}

	/**
	 * @Route("/admin", name="admin.property.index")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index():Response
	{
		$properties = $this->repository->findAll();
		return $this->render('admin/property/index.html.twig', compact('properties'));
	}

	/**
	 * @Route("/admin/property/create", name="admin.property.new")
	 */
	public function new(Request $request)
	{
		$property = new Property();
		$form = $this->createForm(PropertyType::class, $property);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->persist($property);
			$this->em->flush();
			$this->addFlash('success', 'Le bien a été créé avec succès');
			return $this->redirectToRoute('admin.property.index');
		}

		return $this->render('admin/property/new.html.twig', [
			'property' => $property,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
	 * param Property $property 
	 * param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function edit(Property $property, Request $request)
	{
		$form = $this->createForm(PropertyType::class, $property);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->flush();
			$this->addFlash('success', 'Le bien a été modifé avec succès');
			return $this->redirectToRoute('admin.property.index');
		}

		return $this->render('admin/property/edit.html.twig', [
			'property' => $property,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
	 * param Property $property
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function delete(Property $property, Request $request)
	{
		if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
			$this->em->remove($property);
			$this->em->flush();
			$this->addFlash('success', 'Le bien a été supprimé avec succès');
		}
		return $this->redirectToRoute('admin.property.index');
	}
}