<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Model;

    class OrderProductModel extends Model {
        use HasFactory;

        protected $table = 'order_product';
        protected $fillable = [
            'order_id',
            'product_id',
            'quantity',
            'gift_quantity',
            'invoice_type',
            'price',
            'total_invoice'
        ];

        public function order(): BelongsTo {
            return $this->belongsTo(OrderModel::class, "order_id", "id");
        }

        public function product(): BelongsTo {
            return $this->belongsTo(ProductModel::class, "product_id", "id");
        }
    }
