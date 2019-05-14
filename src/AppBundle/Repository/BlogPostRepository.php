<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class BlogPostRepository extends EntityRepository {

    public function findAllDescByCreateDate() {
        return $this->findBy(array(), array('createDate' => 'DESC'));
    }

    public function findDescByCreateDate($userId) {

        $em = $this->getEntityManager();
        $query = "SELECT * FROM blog_post WHERE user_id = :userId ORDER BY create_date DESC";

        $statement = $em->getConnection()->prepare($query);
        $statement->bindValue("userId", $userId);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    public function getRecentPosts() {
        return $this->getEntityManager()->createQuery(
            'SELECT blogPost.id, blogPost.title, blogPost.createDate FROM AppBundle:BlogPost blogPost 
            WHERE blogPost.createDate >= :interval_date
            ORDER BY blogPost.createDate DESC'
        )
        ->setParameter("interval_date", new \DateTime('-2 days'))
        ->getResult();
    }

    public function getArchiveMonthsAndYears() {

        $em = $this->getEntityManager();
        $query = "SELECT  CONCAT(MONTHNAME(x.create_date),' ',year(x.create_date)) as 'monthYear', 
                    COUNT(x.id) AS 'posts', year(x.create_date) as 'y', month(x.create_date) as 'm'
                    from blog_post x
                    GROUP BY monthYear, y, m
                    order by y desc, m desc";

        $statement = $em->getConnection()->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();

        $archiveArray = [];
        
        foreach($results as $result) {

            $monthYear = $result["monthYear"];
            $postsNumber = $result["posts"];

            $month = $result['m'];
            $year = $result['y'];

            $postsQuery = "SELECT * FROM blog_post WHERE MONTH(create_date) = :month  AND YEAR(create_date) = :year 
            ORDER BY create_date DESC";

            $postsStatement = $em->getConnection()->prepare($postsQuery);
            $postsStatement->bindValue("month", $month);
            $postsStatement->bindValue("year", $year);
            $postsStatement->execute();
            $posts = $postsStatement->fetchAll();

            $archiveObject = new Archive();
            $archiveObject->monthYear = $monthYear;
            $archiveObject->postsNumber = $postsNumber;
            $archiveObject->posts = $posts;

            array_push($archiveArray, $archiveObject);
        }
        
        return $archiveArray;
    } 
}

class Archive {
    public $monthYear = "";
    public $postsNumber = "";
    public $posts = [];

}