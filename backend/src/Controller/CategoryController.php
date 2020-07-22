<?php


namespace App\Controller;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends ApiController
{
    /**
     * @Route("/api/categories", methods="GET")
     */
    public function index (CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->renderAll();

        return $this->respond($categories);
    }

    /**
     * @Route("/api/category", methods="POST")
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