<?php 

/**
 * This class is used to manage the category and categories
 */
class Category {

    private $dbConn;

    public function __construct($database) {
        $this->dbConn = $database;

    }


    /**
     * This function return all the category
     *
     * @return array
     */
    public function getAll(): array 
    {
        $query = "SELECT id, title FROM category where sub_category_id is null";
        $whereClause = ['sub_category_id' => 'is null'];
      return $stmt = $this->dbConn->query($query);

    }

    /**
     * This function return the sub category based on the categoryId param
     *
     * @param integer $categoryId
     * @return array
     */
    public function getSubCategory(int $categoryId): array
    {
        $query = "SELECT id, title FROM category where sub_category_id = :sub_category_id";
        $whereClause = ['sub_category_id' => $categoryId];
      return $stmt = $this->dbConn->query($query, $whereClause);

    }

    public function prepareSubCategoryListing($id) 
    {
        $subCategory = $this->getSubCategory($id);
        $list = '<select class="form-control" id="sub_category" name="sub_category">';
        foreach($subCategory as $category) {
            $list .= '<option value="'.$category['id'].'">'.$category['title'].'</option>';

        }
        $list .= '</select>';
        return $list;
    }

}

?>