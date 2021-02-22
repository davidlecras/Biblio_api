<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Repository\NationalityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiAuteurController extends AbstractController
{

    /**
     * @Route("/api/auteur", name="api_auteur", methods={"GET"})
     */
    public function index(AuteurRepository $auteurRepository, SerializerInterface $serializerInterface): Response
    {
        $auteur = $auteurRepository->findAll();
        $result = $serializerInterface->serialize($auteur, 'json', [
            'groups' => ['listeAuteursFull']
        ]);
        return new JsonResponse($result, 200, [], true);
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_show", methods={"GET"})
     */
    public function show(Auteur $auteur, SerializerInterface $serializerInterface): Response
    {
        $result = $serializerInterface->serialize($auteur, 'json', [
            'groups' => ['listeAuteursSimple']
        ]);
        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/auteur", name="api_auteur_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManagerInterface, SerializerInterface $serializerInterface, ValidatorInterface $validatorInterface): Response
    {
        $data = $request->getContent();
        //1ere methode:
        // $auteur = new Auteur();
        // $result = $serializerInterface->deserialize($data, Auteur::class, 'json', ['object_to_populate'=>$auteur]);

        //2eme methode:
        $auteur = $serializerInterface->deserialize($data, Auteur::class, 'json');

        // Validation des donnees=> gestion des erreurs:
        $errors = $validatorInterface->validate($auteur);
        if (count($errors)) {
            $errors_json = $serializerInterface->serialize($errors, 'json');
            return new JsonResponse($errors_json, Response::HTTP_BAD_REQUEST, [], true);
        }

        // Enregistrement en BDD:
        $entityManagerInterface->persist($auteur);
        $entityManagerInterface->flush();


        // 1er exemple de retour de reponse Json:
        // return new JsonResponse(null, Response::HTTP_CREATED, [
        //     "location" => "api/auteur" . $auteur->getId()
        // ], true);


        // 2eme methode:
        return new JsonResponse(
            "auteur créé",
            Response::HTTP_CREATED,
            [
                "location" => $this->generateUrl('api_auteur_show', ["id" => $auteur->getId()], UrlGeneratorInterface::ABSOLUTE_PATH)
            ],
            true
        );
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_update", methods={"PUT"})
     */
    public function update(Request $request, NationalityRepository $nationalityRepository, Auteur $auteur, SerializerInterface $serializerInterface, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validatorInterface): Response
    {
        $data = $request->getContent();
        //Solution 1:
        $serializerInterface->deserialize($data, Auteur::class, 'json', [
            'object_to_populate' => $auteur
        ]);

        // Validation des donnees=> gestion des erreurs:
        $errors = $validatorInterface->validate($auteur);
        if (count($errors)) {
            $errors_json = $serializerInterface->serialize($errors, 'json');
            return new JsonResponse($errors_json, Response::HTTP_BAD_REQUEST, [], true);
        }
        $entityManagerInterface->flush($auteur);
        return new JsonResponse("bien modifié!", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/auteur/{id}", name="api_auteur_delete", methods={"DELETE"})
     */
    public function delete(Auteur $auteur, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($auteur);
        $entityManagerInterface->flush();
        return new JsonResponse("bien annulé!", Response::HTTP_OK, []);
    }
}
