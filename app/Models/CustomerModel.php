<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Model;

    class CustomerModel extends Model {
        use HasFactory;

        protected $table = 'customers';
        protected $fillable = [
            'full_name',
            'national_code',
            'economic_code',
            'phone_number',
            'telephone_number',
            'city',
            'address',
            'file',
            'total_invoice'
        ];

        public function customerOrders(): HasMany {
            return $this->hasMany(OrderModel::class, 'customer_id', 'id');
        }

        public function financialReports(): HasMany {
            return $this->hasMany(FinancialReportModel::class, 'customer_id', 'id');
        }

        public function settlements(): HasMany {
            return $this->hasMany(CustomerSettlementModel::class, 'customer_id', 'id');
        }

        public function returnOfGoods(): HasMany {
            return $this->hasMany(ReturnProductModel::class, 'customer_id', 'id');
        }
    }
