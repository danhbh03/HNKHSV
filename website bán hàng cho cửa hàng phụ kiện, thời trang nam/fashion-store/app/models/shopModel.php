<?php
class ProductModel {
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getFilteredProducts($filters, $sort) {
        $conditions = [];
        $params = [];

        if (!empty($filters['filter']) && $filters['filter'] !== 'all') {
            $conditions[] = "LOWER(Product_type) LIKE LOWER(:filter)";
            $params[':filter'] = '%' . $filters['filter'] . '%';
        }

        if (!empty($filters['priceFilter'])) {
            switch ($filters['priceFilter']) {
                case "0-100": $conditions[] = "Price BETWEEN 0 AND 100000"; break;
                case "100-200": $conditions[] = "Price > 100000 AND Price <= 200000"; break;
                case "200-500": $conditions[] = "Price > 200000 AND Price <= 500000"; break;
                case "500-800": $conditions[] = "Price > 500000 AND Price <= 800000"; break;
                case "800-1000": $conditions[] = "Price > 800000 AND Price <= 1000000"; break;
                case "1000+": $conditions[] = "Price > 1000000"; break;
            }
        }

        if (!empty($filters['search'])) {
            $conditions[] = "(Product_name LIKE :search OR CAST(Price AS CHAR) LIKE :priceSearch)";
            $params[':search'] = '%' . $filters['search'] . '%';
            $params[':priceSearch'] = '%' . $filters['search'] . '%';
        }

        $allowedSorts = ['low_to_high' => 'Price ASC', 'high_to_low' => 'Price DESC'];
        $orderBy = isset($allowedSorts[$sort]) ? "ORDER BY {$allowedSorts[$sort]}" : '';
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT * FROM mens_fashion $where $orderBy";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>