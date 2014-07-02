<?php
class ModelNewsNews extends Model 
{
    private $conn;
    private $permalink;

    public function __construct($registry)
    {
        parent::__construct($registry);

        try {
            $this->conn = new PDO('mysql:host='.DB_HOSTNAME.';dbname=opentech_blog', DB_USERNAME, DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $this->conn->prepare('SELECT option_value FROM wp_options WHERE option_name LIKE :optionName');
            $stmt->execute(array('optionName' => 'permalink_structure'));
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->permalink = $data['option_value'];
        } catch(PDOException $e) {
            $this->conn = null;
        }
    }

    /**
     * Retrieves published posts in wordpress
     */
    public function getPublishedPosts($offset = 0, $limit = 0) {
        if (!is_null($this->conn)) {
            try {
                $sql = "SELECT * FROM wp_posts WHERE post_type LIKE :postType AND post_status LIKE :postStatus ORDER BY post_date DESC";

                if ($limit > 0) {
                    $sql .= " LIMIT $offset, $limit";
                }

                $stmt = $this->conn->prepare($sql);
                $stmt->execute(array(
                    'postType'      => 'post',
                    'postStatus'    => 'publish'
                ));
             
                $blogs = array();

                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $featuredImage = $this->getFetauredImageOfPost($row['ID']);
                    $blogs[] = array(
                        'id'    => $row['ID'],
                        'title' => $row['post_title'],
                        'url'   => $this->getPostUrl($row['guid'], $row['ID'], $row['post_name'], $row['post_date']),
                        'featured_image' => $featuredImage,
                        'content' => $row['post_content']
                    );
                }

                return $blogs;
            } catch(PDOException $e) {
                throw $e;
            }
        } else {
            return array();
        }
    }

    /**
     * Retrieves featured image of post
     */
    public function getFetauredImageOfPost($postId)
    {
        try {
            $sql = "SELECT DISTINCT wp_postmeta.meta_value as featured_image_id FROM wp_postmeta WHERE meta_key LIKE :metaKey AND post_id LIKE :postId ";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array(
                'metaKey' => '_thumbnail_id',
                'postId'  => $postId
            ));

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $imageId = $row['featured_image_id'];
            }

            if (isset($imageId) && $imageId) {
                $sql = "SELECT wp_posts.guid as image_src FROM wp_posts WHERE 1 AND wp_posts.ID = :postId";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute(array('postId'  => $imageId));

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $src = '';

                foreach ($rows as $row) {
                    $src = $row['image_src'];
                }

                return $src;
            }

            return '';

        } catch(PDOException $e) {
            throw $e;
        }
    }

    /**
     * Returns post URL
     */
    private function getPostUrl($guid, $postId, $postName, $postDate)
    {
        $postDate = new \DateTime($postDate);

        if (empty($this->permalink)) {
            return $guid;       
        }

        $link = substr($guid, 0, strpos($guid, '?') - 1);
        $link = str_replace('%year%', $postDate->format('Y'), $link.$this->permalink);
        $link = str_replace('%monthnum%', $postDate->format('m'), $link);
        $link = str_replace('%day%', $postDate->format('d'), $link);
        $link = str_replace('%postname%', $postName, $link);
        $link = str_replace('%post_id%', $postId, $link);

        return $link;
    }
}
