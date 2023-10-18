<?php
namespace wiki_log_analysis;


require_once __DIR__ . '/Analysis.php';
require_once __DIR__ . '/setupDB.php';

use PDO;
class MoreViewsArticle
{

    private $pdo;
    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function getViews() : array
    {
        $articleNum = $this->getNumber();

        $sql = <<< SQL
            SELECT
                domain_code,
                page_title,
                count_views
            FROM
                page_views
            ORDER BY
                count_views DESC
            LIMIT
                :articleNum
                
        SQL;


        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':articleNum', $articleNum,PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function showResult(array $results)
    {
        if (count($results) === 0) {
            echo '一致するデータがありません.' . PHP_EOL;
        } else {
            foreach ($results as $result) {
                echo  implode(' ', $result) . PHP_EOL;
            }
        }
    }


    public function getNumber() : int
    {
        echo '検索結果を表示する件数を入力してください。' . PHP_EOL;
        $stdin = trim(fgets(STDIN));  

        $articleNum = filter_var($stdin,FILTER_VALIDATE_INT,);
        
        return (int) $articleNum;

    }

}