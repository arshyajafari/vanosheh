<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Model;

    class OrderModel extends Model {
        use HasFactory;

        protected $table = 'orders';
        protected $fillable = [
            'customer_id',
            'member_id',
            'total_invoice',
            'invoice_type',
            'order_status',
            'description',
        ];

        public function orderProduct(): HasMany {
            return $this->hasMany(OrderProductModel::class, 'order_id', 'id');
        }

        public function customer(): BelongsTo {
            return $this->belongsTo(CustomerModel::class, "customer_id", "id");
        }

        public function member(): BelongsTo {
            return $this->belongsTo(MemberModel::class, "member_id", "id");
        }
    }
