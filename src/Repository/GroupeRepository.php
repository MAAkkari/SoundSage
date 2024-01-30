<?php

namespace App\Repository;

use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Groupe>
 *
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }
    
    public function findPopulaire(){

        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()
            ->select('g.id')
            ->addSelect('SUM(m.nbEcoutes) as totalEcoutes')
            ->from('App\Entity\Groupe', 'g')
            ->innerJoin('g.musiques', 'm')
            ->groupBy('g.id')
            ->orderBy('totalEcoutes', 'DESC')
            ->setMaxResults(6);
        $results = $query->getQuery();
        return $results->getResult();

    }

    public function findPlusLiker(int $limit)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT user_groupe_liker.groupe_id, Count(user_groupe_liker.groupe_id) FROM user_groupe_liker
            GROUP BY user_groupe_liker.groupe_id
            ORDER BY Count(user_groupe_liker.groupe_id) DESC
            LIMIT :limit;
        ';

        $resultSet = $conn->executeQuery($sql, ['limit' => $limit], ['limit' => \Doctrine\DBAL\ParameterType::INTEGER]);
        return $resultSet->fetchAllAssociative();
    }
    public function findPlusEcouter($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM groupe
            INNER JOIN album_groupe ON album_groupe.groupe_id = groupe.id
            INNER JOIN musique ON musique.album_id = album_groupe.album_id
            WHERE groupe.id = :id
            ORDER BY musique.nb_ecoutes DESC
            LIMIT 3;
        ';

        $resultSet = $conn->executeQuery($sql , ['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return Groupe[] Returns an array of Groupe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Groupe
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
