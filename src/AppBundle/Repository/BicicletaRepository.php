<?php

namespace AppBundle\Repository;

/**
 * BicicletaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BicicletaRepository extends \Doctrine\ORM\EntityRepository
{
    public function buscarTitulo($palabra) {
        return $this->getEntityManager()

            ->createQuery("SELECT bici from AppBundle:Bicicleta bici 
                      WHERE bici.name LIKE :palabra")->setParameter('palabra', '%'. $palabra. '%')->getResult();
    }
}
