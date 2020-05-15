<?php

// namespace Anax\Controller;
namespace Pan\Movie;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

class MovieController implements AppInjectableInterface
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
    public function indexAction() : object
    {
        $page = $this->app->page;
        $title = "Movie database";

        $sql = "SELECT * FROM movie;";
        $res = $this->db->executeFetchAll($sql);
        $page->add("movie/header");
        $page->add("movie/index", ["res" => $res, ]);
        return $page->render(["title" => $title, ]);
    }

    /**
    * This is the search title method get action, it handles:
    * search movie by title
    *
    * @return object
    */
    public function searchtitleActionGet() : object
    {
        $title = "Search title of the movie";
        $request = $this->app->request;

        $searchTitle = $request->getGet("searchTitle");
        if ($searchTitle) {
            $sql = "SELECT * FROM movie WHERE title LIKE ?;";
            $res = $this->db->executeFetchAll($sql, [$searchTitle]);
        }

        $data = [
            "searchTitle" =>$searchTitle,
            "res"=> $res ?? []
        ];
        $this->app->page->add("movie/header");
        $this->app->page->add("movie/search-title", $data);
        $this->app->page->add("movie/index", $data);
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
    * This is the search title method get action, it handles:
    * search movies by year
    *
    * @return object
    */
    public function searchyearActionGet() : object
    {
        $title = "Search year of the movie";
        $request = $this->app->request;

        $year1 = $request->getGet("year1");
        $year2 = $request->getGet("year2");
        if ($year1 && $year2) {
            $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
            $resultset = $this->db->executeFetchAll($sql, [$year1, $year2]);
        } elseif ($year1) {
            $sql = "SELECT * FROM movie WHERE year >= ?;";
            $resultset = $this->db->executeFetchAll($sql, [$year1]);
        } elseif ($year2) {
            $sql = "SELECT * FROM movie WHERE year <= ?;";
            $resultset = $this->db->executeFetchAll($sql, [$year2]);
        }
        $data = [
            "year1" =>$year1 ?? null,
            "year2" =>$year2 ?? null,
            "res"=> $resultset ?? []
        ];
        $this->app->page->add("movie/header");
        $this->app->page->add("movie/search-year", $data);
        $this->app->page->add("movie/index", $data);
        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * This is the admin database method get action, it handles:
    * add, edit and delete movie
    *
    * @return object
    */
    public function adminActionGet() : object
    {
        $title = "Admin the movie";
        $sql = "SELECT id, title FROM movie;";
        $movies = $this->db->executeFetchAll($sql);
        $data = [
            "movies"=> $movies ?? []
        ];
        $this->app->page->add("movie/header");
        $this->app->page->add("movie/movie-select", $data);
        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
    * This admin POST action handles:
    * redirect to pages with different actions
    * like add, edit and delete.
    * @return object
    */
    public function adminActionPost() : object
    {
        $request = $this->app->request;
        $response = $this->app->response;
        $session = $this->app->session;
        $movieId = $request->getPost("movieId");
        $doDelete = $request->getPost("doDelete");
        $doAdd = $request->getPost("doAdd");
        $doEdit = $request->getPost("doEdit");

        if ($doDelete) {
            $sql = "DELETE FROM movie WHERE id = ?;";
            $this->db->execute($sql, [$movieId]);
            return $response->redirect("movie/admin");
        } elseif ($doEdit  && is_numeric($movieId)) {
            $session->set("movieId", $movieId);
            return $response->redirect("movie/edit");
        } elseif ($doAdd) {
            // get the last indert Id
            $sql = "select max(id) as maxid from movie;";
            $res = $this->db->executeFetchAll($sql);
            $movieId = $res[0]->maxid + 1;
            $session->set("movieId", $movieId);
            return $response->redirect("movie/add");
        }
    }

    /**
    * This is the edit movie method get action, it handles:
    * edit a movie
    *
    * @return object
    */
    public function editActionGet() : object
    {
        $title = "Update a movie";
        $session = $this->app->session;
        $movieId = $session->get("movieId");
        $page = $this->app->page;

        $sql = "SELECT * FROM movie WHERE id = ?;";
        $movie = $this->db->executeFetchAll($sql, [$movieId]);
        $movie = $movie[0];

        $page->add("movie/header");
        $page->add("movie/movie-edit", ["movie" => $movie, ]);
        return $page->render(["title" => $title,]);
    }


    /**
    * This is the edit movie method post action, it handles:
    * edit a movie
    *
    * @return object
    */
    public function editActionPost() : object
    {
        $request = $this->app->request;
        $response = $this->app->response;
        $doSave = $request->getPost("doSave");
        $movieId    = $request->getPost("movieId");
        $movieTitle = $request->getPost("movieTitle");
        $movieYear  = $request->getPost("movieYear");
        $movieImage = $request->getPost("movieImage");

        if ($doSave) {
            $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
            $this->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);
            return $response->redirect("movie/admin");
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
        $title = "Add a movie";
        $session = $this->app->session;
        $movieId = $session->get("movieId");
        $doAdd = $session->get("doAdd");
        var_dump($doAdd, $movieId);
        $page = $this->app->page;
        $arr = [
            "id" => $movieId,
            "title" => "",
            "year" => "",
            "image" => ""
        ];
        // Chaange array to an object
        $movie = new \StdClass();
        foreach ($arr as $key => $val) {
            $movie->$key = $val;
        };

        $page->add("movie/header");
        $page->add("movie/movie-add", ["movie" => $movie, ]);
        return $page->render([
            "title" => $title,
        ]);
    }


    /**
    * This is the edit movie method post action, it handles:
    * add a movie
    *
    * @return object
    */
    public function addActionPost() : object
    {
        $request = $this->app->request;
        $response = $this->app->response;
        $create = $request->getPost("create");
        $movieId    = $request->getPost("movieId");
        $movieTitle = $request->getPost("movieTitle");
        $movieYear  = $request->getPost("movieYear");
        $movieImage = $request->getPost("movieImage");
        if ($create) {
            $sql = "INSERT INTO movie (title, year, image, id) VALUES (?, ?, ?, ?);";
            $this->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);
            return $response->redirect("movie/admin");
        }
    }


    /**
    * This is the show all sort movie method get action, it handles:
    * show movie with sortable
    *
    * @return object
    */
    public function sortActionGet() : object
    {
        $title = "Show and sort all movies";
        $page = $this->app->page;
        $request = $this->app->request;
        // Only these values are valid
        // $columns = ["id", "title", "year", "image"];
        // $orders = ["asc", "desc"];

        // Get settings from GET or use defaults
        $orderBy = $request->getGet("orderby") ?: "id";
        $order = $request->getGet("order") ?: "asc";

        // Incoming matches valid value sets
        // if (!(in_array($orderBy, $columns) && in_array($order, $orders))) {
        //     die("Not valid input for sorting.");
        // }

        $sql = "SELECT * FROM movie ORDER BY $orderBy $order;";
        $resultset = $this->db->executeFetchAll($sql);

        $page->add("movie/header");
        $page->add("movie/show-all-sort", ["resultset" => $resultset, ]);
        return $page->render([
            "title" => $title,
        ]);
    }

    /**
    * This is the show all paginate movie method get action, it handles:
    * show movie with pages
    *
    * @return object
    */
    public function pageActionGet() : object
    {
        $title = "Show, paginate movies";
        $request = $this->app->request;
        // Get number of hits per page
        $hits = $request->getGet("hits", 4);
        // if (!(is_numeric($hits) && $hits > 0 && $hits <= 8)) {
        //     die("Not valid for hits.");
        // }

        // Get max number of pages
        $sql = "SELECT COUNT(id) AS max FROM movie;";
        $max = $this->db->executeFetchAll($sql);
        $max = ceil($max[0]->max / $hits);

        // Get current page
        $page = $request->getGet("page", 1);
        // if (!(is_numeric($hits) && $page > 0 && $page <= $max)) {
        //     die("Not valid for page.");
        // }
        $offset = $hits * ($page - 1);

        // Only these values are valid
        // $columns = ["id", "title", "year", "image"];
        // $orders = ["asc", "desc"];

        // Get settings from GET or use defaults
        $orderBy = $request->getGet("orderby") ?: "id";
        $order = $request->getGet("order") ?: "asc";

        // Incoming matches valid value sets
        // if (!(in_array($orderBy, $columns) && in_array($order, $orders))) {
        //     die("Not valid input for sorting.");
        // }

        $sql = "SELECT * FROM movie ORDER BY $orderBy $order LIMIT $hits OFFSET $offset;";
        $resultset = $this->db->executeFetchAll($sql);

        $this->app->page->add("movie/header");
        $this->app->page->add("movie/show-all-paginate", ["resultset" => $resultset, "max" => $max, ]);
        return $this->app->page->render(["title" => $title, ]);
    }

    /**
    * This is the reset database method get action, it handles:
    * reset database
    *
    * @return object
    */
    public function resetActionGet() : object
    {
        $title = "Reset database | oophp";
        $page = $this->app->page;
        $data = [
            "output" => ""
        ];
        $page->add("movie/header");
        $page->add("movie/reset", $data);
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
        $title = "Reset database | oophp";
        $page = $this->app->page;
        $request =  $this->app->request;
        $file   = "sql/movie/setup.sql";
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

        $page->add("movie/header");
        $page->add("movie/reset", $data);
        return $page->render(["title" => $title, ]);
    }
}
