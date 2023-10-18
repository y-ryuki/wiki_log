<?php

namespace wiki_log_analysis;


require_once __DIR__ . '/Analysis.php';
// require_once __DIR__ . '/setupDB.php';

use PDO;

class searchArticle
{

    public function getSearchViews($pdo) : array
    {
        $domain_array = $this->getDomain();

        $domain = implode(",",array_fill(0, count($domain_array), '?'));

        $sql = <<< SQL
            SELECT
                domain_code,
                SUM(count_views) AS total_views
                
            FROM
                page_views
            
            WHERE
                domain_code IN ($domain)

            GROUP BY domain_code
            ORDER BY
                total_views DESC
        SQL;


        $stmt = $pdo->prepare($sql);
        $stmt->execute($domain_array);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;

    }

    public function getDomain() :array
    { 
        echo "ドメインコードをスペース区切りで入力してください。" . PHP_EOL;
        $stdin = trim(fgets(STDIN));
        $stdin_array = explode(" ", $stdin);

        $domain=[];
        foreach($stdin_array as $input){
            if(filter_var($input,FILTER_VALIDATE_DOMAIN)){
                $domain[] = $input;

            }else {
                echo 'ドメインコードの入力形式に誤りがあります。' . PHP_EOL;
                break;
            }
        }

        return $domain;

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







}