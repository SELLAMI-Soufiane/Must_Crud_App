<?php


namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class BrandController extends ApiController
{
    /**
     * @Route("/api/brands", methods="GET")
     * @SWG\Response(
     *     response=200,
     *     description="Returns All brands",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Brand::class))
     *     )
     * )
     * @SWG\Tag(name="Brand")
     * @Security(name="Bearer")
     */
    public function index (BrandRepository $brandRepository)
    {
        $brands = $brandRepository->renderAll();

        return $this->respond($brands);
    }

    /**
     * @Route("/api/brand", methods="POST")
     * @SWG\Response(
     *     response=200,
     *     description="Create new brand",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Brand::class))
     *     )
     * )
     *
     * @SWG\Response(
     *     response=422,
     *     description="Data validaation errors!",
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="The name of new brand"
     * )
     * @SWG\Tag(name="Brand")
     * @Security(name="Bearer")
     */
    public function create(Request $request, BrandRepository $brandRepository, EntityManagerInterface $em)
    {

        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid data!');
        }

        // validate the name
        if (! $request->get('name')) {
            return $this->respondValidationError('Please provide a name!');
        }

        // persist the new category
        $brand = new Brand();
        $brand->setName($request->get('name'));
        $em->persist($brand);
        $em->flush();

        return $this->respondCreated($brandRepository->render($brand));
    }

    /**
     * @Route("/api/brand/edit/{id}", methods="PUT")
     * @SWG\Response(
     *     response=200,
     *     description="Edit a brand",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Brand::class))
     *     )
     * )
     *
     * @SWG\Response(
     *     response=422,
     *     description="Data validaation errors!",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Resource Not found!",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="The id of brand"
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="The new name of brand",
     *     required=true,
     * )
     * @SWG\Tag(name="Brand")
     * @Security(name="Bearer")
     */
    public function update($id, Request $request, BrandRepository $brandRepository, EntityManagerInterface $em)
    {

        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid data!');
        }

        $brand = $brandRepository->find($id);

        //validate data
        if (! $brand) {
            return $this->respondNotFound();
        }

        // validate the name
        if (! $request->get('name')) {
            return $this->respondValidationError('Please provide a name!');
        }

        // persist the new category
        $brand->setName($request->get('name'));
        $em->persist($brand);
        $em->flush();

        return $this->respondCreated($brandRepository->render($brand));
    }

    /**
     * @Route("/api/brand/delete/{id}", methods="DELETE")
     * @SWG\Response(
     *     response=201,
     *     description="Delete a brand",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Brand::class))
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
     *     description="The id of brand"
     * )
     * @SWG\Tag(name="Brand")
     * @Security(name="Bearer")
     */
    public function delete($id, BrandRepository $brandRepository, EntityManagerInterface $em)
    {
        $brand = $brandRepository->find($id);

        if (! $brand) {
            return $this->respondNotFound();
        }
        $em->remove($brand);
        $em->flush();

        return $this->respondDeleted();

    }
}