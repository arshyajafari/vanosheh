<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Model;

    class ReturnProductModel extends Model {
        use HasFactory;

        protected $table = 'return_product';
        protected $fillable = [
            'customer_id',
            'product_id',
            'member_id',
            'quantity',
            'description',
            'return_status',
        ];
        protected $casts = [
            'quantity' => 'integer',
        ];

        public function customer(): BelongsTo {
            return $this->belongsTo(CustomerModel::class, "customer_id", "id");
        }

        public function product(): BelongsTo {
            return $this->belongsTo(ProductModel::class, "product_id", "id");
        }

        public function member(): BelongsTo {
            return $this->belongsTo(MemberModel::class, "member_id", "id");
        }
    }
