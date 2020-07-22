<?php


namespace App\Controller;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class CategoryController extends ApiController
{
    /**
     * @Route("/api/categories", methods="GET")
     * @SWG\Response(
     *     response=200,
     *     description="Returns All categories",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Category::class))
     *     )
     * )
     * @SWG\Tag(name="Category")
     * @Security(name="Bearer")
     */
    public function index (CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->renderAll();

        return $this->respond($categories);
    }

    /**
     * @Route("/api/category", methods="POST")
     * @SWG\Response(
     *     response=200,
     *     description="Create new Category",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Category::class))
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
     *     description="The name of new Category"
     * )
     * @SWG\Tag(name="Category")
     * @Security(name="Bearer")
     */
    public function create(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em)
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
        $category = new Category();
        $category->setName($request->get('name'));
        $em->persist($category);
        $em->flush();

        return $this->respondCreated($categoryRepository->render($category));
    }

    /**
     * @Route("/api/category/edit/{id}", methods="PUT")
     * @SWG\Response(
     *     response=200,
     *     description="Edit a Category",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Category::class))
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
     *     description="The id of Category"
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="The new name of Category",
     *     required=true,
     * )
     * @SWG\Tag(name="Category")
     * @Security(name="Bearer")
     */
    public function update($id, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {

        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid data!');
        }

        $category = $categoryRepository->find($id);

        //validate data
        if (! $category) {
            return $this->respondNotFound();
        }

        // validate the name
        if (! $request->get('name')) {
            return $this->respondValidationError('Please provide a name!');
        }

        // persist the new category
        $category->setName($request->get('name'));
        $em->persist($category);
        $em->flush();

        return $this->respondCreated($categoryRepository->render($category));
    }

    /**
     * @Route("/api/category/delete/{id}", methods="DELETE")
     * @SWG\Response(
     *     response=201,
     *     description="Delete a Category",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Category::class))
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
     *     description="The id of Category"
     * )
     * @SWG\Tag(name="Category")
     * @Security(name="Bearer")
     */
    public function delete($id, CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        $category = $categoryRepository->find($id);

        if (! $category) {
            return $this->respondNotFound();
        }
        $em->remove($category);
        $em->flush();

        return $this->respondDeleted();

    }
}