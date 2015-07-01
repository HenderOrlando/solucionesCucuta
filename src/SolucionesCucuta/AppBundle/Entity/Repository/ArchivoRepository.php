<?php

namespace SolucionesCucuta\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArchivoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArchivoRepository extends EntityRepository
{
    public function getArchivosTipo($tipo, $results = true){
        $qb = $this->createQueryBuilder('a');
        if(is_numeric($tipo)){
            $qb->andWhere($qb->expr()->eq('a.tipo',$tipo));
        }elseif(is_string($tipo)){
            $qb ->join('a.tipo', 'at')
                ->andWhere($qb->expr()->eq('at.slug',$qb->expr()->literal($tipo)));
        }
        if($results === null){
            return $qb;
        }
        if(!$results){
            return $qb->getQuery();
        }
        return $qb->getQuery()->getResult();
    }

    public function getArchivosTipoBySlug($tipo, $slug, $slughijo, $results = true){
        $qb = $this->getArchivosTipo($tipo, null);

        $qb->innerjoin('a.usuario', 'au')
            ->leftJoin('au.etiquetas', 'aue')
            ;
        if(is_string($slughijo)){
            $qb->andWhere($qb->expr()->eq('aue.slug',$qb->expr()->literal($slughijo)));
        }else{
            $qb
                ->andWhere($qb->expr()->eq('aue.slug',$qb->expr()->literal($slug)))
                /*->leftJoin('ue.hijos', 'ue_hijo')
                ->orWhere('ue_hijo.slug=u.etiquetas')*/
            ;
        }
        if($results === null){
            return $qb;
        }
        if(!$results){
            return $qb->getQuery();
        }
        return $qb->getQuery()->getResult();
    }

}