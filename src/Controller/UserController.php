<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $userRepository;
    private $serializer;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()], [new XmlEncoder(), new JsonEncoder()]);
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($userRepository->findAll(), 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @Route("/new", name="user_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $this->userRepository->createUser($user);
        return new JsonResponse(['status' => '200'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show($id,UserRepository $userRepository): Response
    {
        if($this->userRepository->find($id) == null){
            return new JsonResponse(['status' => '400', "error" => "User doesn't exist"], Response::HTTP_BAD_REQUEST);
        }
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($userRepository->find($id), 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"POST"})
     */
    public function edit($id,Request $request,UserRepository $userRepository): Response
    {
        $user = $this->userRepository->find($id);
        if($user == null){
            return new JsonResponse(['status' => '400', "error" => "User doesn't exist"], Response::HTTP_BAD_REQUEST);
        }
        $data = json_decode($request->getContent(), true);

        !empty($data['firstname']) ? $user->setFirstname(  $data['firstname'] ) : null;
        !empty($data['lastname'])  ? $user->setLastname(  $data['lastname'] ) : null;
        $this->userRepository->editUser($user);

        return new JsonResponse(['status' => '200'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete($id): Response
    {
        return $this->userRepository->deleteUser($id) ? new JsonResponse(['status' => '200'], Response::HTTP_OK) : new JsonResponse(['status' => '400', "error" => "User doesn't exist"], Response::HTTP_BAD_REQUEST);
    }
}
