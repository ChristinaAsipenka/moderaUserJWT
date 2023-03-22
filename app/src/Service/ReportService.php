<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;

class ReportService
{
    private EntityManagerInterface $entityManager;
    private User $currentUser;
    private const ROLE_ADMIN = 'ROLE_ADMIN';

    public function __construct(EntityManagerInterface $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->currentUser = $userService->getCurrentUser();
    }
    public function makeReport(string $from, string $till): array
    {

        $id_user = (in_array(self::ROLE_ADMIN, $this->currentUser->getRoles()) ? null : $this->currentUser->getId());
        $blockWhere = $blockUser = $blockFrom = $blockTill = "";
        $parameters = ['text' => 'How I like it',
                      'text2' => 'Will I recommend this post to my friends'];
        if ($id_user !== null || $from !== null || $till !== null) {
            $blockWhere =" WHERE";
            if ($id_user !== null ){
                $blockUser = " post.owner_id = :id_user ";
                $parameters = array_merge($parameters, ['id_user'=> $id_user]);
            }

            if ($from !== null) {
                $blockFrom = " feedback.added_at >= :from ";
                $parameters = array_merge($parameters, ['from'=> $from]);
            }

            if ($till !== null) {
                $blockTill= " feedback.added_at <= :till ";
                $parameters = array_merge($parameters, ['till'=> $till]);
            }

            $blockWhere .= (strlen($blockUser) !== 0 ? $blockUser : '');

            if (strlen($blockUser)!== 0 and (strlen($blockFrom) !== 0 || strlen($blockTill) !== 0)) {
                $blockWhere = $blockWhere." AND ";
            }

            if (strlen($blockFrom) !== 0) {
                $blockWhere .= $blockFrom;
            }

            if ((strlen($blockUser)!== 0 || strlen($blockFrom) !== 0) && strlen($blockTill) !== 0) {
                $blockWhere = $blockWhere." AND ".$blockTill;
            }

        }

        $query = "select post.title,
                    SUM(CASE WHEN feedback.feedback_text LIKE :text then 1 else 0 end ) AS feedback_like,
                    SUM(CASE WHEN feedback.feedback_text LIKE :text2 then 1 else 0 end) AS feedback_share
                  FROM post
                  left join feedback ON feedback.post_id = post.id".$blockWhere."
                  GROUP BY post.title
                 ";
        $query_result = $this->entityManager
            ->getConnection("default")
            ->prepare($query)
            ->executeQuery($parameters)
            ->fetchAllAssociative();

        return $query_result;
    }
}
