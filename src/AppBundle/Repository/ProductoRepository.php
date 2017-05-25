<?php

namespace AppBundle\Repository;

/**
 * ProductoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductoRepository extends \Doctrine\ORM\EntityRepository
{
    public function buscarTitulo($palabra) {
        return $this->getEntityManager()

        ->createQuery("SELECT prod from AppBundle:Producto prod 
                      WHERE prod.name LIKE :palabra")->setParameter('palabra', '%'. $palabra. '%')->getResult();
    }
}
