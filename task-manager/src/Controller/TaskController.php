<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/tasks")
 */
class TaskController extends AbstractController
{
    private $entityManager;
    private $taskRepository;
    private $serializer;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, TaskRepository $taskRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setStatus($data['status']);
        $task->setCreatedAt(new \DateTime());
        $task->setUpdatedAt(new \DateTime());

        $errors = $this->validator->validate($task);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse($this->serializer->serialize($task, 'json'), JsonResponse::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(Request $request, $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setStatus($data['status']);
        $task->setUpdatedAt(new \DateTime());

        $errors = $this->validator->validate($task);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse($this->serializer->serialize($task, 'json'), JsonResponse::HTTP_OK, [], true);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function list(Request $request): JsonResponse
    {
        $status = $request->query->get('status');
        $page = $request->query->get('page', 1);
        $limit = 10;

        $tasks = $this->taskRepository->findByStatus($status, $page, $limit);

        return new JsonResponse($this->serializer->serialize($tasks, 'json'), JsonResponse::HTTP_OK, [], true);
    }

    /**
     * @Route("/search", methods={"GET"})
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('query');
        $tasks = $this->taskRepository->searchByTitleOrDescription($query);

        return new JsonResponse($this->serializer->serialize($tasks, 'json'), JsonResponse::HTTP_OK, [], true);
    }
}
