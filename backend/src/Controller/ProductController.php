<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class ProductController extends ApiController
{
    /**
     * @Route("/api/products", methods="GET")
     * @SWG\Response(
     *     response=200,
     *     description="Returns All products",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @SWG\Tag(name="Product")
     * @Security(name="Bearer")
     */
    public function index (ProductRepository $productRepository)
    {
        $products = $productRepository->renderAll();

        return $this->respond($products);
    }

    /**
     * @Route("/api/product/{id}", methods="GET")
     * @SWG\Response(
     *     response=200,
     *     description="Return product",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not found!",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The Id of product to be returend"
     * )
     * @SWG\Tag(name="Product")
     * @Security(name="Bearer")
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
     * @SWG\Response(
     *     response=200,
     *     description="Create new product",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not found!",
     * )
     * * @SWG\Response(
     *     response=422,
     *     description="Data validaation errors!",
     * )
     * @SWG\Parameter(
     *      type="string", name="name", in="formData", description="The Id of new product", required= true,
     * )
     * @SWG\Parameter(
     *      type="string", name="description", in="formData", description="The name of new product", required= true,
     * )
     * @SWG\Parameter(
     *      type="integer", name="brand", in="formData", description="The brand of new product",required= true,
     * )
     * *@SWG\Parameter(
     *      type="string", name="url", in="formData", description="The url of new product",
     * )
     * @SWG\Parameter(
     *      type="boolean", name="active", in="formData", description="The state of new product",
     *)
     * @SWG\Tag(name="Product")
     * @Security(name="Bearer")
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
     * @SWG\Response(
     *     response=200,
     *     description="Edit new product",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not found!",
     * )
     * * @SWG\Response(
     *     response=422,
     *     description="Data validaation errors!",
     * )
     * @SWG\Parameter(
     *      type="string", name="name", in="formData", description="The Id of new product", required= true,
     * )
     * @SWG\Parameter(
     *      type="string", name="description", in="formData", description="The name of new product", required= true,
     * )
     * @SWG\Parameter(
     *      type="integer", name="brand", in="formData", description="The brand of new product", required= true,
     * )
     * @SWG\Parameter(
     *      type="string", name="url", in="formData", description="The url of new product",
     * )
     * @SWG\Parameter(
     *      type="boolean", name="active", in="formData", description="The state of new product",
     * )
     * @SWG\Tag(name="Product")
     * @Security(name="Bearer")
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
     * @SWG\Response(
     *     response=201,
     *     description="Delte a product",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not found!",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The Id of product to be deleted"
     * )
     * @SWG\Tag(name="Product")
     * @Security(name="Bearer")
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