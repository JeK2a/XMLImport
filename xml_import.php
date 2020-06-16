<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);
ini_set("memory_limit","1000M");

require_once __DIR__ . '/db.php';

class XMLImport
{
    private $db;
    private $i = 0;

    public function __construct()
    {
        $this->db = db\DB::getConnection();

        echo ini_get("memory_limit");;
    }

    public function index()
    {
        $dir_data = __DIR__ . '/data';
        $files = scandir($dir_data);

        foreach ($files as $key => $file_name) {
            if (strpos($file_name, 'import') !== false) {
                preg_match_all('/import([0-9]*)_([0-9]*).xml/',
                    $file_name, $matches, PREG_SET_ORDER, 0);

                $city_code = $matches[0][1];
                $number    = $matches[0][2];
                $file_products = file_get_contents($dir_data . '/' . $file_name);
                $xml_products = new SimpleXMLElement($file_products);
                $products = $xml_products->Каталог->Товары->Товар;

                $this->addNewProducts($products);

                $file_offers = file_get_contents($dir_data . '/offers' . $city_code . '_' . $number. '.xml');
                $xml_offers = new SimpleXMLElement($file_offers);
                $offers = $xml_offers->ПакетПредложений->Предложения->Предложение;

                if (!empty($offers)) {
                    $this->updatePriceAndQuantity($offers, $city_code);
                }

                echo ++$this->i;

                echo '<pre>';
                echo __FILE__.' - '.__LINE__."\n";
                print_r(memory_get_usage());

                echo "\n";

                unset($xml_products);
                unset($file_products);
                gc_collect_cycles();


                print_r(memory_get_usage());
                echo '</pre>';
            }
        }
    }

    private function addNewProducts(SimpleXMLElement &$products)
    {
        $query_start = '
            INSERT IGNORE INTO `products`(
                `name`,
                `code`,
                `weight`,
                `usage`
            ) VALUES';

        $query = '';
        $i = 0;

        foreach ($products as $key => $product) {
            $name = $product->Наименование;
            $code = $product->Код;
            $weight = $product->Вес;
            $usages = [];
            $usages_tmp = $product->Взаимозаменяемости;

            if (!empty($usages_tmp)) {
                foreach ($usages_tmp->Взаимозаменяемость as $usage) {
                    $usages[] = $usage->Марка . '-' . $usage->Модель . '-' . $usage->КатегорияТС;
                }
            }

            $query .= '
                 (
                    "' . addslashes($name) . '",    
                    "' . $code . '",    
                    "' . $weight . '",    
                    "' . implode('|', $usages) . '"    
                ),';

            if (++$i > 100) {
                $query = substr($query, 0, -1);
                $this->db->query($query_start . $query);
                $i = 0;
                $query = '';
            }
        }

        if (!empty($query)) {
            $query = substr($query, 0, -1);
            $this->db->query($query_start . $query);
        }
    }

    private function updatePriceAndQuantity(SimpleXMLElement &$offers, $city_code)
    {
        foreach ($offers as $offer) {
            $code     = $offer->Код;
            $price    = $offer->Цены->Цена[0]->ЦенаЗаЕдиницу ?? 0;
            $quantity = $offer->Количество ?? 0;

            $query = '
                UPDATE `products` 
                SET 
                    `quantity_' . $city_code . '` = ' . $quantity . ',
                    `price_' . $city_code . '`    = ' . $price . ' 
                WHERE `code` = ' . $code . ';';

//            echo ' ' . ++$this->i . ' ';
            $this->db->query($query);
        }
    }
}

//while (true) {
    echo "start\n";
    $XMLImport = new XMLImport();
    $XMLImport->index();
    echo "stop\n";
//    sleep(60);
//}
