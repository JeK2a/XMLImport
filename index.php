<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';

class Products
{
    private $db;

    private $total;
    private $limit;
    private $page;

    public function __construct()
    {
        $this->db = db\DB::getConnection();
    }

    public function index()
    {
        $this->total = $this->getProductsCount();
        $this->limit = $_GET['limit'] ?? 50;
        $this->page  = $_GET['page']  ?? 1;

        $content = file_get_contents('products.html');
        $products = $this->getProducts($this->page, $this->limit);
        $content = str_replace('{products}', $this->showProducts($products), $content);
        $content = str_replace('{pagination}', $this->createLinks(15), $content);

        echo $content;
    }

    public function showProducts($products)
    {
        $columns = [
            'id'       => 'ID',
            'name'     => 'Название',
            'code'     => 'Код',
            'weight'   => 'Вес',
            'quantity' => 'Количество',
            'price'    => 'Цена',
            'usage'    => 'Заменители'
        ];

        $cities = $this->getCity();
        $count_cities = count($cities);

        $html    = '';
        $col_tmp = '';

        $html .= '<tr>';

        foreach ($columns as $col_key => $col_name) {
            switch ($col_key) {
                case 'quantity':
                case 'price':
                    $html .= '<th colspan="' . $count_cities . '">' . $col_name . '</th>';

                    foreach ($cities as $key => $city) {
                        $col_tmp .= '<th><p class="vertical">' . $city . '</p></th>';
                    }
                    break;
                default:
                    $html .= '<th rowspan="2">' . $col_name . '</th>';
                    break;
            }
        }

        $html .= '
            </tr>
            <tr>' . $col_tmp . '</tr>';

        foreach ($products as $product_key => $product) {
            $html .= '<tr>';

            foreach ($columns as $col_key => $value) {
                switch ($col_key) {
                    case 'quantity':
                    case 'price':
                        foreach ($cities as $key => $city) {
                            $html .= '<td>' . $product[$col_key . '_' .$key] . '</td>';
                        }
                        break;
                    default:
                        $html .= '<td>' . $product[$col_key] . '</td>';
                        break;
                }
            }

            $html .= '<tr>';
        }

        return $html;
    }

    private function createLinks(int $links = 7)
    {
        $last = ceil($this->total / $this->limit);

        $start = (($this->page - $links) > 0)     ? $this->page - $links : 1;
        $end   = (($this->page + $links) < $last) ? $this->page + $links : $last;

        $html = '<ul class="pagination">';

        $class = ($this->page == 1) ? "disabled" : "";
        $html .= '
            <li class="' . $class . '"><a href="?limit=' . $this->limit .
                '&page=' . ($this->page - 1) . '">&laquo;</a></li>';

        if ($start > 1) {
            $html   .= '
                <li><a href="?limit=' . $this->limit . '&page=1">1</a></li>
                <li class="disabled"><span>...</span></li>';
        }

        for ($i = $start ; $i <= $end; $i++) {
            $class = ($this->page == $i) ? "active" : "";
            $html .= '
                <li><a class="' . $class . '" href="?limit=' .
                    $this->limit . '&page=' . $i . '">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $html .= '
                <li class="disabled"><span>...</span></li>
                <li><a href="?limit=' . $this->limit .
                    '&page=' . $last . '">' . $last . '</a></li>';
        }

        $class = ($this->page == $last) ? "disabled" : "";
        $html .= '
            <li class="' . $class . '"><a href="?limit=' . $this->limit .
                '&page=' . ($this->page + 1) . '">&raquo;</a></li>';

        $html .= '</ul>';

        return $html;
    }

    private function getCity()
    {
        $query = '
            SELECT 
                `id`,
                `name`
            FROM `city`
            ORDER BY `id`;';

        $stmt_city = $this->db->query($query);

        $city = [];

        while ($row = $stmt_city->fetch()) {
            $city[$row['id']] = $row['name'];
        }

        return $city;
    }

    private function getProducts(int $page, int $limit)
    {
        $page--;

        $query = '
            SELECT *
            FROM `products`
            ORDER BY `id` 
            LIMIT ' . ($page * $limit) . ', ' . $limit . ';';

        $stmt = $this->db->query($query);
        $products = $stmt->fetchAll();

        return $products;
    }

    private function getProductsCount()
    {
        $query = '
            SELECT COUNT(*) AS `count`
            FROM `products`;';

        $stmt = $this->db->query($query);
        $products_count = $stmt->fetchColumn();

        return $products_count;
    }

}

$Products = new Products();
$Products->index();