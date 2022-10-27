<?php

namespace App\Handler;
use App\Entity\ToDo;
use App\Manager\ToDoManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ToDoHandler
{
    private RequestStack $requestStack;

    private ToDoManager $manager;

    private SerializerInterface $serializer;

    public function __construct(RequestStack $requestStack, ToDoManager $manager, SerializerInterface $serializer)
    {
        $this->requestStack = $requestStack;
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    public function handle(?int $id = null): Response
    {
        $toDo = null;
        if (null !== $id) {
            $toDo = $this->manager->getRepository()->findOneBy(['id' => $id]);
            if (null === $toDo) {
                return new JsonResponse([
                    'error' => 'ToDo with id \''.$id.'\' was not found',
                    'code' => 404
                ], Response::HTTP_NOT_FOUND);
            }
        }

        $request = $this->requestStack->getCurrentRequest();
        switch($request->getMethod()) {
            case Request::METHOD_GET:
                return $this->handleSearch($request);
            case Request::METHOD_POST:
                if ($response = $this->handleCreate($request)) {
                    return $response;
                }

                break;
            case Request::METHOD_PUT:
                return new JsonResponse([], Response::HTTP_ACCEPTED);
            case Request::METHOD_PATCH:
                return new JsonResponse([], Response::HTTP_NO_CONTENT);
            case Request::METHOD_DELETE:
                return new JsonResponse([], Response::HTTP_NO_CONTENT);
            default:
                return new JsonResponse([], Response::HTTP_OK);
        }

        return new JsonResponse(['error' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function handleSearch(Request $request): ?JsonResponse
    {
        $todos = $this->manager->search($request->query->get('query', null));

        $json = $this->serializer->serialize($todos, 'json', [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
        ]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    private function handleCreate(Request $request): ?JsonResponse
    {
        $toDoObj = $this->serializer->deserialize($request->getContent(), ToDo::class, 'json', [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
        ]);

        if ($newToDo = $this->manager->create($toDoObj)) {
            $json = $this->serializer->serialize($newToDo, 'json', [
                DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
            ]);

            return new JsonResponse($json, Response::HTTP_CREATED, [], true);
        }

        return null;
    }
}