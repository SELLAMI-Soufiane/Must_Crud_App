<?php


namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends ApiController
{
    /**
     * @Route("/api/brands", methods="GET")
     */
    public function index (BrandRepository $brandRepository)
    {
        $brands = $brandRepository->renderAll();

        return $this->respond($brands);
    }

    /**
     * @Route("/api/brand", methods="POST")
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