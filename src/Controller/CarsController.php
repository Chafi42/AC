<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Cars;
use App\Entity\Picture;
use App\Form\CarsType;
use App\Repository\CarsRepository;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CarsController extends AbstractController
{
    #[Route('/achat', name: 'app_cars_achat', methods: ['GET', 'POST'])]
    public function index(CarsRepository $carsRepository, Request $request, PictureRepository $pictureRepository): Response
    {
        if ($request->isMethod('POST')) {


            $model = $request->request->all('model');
            $type = $request->request->all('type');
            $brand = $request->request->all('brand');
            $mileage = $request->request->all('mileage');
            $price = $request->request->all('price');

            $cars = $carsRepository->findBy([
                'model' => $model,
                'type' => $type,
                'brand' => $brand,
                'mileage' => $mileage,
                'price' => $price,
            ]);



            if (!$cars) {
                $cars = $carsRepository->findAll();
            }
        } else {
            $cars = $carsRepository->findAll();
        }
        $pictures = $pictureRepository->findAll();
        // dd($pictures);
        return $this->render('cars/achat.html.twig', [
            'cars' => $cars,
            'pictures' => $pictures,
        ]);
    }

    #[Route('/vente', name: 'app_cars_vente', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger, PictureRepository $picture, EntityManagerInterface $entityManager): Response
    {
        $cars = new Cars();
        $form = $this->createForm(CarsType::class, $cars);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFilename);
                $newFilename = $safeFileName . '-' . uniqid() . '.' . $picture->guessExtension();

                try {
                    $picture->move($this->getParameter('pictures_directory'), $newFilename);
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

        return $this->render('cars/vente.html.twig', [
            'cars' => $cars,
            'form' => $form,
        ]);
    }

    #[Route('/car/{id}', name: 'app_cars_show', methods: ['GET'])]
    public function show(Cars $cars): Response
    {
        $users = UserRepository::class;
        return $this->render('cars/show.html.twig', [
            'cars' => $cars,
            'users' => $users,
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

    #[Route('/car/delete/{id}', name: 'app_cars_delete', methods: ['POST'])]
    public function delete(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cars_achat', [], Response::HTTP_SEE_OTHER);
    }
}
