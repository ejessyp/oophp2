<?php

// namespace Anax\Controller;
namespace Pan\Cms;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

class CmsController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
    * @var string $db a sample member variable that gets initialised
    */
    private $db;


    /**
    * The initialize method is optional and will always be called before the
    * target method/action. This is a convienient method where you could
    * setup internal properties that are commonly used by several methods.
    *
    * @return void
    */

    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->db = $this->app->db;
        $this->db->connect();
        $this->app->page->add("cms/header");
    }

    /**
    * This is the index method action, it handles:
    * ANY METHOD mountpoint
    * ANY METHOD mountpoint/
    * ANY METHOD mountpoint/index
    *
    * @return object
    */
    public function showallActionGet() : object
    {
        $page = $this->app->page;
        $title = "Show all content";

        $sql = "SELECT * FROM content;";
        $resultset = $this->db->executeFetchAll($sql);
        // $page->add("cms/header");
        $page->add("cms/show-all", ["resultset" => $resultset, ]);
        return $page->render(["title" => $title, ]);
    }


    /**
    * This is the view pages method action, it handles:
    *
    * ANY METHOD mountpoint/page
    *
    * @return object
    */
    public function pagesAction() : object
    {
        $page = $this->app->page;
        $title = "View pages of contents";
        $sql = <<<EOD
SELECT
    *,
    CASE
        WHEN (deleted <= NOW()) THEN "isDeleted"
        WHEN (published <= NOW()) THEN "isPublished"
        ELSE "notPublished"
    END AS status
FROM content
WHERE type=?
;
EOD;
        $resultset = $this->db->executeFetchAll($sql, ["page"]);
        // $page->add("cms/header");
        $page->add("cms/pages", ["resultset" => $resultset, ]);
        return $page->render(["title" => $title, ]);
    }

    /**
    * This is the page method Get action, it handles:
    *
    * ANY METHOD mountpoint/page
    *
    * @return object
    */
    public function pageActionGet($path) : object
    {
        $page = $this->app->page;
        $title = "View page ". $path;
        $sql = <<<EOD
SELECT
   *,
   DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS modified_iso8601,
   DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS modified
FROM content
WHERE
   path = ?
   AND type = ?
   AND (deleted IS NULL OR deleted > NOW())
   AND published <= NOW()
;
EOD;
        $content = $this->db->executeFetch($sql, [$path, "page"]);
        if (!$content) {
            // $page->add("cms/header");
            $page->add("cms/404");
            $title = "404";
            return $page->render(["title" => $title, ]);
        }
        $title = $content->title;
        $arr = explode(",", $content->filter);
        $content->filter = $arr;
        var_dump($arr);
        $textfilter = new \Pan\TextFilter\MyTextFilter();
        $content->data = $textfilter->parse($content->data, $content->filter);
        // $page->add("cms/header");
        $page->add("cms/page", ["content" => $content, ]);
        return $page->render(["title" => $title, ]);
    }

    public function variadicActionGet(...$value) : string
    {
        // Deal with the action and return a response.
        return __METHOD__ . ", got '" . count($value) . "' arguments: " . implode(", ", $value);
    }

    /**
    * This is the blog method  action, it handles:
    *
    * ANY METHOD mountpoint/blog
    *
    * @return object
    */
    public function blogActionGet(...$value) : object
    {
        if ($value) {
            $page = $this->app->page;
            $title = "View page ". $value[1];
            $sql = <<<EOD
SELECT
        *,
        DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
        DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
FROM content
WHERE
        slug = ?
        AND type = ?
        AND (deleted IS NULL OR deleted > NOW())
        AND published <= NOW()
        ORDER BY published DESC
;
EOD;
            $content = $this->db->executeFetch($sql, [$value[1], "post"]);
            if (!$content) {
                $page->add("cms/404");
                $title = "404";
                return $page->render(["title" => $title, ]);
            }
            $title = $content->title;
            $arr = explode(",", $content->filter);
            $content->filter = $arr;
            var_dump($arr);
            $textfilter = new \Pan\TextFilter\MyTextFilter();
            $content->data = $textfilter->parse($content->data, $content->filter);
            $page->add("cms/blogpost", ["content" => $content, ]);
            return $page->render(["title" => $title, ]);
        }
        $page = $this->app->page;
        $title = "View blogs of contents";
        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
FROM content
WHERE type=?
ORDER BY published DESC
;
EOD;
        $resultset = $this->db->executeFetchAll($sql, ["post"]);
        // $page->add("cms/header");
        $page->add("cms/blog", ["resultset" => $resultset, ]);
        return $page->render(["title" => $title, ]);
    }


//     /**
//     * This is the blogpost method Get action, it handles:
//     *
//     * ANY METHOD mountpoint/page
//     *
//     * @return object
//     */
//     public function blogPostActionGet($slug) : object
//     {
//         $page = $this->app->page;
//         $title = "View page ". $slug;
//         $sql = <<<EOD
// SELECT
//     *,
//     DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
//     DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
// FROM content
// WHERE
//     slug = ?
//     AND type = ?
//     AND (deleted IS NULL OR deleted > NOW())
//     AND published <= NOW()
//     ORDER BY published DESC
// ;
// EOD;
//        $content = $this->db->executeFetch($sql, [$slug, "post"]);
//        if (!$content) {
//            $page->add("cms/header");
//            $page->add("cms/404");
//            $title = "404";
//            return $page->render(["title" => $title, ]);
//        }
//        $title = $content->title;
//        var_dump($content);
//        $arr = explode(",", $content->filter);
//        $content->filter = $arr;
//        var_dump($arr);
//        $textfilter = new \Pan\TextFilter\MyTextfilter();
//        $content->data = $textfilter->parse($content->data, $content->filter);
//
//        $page->add("cms/header");
//        $page->add("cms/blogpost", ["content" => $content, ]);
//        return $page->render(["title" => $title, ]);
//     }

    /**
    * This is the admin database method get action, it handles:
    * edit and delete movie
    *
    * @return object
    */
    public function adminActionGet() : object
    {
        $title = "Admin content";
        $sql = "SELECT * FROM content;";
        $resultset = $this->db->executeFetchAll($sql);
        $data = [
            "resultset"=> $resultset ?? []
        ];
        $this->app->page->add("cms/header");
        $this->app->page->add("cms/admin", $data);
        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * This is the delete method get action, it handles:
    * delete content
    *
    * @return object
    */
    public function deleteActionGet($id) : object
    {
        $title = "Delete content";
        $sql = "SELECT id, title FROM content WHERE id = ?;";
        $content = $this->db->executeFetch($sql, [$id]);
        $data = [
            "content"=> $content
        ];
        $this->app->page->add("cms/header");
        $this->app->page->add("cms/delete", $data);
        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * This is the delete method get action, it handles:
    * delete content
    *
    * @return object
    */
    public function deleteActionPost($id) : object
    {
        $response = $this->app->response;
        if (!is_numeric($id)) {
            die("Not valid for content id.");
        }

        if (hasKeyPost("doDelete")) {
            $sql = "UPDATE content SET deleted=NOW() WHERE id=?;";
            $this->db->execute($sql, [$id]);
            return $response->redirect("cms/showall");
        }
    }


    /**
    * This is the edit content method get action, it handles:
    * edit a movie
    *
    * @return object
    */
    public function editActionGet($id) : object
    {
        $title = "Update content";
        $page = $this->app->page;
        $sql = "SELECT * FROM content WHERE id = ?;";
        $content = $this->db->executeFetchAll($sql, [$id]);
        $content = $content[0];
        $data = [
            "content"=> $content
        ];

        // $page->add("cms/header");
        $page->add("cms/edit", $data);
         return $page->render(["title" => $title,]);
    }


    /**
    * This is the edit content method post action, it handles:
    * edit a movie
    *
    * @return object
    */
    public function editActionPost($id) : object
    {
        $response = $this->app->response;
        if (!is_numeric($id)) {
            die("Not valid for content id.");
        }
        if (hasKeyPost("doSave")) {
            $params = getPost([
                "contentTitle",
                "contentPath",
                "contentSlug",
                "contentData",
                "contentType",
                "contentFilter",
                "contentPublish",
                "contentId"
            ]);

            if (!$params["contentSlug"]) {
                $params["contentSlug"] = slugify($params["contentTitle"]);
            }

            if (!$params["contentPath"]) {
                $params["contentPath"] = null;
            }

            $sql = "UPDATE content SET title=?, path=?, slug=?, data=?, type=?, filter=?, published=? WHERE id = ?;";
            $this->db->execute($sql, array_values($params));
            return $response->redirect("cms/showall");
        }
    }

    /**
    * This is the add movie method get action, it handles:
    * add a movie
    *
    * @return object
    */
    public function addActionGet() : object
    {
        // Incoming variables
        $title = "Create contents";
        $sql = "select max(id) as maxid from content;";
        $res = $this->db->executeFetchAll($sql);
        $id = $res[0]->maxid + 1;
        $published = date('Y-m-d H:i:s', time());
        $page = $this->app->page;
        $arr = [
            "id" => $id,
            "title" => "",
            "path" => "",
            "slug" => "",
            "data" => "",
            "type" => "",
            "filter" => "",
            "published" => "$published"
        ];
        // Change array to an object
        $content = new \StdClass();
        foreach ($arr as $key => $val) {
            $content->$key = $val;
        };

        // $page->add("cms/header");
        $page->add("cms/add", ["content" => $content, ]);
        return $page->render([
            "title" => $title,
        ]);
    }


    /**
    * This is the create content method post action, it handles:
    * create contents
    *
    * @return object
    */
    public function addActionPost() : object
    {
        $request = $this->app->request;
        $response = $this->app->response;
        $create = hasKeyPost("doSave");
        if ($create) {
            $id = $request->getPost("contentId");
            $title = $request->getPost("contentTitle");
            $path = $request->getPost("contentPath") ?? null;
            $slug = $request->getPost("contentSlug");
            $data = $request->getPost("contentData");
            $type = $request->getPost("contentType");
            $filter = $request->getPost("contentFilter");
            $published = $request->getPost("contentPublish");
            if (!$slug) {
                $slug = slugify($title);
            }
            $sql = "INSERT INTO content (id, title, path, slug, data, type, filter, published) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
            $this->db->execute($sql, [$id, $title, $path, $slug,  $data, $type, $filter, $published]);
            return $response->redirect("cms/showall");
        }
    }



    /**
    * This is the reset database method get action, it handles:
    * reset database
    *
    * @return object
    */
    public function resetActionGet() : object
    {
        $title = "Reset database | CMS oophp";
        $page = $this->app->page;
        $data = [
            "output" => ""
        ];
        // $page->add("cms/header");
        $page->add("cms/reset", $data);
        return $page->render(["title" => $title, ]);
    }


    /**
    * This is the reset database method post action, it handles:
    * reset database
    *
    * @return object
    */
    public function resetActionPost() : object
    {
        // Restore the database to its original settings
        $title = "Reset database CMS oophp";
        $page = $this->app->page;
        $request =  $this->app->request;

        //$file  = "sql/content/setup.sql";
        // $mysql  = "/usr/bin/mysql";
        $results = null;


        // Extract hostname and databasename from dsn
        $config = require(ANAX_INSTALL_PATH . "/config/database.php");
        $dsnDetail = [];
        preg_match("/mysql:host=(.+);dbname=([^;.]+)/", $config["dsn"], $dsnDetail);
        $host = $dsnDetail[1];
        $database = $dsnDetail[2];
        $login = $config["username"];
        $password = $config["password"];
        if ($host !="127.0.0.1") {
            $file = dirname(dirname(__DIR__)) . "/sql/content/setup.sql";
        } else {
            $file = "C:/Users/panqing/dbwebb-kurser/oophp/me/redovisa/sql/content/setup.sql";
        }

        if ($request->getPost("reset")) {
            $command = "mysql -h{$host} -u{$login} -p{$password} $database < $file 2>&1";
            $results = [];
            $status = null;
            exec($command, $res, $status);

            $results = "<p>The command was: <code>$command</code>.<br>The command status was $status."
            . "<br>The output from the command was:</p><pre>"
            . print_r($res, 1);
        }
        $data = [
            "output" => $results ?? ""
        ];

        // $page->add("cms/header");
        $page->add("cms/reset", $data);
        return $page->render(["title" => $title, ]);
    }
}
