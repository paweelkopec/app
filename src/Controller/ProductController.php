<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductPagination;
use App\Form\ProductPaginationType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_list", methods={"GET"})
     */
    public function index(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        //request validation
        $productPagination = new ProductPagination();
        $form = $this->createForm(ProductPaginationType::class, $productPagination);
        $form->submit([
            'firstResult' =>  $request->query->getInt('firstResult',0),
            'maxResults' =>  $request->query->getInt('maxResults',10),
            'order' =>  $request->query->get('order',"id"),
            'sort' =>  $request->query->get('sort',"asc"),
        ],true);
        if (!$form->isValid()) {
            return $this->json( ['errors' => $this->getErrorsFromForm($form)], Response::HTTP_BAD_REQUEST);
        }
        //query
        $entityManager = $doctrine->getManager();
        $query = $entityManager->createQuery("select p from App\Entity\Product p  ORDER BY p.{$productPagination->getOrder()} {$productPagination->getSort()}")
            ->setFirstResult($productPagination->getFirstResult())
            ->setMaxResults($productPagination->getMaxResults());
        //paginator
        $paginator  = new Paginator( $query);
        $productPagination->setTotalResults(count($paginator));
        //response
        return $this->json( ['products' =>  $paginator,  'pagination' => $productPagination ] );
    }

    /**
     * @Route("/product", name="product_create", methods={"POST"})
     */
    public function create(Request $request,  ProductRepository $productRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data,true);
        if (!$form->isValid()) {
            return $this->json( ['errors' => $this->getErrorsFromForm($form)], Response::HTTP_BAD_REQUEST);
        }
        $productRepository->add($product );
        return $this->json( $product , Response::HTTP_CREATED );
    }

    /**
     * @Route("/product/{id}", name="product_update", methods={"PUT"})
     */
    public function update(int $id, Request $request,  ProductRepository $productRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = $productRepository
            ->find($id);
        if(is_null($product)){
            return $this->json(   ["errors" => ["Not found"] ], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data, true);
        if (!$form->isValid()) {
            return $this->json( ['errors' => $this->getErrorsFromForm($form)], Response::HTTP_BAD_REQUEST);
        }
        $productRepository->update($product);
        return $this->json(   $product,  Response::HTTP_ACCEPTED );
    }

    /**
     * @Route("/product/{id}", name="product_read", methods={"GET"})
     */
    public function read(int $id,   ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository
            ->find($id);
        if(is_null($product)){
            return $this->json(   ["errors" => ["Not found"] ], Response::HTTP_NOT_FOUND);
        }
        return $this->json(    $product );
    }

    /**
     * @Route("/product/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(int $id,   ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository
            ->find($id);
        if(is_null($product)){
            return $this->json(   ["errors" => ["Not found"] ], Response::HTTP_NOT_FOUND);
        }
        $productRepository->remove($product );
        return $this->json( [],  Response::HTTP_ACCEPTED  );
    }


    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

}
