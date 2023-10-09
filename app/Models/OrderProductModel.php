<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        ];

        public function order(): BelongsTo {
            return $this->belongsTo(OrderModel::class, "order_id", "id");
        }

        public function product(): BelongsTo {
            return $this->belongsTo(ProductModel::class, "product_id", "id");
        }
    }
