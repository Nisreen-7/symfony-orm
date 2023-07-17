<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api/student')]
class StudentController extends AbstractController
{
    public function __construct(private StudentRepository $repo, private EntityManagerInterface $em)
    {
    }

    #[Route(methods: 'GET')]
    public function all(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $pageSize = $request->query->get('pageSize', 5);

        return $this->json($this->repo->findBy([], limit: $pageSize, offset: ($page - 1) * $pageSize));
    }

    #[Route('/{id}', methods: 'GET')]
    public function one(Student $student)
    {
        return $this->json($student);
    }

    #[Route(methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            $student = $serializer->deserialize($request->getContent(), Student::class, 'json');
        } catch (\Exception $e) {
            return $this->json('Invalid Body', 400);
        }

        $this->em->persist($student);
        $this->em->flush();
        return $this->json($student, 201);

    }

    #[Route('/{id}', methods: 'DELETE')]
    public function delete(Student $student): JsonResponse
    {
        $this->em->remove($student);
        $this->em->flush();
        return $this->json(null, 204);
    }

    #[Route('/{id}', methods: 'PATCH')]
    public function update(Student $student, Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            $serializer->deserialize($request->getContent(), Student::class, 'json', [
                'object_to_populate' => $student
            ]);
        } catch (\Exception $th) {
            return $this->json('Invalid body', 400);
        }

        $this->em->flush();

        return $this->json($student);
    }
}