<?php

namespace App\Repository;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function displayWithoutFilter(?User $user)
    {
        $query = $this->createQueryBuilder('sortie');
        $query->leftJoin('sortie.etat', 's');
        // On retire les sortie enregistrés non publiés des autres users
        $query->where('sortie.nom != :nom ')
            ->setParameter('nom', 'En création');

        // On n'affiche pas les sorties passées
        $query->andWhere('sortie.dateHeureDebut > :dateLimiteInscription')
            //         ->setParameter('dateLimiteInscription', new DateTime(), \Doctrine\DBAL\Types\Type::DATETIME);
            ->setParameter('dateLimiteInscription', new DateTime());
        // Trier les résultats par date limite d'inscription
        $query->orderBy('sortie.dateLimiteInscription', 'ASC');
        return $query->getQuery()->getResult();
    }
    public function filter(?User $user, ?Site $site, ?String $search, ?DateTime $minDate, ?DateTime $maxDate, $organiser, $isAEteInscrit, $isNotAEteInscrit, $isArchived)
    {
        $query = $this->createQueryBuilder('sortie');
        $query->leftJoin('sortie.etat', 's');
        // Filtre site
        if ($site != null) {
            $query->innerJoin('sortie.site', 'site', 'WITH', 'site = :site')
                ->setParameter('site', $site);
        }

        // Filtre date
        if ($minDate != null && $maxDate != null) {
            $query->andWhere($query->expr()->between('sortie.dateHeureDebut', ':date_from', ':date_to'))
                ->setParameter('date_from', $minDate)
                ->setParameter('date_to', $maxDate);
        }

        // Filtre search
        if ($search != null) {
            $query->andWhere('sortie.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        // Trier les résultats par date limite d'inscription
        $query->orderBy('sortie.dateLimiteInscription', 'ASC');
        return $query->getQuery()->getResult();
    }
}
