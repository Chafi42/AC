<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Cars;
use App\Entity\Picture;
use App\Form\CarsType;
use App\Repository\CarsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class CarsController extends AbstractController
{
    #[Route('/achat', name: 'app_cars_achat', methods: ['GET'])]
    public function index(CarsRepository $carsRepository, Request $request): Response
    {
        $searchForm = $this->createFormBuilder()
            ->add('search', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Search for cars...'
                ]
            ])
            ->getForm();
        
        $searchForm->handleRequest($request);
        
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData = $searchForm->getData();
            $searchTerm = $searchData['search'];
            
            $cars = $carsRepository->findBySearchTerm($searchTerm);
        } else {
            $cars = $carsRepository->findAll();
        }
        
        return $this->render('cars/achat.html.twig', [
            'cars' => $cars,
            'searchForm' => $searchForm->createView()
        ]);
    }

    #[Route('/vente', name: 'app_cars_vente', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $cars = new Cars();
        $form = $this->createForm(CarsType::class, $cars);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     
            $picture = $form->get('picture')->getData();

        
            if($picture) {
                    

                    $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFileName = $slugger->slug($originalFilename);
                    $newFilename = $safeFileName.'-'.uniqid().'.'.$picture->guessExtension();
                    
                    try {
                        $picture->move($this->getParameter('pictures_directory'),$newFilename);
                        $pictureEntity = new Picture();
                        $pictureEntity->setName($newFilename);
                        $pictureEntity->setCar($cars);
                        $entityManager->persist($pictureEntity);
                        $cars->addPicture($pictureEntity);
                    } catch (FileException $e) {
                        dd($e->getMessage());
                    }
                }
                
            $cars->setUser($this->getUser());
            $cars->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($cars);
            $entityManager->flush();
            return $this->redirectToRoute('app_cars_achat', [], Response::HTTP_SEE_OTHER);
        }

        

        return $this->render('cars/new.html.twig', [
            'cars' => $cars,
            'form' => $form,
        ]);
    }

    #[Route('/cars/{id}', name: 'car_show', requirements: ['id' => '\d+'])]
    public function show(Cars $car)
    {
        return $this->render('cars/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cars_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cars_achat', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cars/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cars_delete', methods: ['POST'])]
    public function delete(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cars_achat', [], Response::HTTP_SEE_OTHER);
    }
}
