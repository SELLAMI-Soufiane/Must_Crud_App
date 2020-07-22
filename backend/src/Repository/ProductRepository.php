<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function render(Product $product)
    {
        return [
            'id'    => (int) $product->getId(),
            'md5 '    => (string) md5($product->getId()),
            'name' => (string) $product->getName(),
            'description' => (string) $product->getDescription(),
            'url' => (string) $product->getUrl(),
            'active' => (string) $product->getActive(),
            'brand' => (array) $product->getBrand(),
            'categories' => (array) $product->getCategories(),
        ];
    }

    public function renderAll ()
    {
        $products = $this->findAll();
        $productsArray = [];

        foreach ($products as $product) {
            $productsArray[] = $this->render($product);
        }

        return $productsArray;
    }

    public function saveProduct(Request $request, Product $product, Brand $brand)
    {
        $product->setName($request->get('name'));
        $product->setBrand($brand);
        $product->setDescription($request->get('description'));
        if (! $request->get('url')) {
            $product->setUrl($request->get('url'));
        }
        if (! $request->get('active')) {
            $product->setActive($request->get('active'));
        }
        return $product;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
