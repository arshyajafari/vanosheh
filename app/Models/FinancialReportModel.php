<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Model;

    class FinancialReportModel extends Model {
        use HasFactory;

        protected $table = 'financial_report';
        protected $fillable = [
            'customer_id',
            'order_id',
            'checkout_id',
            'amount',
        ];

        public function customer(): BelongsTo {
            return $this->belongsTo(CustomerModel::class, "customer_id", "id");
        }

        public function order(): BelongsTo {
            return $this->belongsTo(OrderModel::class, "order_id", "id");
        }

        public function settlement(): BelongsTo {
            return $this->belongsTo(CustomerSettlementModel::class, "checkout_id", "id");
        }
    }
