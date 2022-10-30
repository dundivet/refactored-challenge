<?php

namespace App\Handler;

use App\Entity\ToDo;
use App\Manager\ToDoManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class ToDoHandler extends AbstractHandler
{
    private ToDoManager $manager;

    public function __construct(ToDoManager $manager, RequestStack $requestStack, SerializerInterface $serializer)
    {
        parent::__construct($requestStack, $serializer);

        $this->manager = $manager;
    }

    public function handle(?int $id = null): Response
    {
        $toDo = null;
        if (null !== $id) {
            $toDo = $this->manager->findById($id);
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
            case Request::METHOD_PUT:
                if ($response = $this->handleUpdate($request, $toDo)) {
                    return $response;
                }

                break;
            case Request::METHOD_PATCH:
                if ($response = $this->handleComplete($request, $toDo)) {
                    return $response;
                }
                break;
            case Request::METHOD_DELETE:
                if ($response = $this->handleDelete($request, $toDo)) {
                    return $response;
                }
                break;
            default:
                return new JsonResponse([], Response::HTTP_OK);
        }

        return new JsonResponse(['error' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function handleSearch(Request $request): ?JsonResponse
    {
        $todos = $this->manager->search($request->query->get('query', null));
        
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['list'])
            ->toArray();

        $json = $this->toJson($todos, $context);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    private function handleUpdate(Request $request, ?ToDo $toDo = null): ?JsonResponse
    {
        $toDoObj = $this->fromJson($request->getContent(), ToDo::class);

        if ($toDoObj = $this->manager->update($toDoObj, $toDo)) {
            $json = $this->toJson($toDoObj);
            $statusCode = null !== $toDo ? Response::HTTP_ACCEPTED : Response::HTTP_CREATED;

            return new JsonResponse($json, $statusCode, [], true);
        }

        return null;
    }

    private function handleDelete(Request $request, ToDo $toDo): ?JsonResponse
    {
        if ($this->manager->remove($toDo)) {
            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        }

        return null;
    }

    private function handleComplete(Request $request, ToDo $toDo): ?JsonResponse
    {
        if ($this->manager->complete($toDo)) {
            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        }

        return null;
    }
}
