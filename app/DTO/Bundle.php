<?php
// app/DTO/Bundle.php
namespace App\DTO;

class Bundle
{
    public function __construct(
        public string $name,
        public int $price,
        public int $id,
        public int $isEnable,
        public int $status,
        public int $volume
    ) {}
}
