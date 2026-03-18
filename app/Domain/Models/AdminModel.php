<?php

namespace App\Domain\Models;
use App\Helpers\Core\PDOService;
class AdminModel extends BaseModel
{
    public function __construct(PDOService $db_service)
    {
        parent::__construct($db_service);
    }
    public function getTotalUsers(): int{
        $sql = "SELECT COUNT(*) FROM users";
        return $this->count($sql);
    }
    public function getTotalCategories(): int{
       $sql= "SELECT COUNT(*)FROM category";
        return $this->count($sql);
    }
    public function getTotalItems(): int{
       $sql= "SELECT COUNT(*)FROM items";
        return $this->count($sql);
    }
    public function getTotalTransactions(): int{
       $sql= "SELECT COUNT(*)FROM transactions";
        return $this->count($sql);
    }
}
