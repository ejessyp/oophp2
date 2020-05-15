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
        $page->add("cms/header");
        $page->add("cms/show-all", ["resultset" => $resultset, ]);
        return $page->render(["title" => $title, ]);
    }


    /**
    * This is the view page method get action, it handles:
    *
    * ANY METHOD mountpoint/page
    *
    * @return object
    */
    public function pageAction() : object
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
        $page->add("cms/header");
        $page->add("cms/pages", ["resultset" => $resultset, ]);
        return $page->render(["title" => $title, ]);
    }

    // /**
    // * This is the search title method get action, it handles:
    // * search movies by year
    // *
    // * @return object
    // */
    // public function searchyearActionGet() : object
    // {
    //     $title = "Search year of the movie";
    //     $request = $this->app->request;
    //
    //     $year1 = $request->getGet("year1");
    //     $year2 = $request->getGet("year2");
    //     if ($year1 && $year2) {
    //         $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
    //         $resultset = $this->db->executeFetchAll($sql, [$year1, $year2]);
    //     } elseif ($year1) {
    //         $sql = "SELECT * FROM movie WHERE year >= ?;";
    //         $resultset = $this->db->executeFetchAll($sql, [$year1]);
    //     } elseif ($year2) {
    //         $sql = "SELECT * FROM movie WHERE year <= ?;";
    //         $resultset = $this->db->executeFetchAll($sql, [$year2]);
    //     }
    //     $data = [
    //         "year1" =>$year1 ?? null,
    //         "year2" =>$year2 ?? null,
    //         "res"=> $resultset ?? []
    //     ];
    //     $this->app->page->add("movie/header");
    //     $this->app->page->add("movie/search-year", $data);
    //     $this->app->page->add("movie/index", $data);
    //     return $this->app->page->render([
    //         "title" => $title,
    //     ]);
    // }
    //
    // /**
    // * This is the admin database method get action, it handles:
    // * add, edit and delete movie
    // *
    // * @return object
    // */
    // public function adminActionGet() : object
    // {
    //     $title = "Admin the movie";
    //     $sql = "SELECT id, title FROM movie;";
    //     $movies = $this->db->executeFetchAll($sql);
    //     $data = [
    //         "movies"=> $movies ?? []
    //     ];
    //     $this->app->page->add("movie/header");
    //     $this->app->page->add("movie/movie-select", $data);
    //     return $this->app->page->render([
    //         "title" => $title,
    //     ]);
    // }
    //
    //
    // /**
    // * This admin POST action handles:
    // * redirect to pages with different actions
    // * like add, edit and delete.
    // * @return object
    // */
    // public function adminActionPost() : object
    // {
    //     $request = $this->app->request;
    //     $response = $this->app->response;
    //     $session = $this->app->session;
    //     $movieId = $request->getPost("movieId");
    //     $doDelete = $request->getPost("doDelete");
    //     $doAdd = $request->getPost("doAdd");
    //     $doEdit = $request->getPost("doEdit");
    //
    //     if ($doDelete) {
    //         $sql = "DELETE FROM movie WHERE id = ?;";
    //         $this->db->execute($sql, [$movieId]);
    //         return $response->redirect("movie/admin");
    //     } elseif ($doEdit  && is_numeric($movieId)) {
    //         $session->set("movieId", $movieId);
    //         return $response->redirect("movie/edit");
    //     } elseif ($doAdd) {
    //         // get the last indert Id
    //         $sql = "select max(id) as maxid from movie;";
    //         $res = $this->db->executeFetchAll($sql);
    //         $movieId = $res[0]->maxid + 1;
    //         $session->set("movieId", $movieId);
    //         return $response->redirect("movie/add");
    //     }
    // }
    //
    // /**
    // * This is the edit movie method get action, it handles:
    // * edit a movie
    // *
    // * @return object
    // */
    // public function editActionGet() : object
    // {
    //     $title = "Update a movie";
    //     $session = $this->app->session;
    //     $movieId = $session->get("movieId");
    //     $page = $this->app->page;
    //
    //     $sql = "SELECT * FROM movie WHERE id = ?;";
    //     $movie = $this->db->executeFetchAll($sql, [$movieId]);
    //     $movie = $movie[0];
    //
    //     $page->add("movie/header");
    //     $page->add("movie/movie-edit", ["movie" => $movie, ]);
    //     return $page->render(["title" => $title,]);
    // }
    //
    //
    // /**
    // * This is the edit movie method post action, it handles:
    // * edit a movie
    // *
    // * @return object
    // */
    // public function editActionPost() : object
    // {
    //     $request = $this->app->request;
    //     $response = $this->app->response;
    //     $doSave = $request->getPost("doSave");
    //     $movieId    = $request->getPost("movieId");
    //     $movieTitle = $request->getPost("movieTitle");
    //     $movieYear  = $request->getPost("movieYear");
    //     $movieImage = $request->getPost("movieImage");
    //
    //     if ($doSave) {
    //         $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
    //         $this->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);
    //         return $response->redirect("movie/admin");
    //     }
    // }

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

        $page->add("cms/header");
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
            $path = $request->getPost("contentPath");
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
        $page->add("cms/header");
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
        //
        // $file = dirname(dirname(__DIR__)) . "\sql\content\setup.sql";
        $file  = "sql/content/setup.sql";
        $mysql  = "/usr/bin/mysql";
        $results = null;

        // Extract hostname and databasename from dsn
        $dsnDetail = [];
        preg_match("/mysql:host=(.+);dbname=([^;.]+)/", "mysql:host=127.0.0.1;dbname=oophp", $dsnDetail);
        $host = $dsnDetail[1];
        $database = $dsnDetail[2];
        $login = "user";
        $password = "pass";

        if ($request->getPost("reset")) {
            $command = "$mysql -h{$host} -u{$login} -p{$password} $database < $file 2>&1";
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

        $page->add("cms/header");
        $page->add("cms/reset", $data);
        return $page->render(["title" => $title, ]);
    }
}
