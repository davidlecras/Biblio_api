<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiGenreController extends AbstractController
{

    /**
     * @Route("/api/genre", name="api_genre", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository, SerializerInterface $serializerInterface): Response
    {
        $genre = $genreRepository->findAll();
        $result = $serializerInterface->serialize($genre, 'json', [
            'groups' => ['listeGenresFull']
        ]);
        return new JsonResponse($result, 200, [], true);
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_show", methods={"GET"})
     */
    public function show(Genre $genre, SerializerInterface $serializerInterface): Response
    {
        $result = $serializerInterface->serialize($genre, 'json', [
            'groups' => ['listeGenresSimple']
        ]);
        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/genre", name="api_genre_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManagerInterface, SerializerInterface $serializerInterface, ValidatorInterface $validatorInterface): Response
    {
        $data = $request->getContent();
        //1ere methode:
        // $genre = new Genre();
        // $result = $serializerInterface->deserialize($data, Genre::class, 'json', ['object_to_populate'=>$genre]);

        //2eme methode:
        $genre = $serializerInterface->deserialize($data, Genre::class, 'json');

        // Validation des donnees=> gestion des erreurs:
        $errors = $validatorInterface->validate($genre);
        if (count($errors)) {
            $errors_json = $serializerInterface->serialize($errors, 'json');
            return new JsonResponse($errors_json, Response::HTTP_BAD_REQUEST, [], true);
        }

        // Enregistrement en BDD:
        $entityManagerInterface->persist($genre);
        $entityManagerInterface->flush();


        // 1er exemple de retour de reponse Json:
        // return new JsonResponse(null, Response::HTTP_CREATED, [
        //     "location" => "api/genre" . $genre->getId()
        // ], true);


        // 2eme methode:
        return new JsonResponse(
            "genre créé",
            Response::HTTP_CREATED,
            [
                "location" => $this->generateUrl('api_genre_show', ["id" => $genre->getId()], UrlGeneratorInterface::ABSOLUTE_PATH)
            ],
            true
        );
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_update", methods={"PUT"})
     */
    public function update(Request $request, Genre $genre, SerializerInterface $serializerInterface, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validatorInterface): Response
    {
        $data = $request->getContent();
        $serializerInterface->deserialize($data, Genre::class, 'json', [
            'object_to_populate' => $genre
        ]);
        // Validation des donnees=> gestion des erreurs:
        $errors = $validatorInterface->validate($genre);
        if (count($errors)) {
            $errors_json = $serializerInterface->serialize($errors, 'json');
            return new JsonResponse($errors_json, Response::HTTP_BAD_REQUEST, [], true);
        }
        $entityManagerInterface->flush($genre);
        return new JsonResponse("bien modifié!", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/genre/{id}", name="api_genre_delete", methods={"DELETE"})
     */
    public function delete(Genre $genre, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($genre);
        $entityManagerInterface->flush();
        return new JsonResponse("bien annulé!", Response::HTTP_OK, []);
    }
}
