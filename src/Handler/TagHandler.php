<?php 

namespace App\Handler;
use App\Entity\Tag;
use App\Manager\TagManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class TagHandler extends AbstractHandler
{
    private TagManager $manager;

    public function __construct(TagManager $manager, RequestStack $requestStack, SerializerInterface $serializer)
    {
        parent::__construct($requestStack, $serializer);

        $this->manager = $manager;
    }

    public function handle(?int $id = null): Response
    {
        $tag = null;
        if (null !== $id) {
            $tag = $this->manager->findById($id);
            if (null === $tag) {
                return new JsonResponse([
                    'error' => 'Tag with id \''.$id.'\' was not found',
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
                if ($response = $this->handleUpdate($request, $tag)) {
                    return $response;
                }

                break;
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
        $tags = $this->manager->search($request->query->get('query', null));

        $json = $this->toJson($tags);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    private function handleUpdate(Request $request, ?Tag $tag = null): ?JsonResponse
    {
        $tagObj = $this->fromJson($request->getContent(), Tag::class);

        if ($tagObj = $this->manager->update($tagObj, $tag)) {
            $json = $this->toJson($tagObj);
            $statusCode = null !== $tag ? Response::HTTP_ACCEPTED : Response::HTTP_CREATED;

            return new JsonResponse($json, $statusCode, [], true);
        }

        return null;
    }
}
