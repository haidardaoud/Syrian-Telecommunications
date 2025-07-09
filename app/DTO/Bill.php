<?php
// app/DTO/Bill.php
class Bill
{
    public function __construct(
        public float $amountDue,
        public float $total,
        public string $dueDate,
        public string $startDate,
        public string $phoneNumber,
        public string $period,
        public string $documentNumber
    ) {}
}
