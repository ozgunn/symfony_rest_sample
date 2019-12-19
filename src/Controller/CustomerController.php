<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerController extends AbstractController
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/add", name="add_customer", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $phoneNumber = $data['phoneNumber'];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber)) {
            return new JsonResponse(['status' => 'Missing required fields'], 406);
        }

        $this->customerRepository->saveCustomer($firstName, $lastName, $email, $phoneNumber);

        return new JsonResponse(['status' => 'Customer created!'], 200);
    }

    /**
     * @Route("/list", name="list_customer", methods={"GET"})
     */
    public function list(SerializerInterface $serializer): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $response = $serializer->serialize($customers,'json');
        return new JsonResponse($response, 200, [], true);
    }

    /**
     * @Route("/get/{id}", name="get_customer", methods={"GET"})
     */
    public function getAll($id, SerializerInterface $serializer): JsonResponse
    {
        $customers = $this->customerRepository->find($id);
        $response = $serializer->serialize($customers,'json');
        return new JsonResponse($response, 200, [], true);
    }

    /**
     * @Route("/delete/{id}", name="delete_customer", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $customers = $this->customerRepository->deleteCustomer($id);
        return new JsonResponse(['status' => 'Customer deleted!'], 200);
    }

    // TODO: Auth
}
