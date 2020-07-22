<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends ApiController
{
    /**
     * @Route("/api/products", methods="GET")
     */
    public function index (ProductRepository $productRepository)
    {
        $products = $productRepository->renderAll();

        return $this->respond($products);
    }

    /**
     * @Route("/api/product/{id}", methods="GET")
     */
    public function read ($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);

        //validate data
        if (! $product) {
            return $this->respondNotFound();
        }

        return $this->respond($productRepository->render($product));
    }

    /**
     * @Route("/api/product", methods="POST")
     */
    public function create(Request $request, ProductRepository $productRepository, BrandRepository $brandRepository, EntityManagerInterface $em)
    {

        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid data!');
        }

        // validate the name
        if (! $request->get('name')) {
            return $this->respondValidationError('Please provide a name!');
        }

        // validate the description
        if (! $request->get('description')) {
            return $this->respondValidationError('Please provide a description!');
        }

        // validate the brand
        if (! $request->get('brand')) {
            return $this->respondValidationError('Please provide a brand!');
        }

        $brand = $brandRepository->find($request->get('brand'));
        if(! $brand){
            return $this->respondNotFound();
        }

        // persist the new product
        $product = new Product();
        $product = $productRepository->saveProduct($request, $product, $brand);
        $em->persist($product);
        $em->flush();

        return $this->respondCreated($productRepository->render($product));
    }

    /**
     * @Route("/api/product/edit/{id}", methods="PUT")
     */
    public function update($id, Request $request, ProductRepository $productRepository, BrandRepository $brandRepository, EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid data!');
        }

        $product = $productRepository->find($id);

        //validate data
        if (! $product) {
            return $this->respondNotFound();
        }

        // validate the name
        if (! $request->get('name')) {
            return $this->respondValidationError('Please provide a name!');
        }

        // validate the description
        if (! $request->get('description')) {
            return $this->respondValidationError('Please provide a description!');
        }

        // validate the brand
        if (! $request->get('brand')) {
            return $this->respondValidationError('Please provide a brand!');
        }
        $brand = $brandRepository->find($request->get('brand'));
        if(! $brand){
            return $this->respondNotFound();
        }


        // persist the new product
        $product = $productRepository->saveProduct($request, $product, $brand);
        $em->persist($product);

        return $this->respondCreated($productRepository->render($product));
    }

    /**
     * @Route("/api/product/delete/{id}", methods="DELETE")
     */
    public function delete($id, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $product = $productRepository->find($id);

        if (! $product) {
            return $this->respondNotFound();
        }
        $em->remove($product);
        $em->flush();

        return $this->respondDeleted();

    }

}