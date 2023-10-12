<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Database\Eloquent\Model;

    class ProductModel extends Model {
        use HasFactory, SoftDeletes;

        protected $table = 'products';
        protected $fillable = [
            'title',
            'picture',
            'stock',
            'expiration_date',
            'description',
            'price',
            'category_id',
            'discount',
            'gift_product',
        ];
        protected $hidden = [
            'password',
        ];
        protected $casts = [
            'stock' => 'integer',
            'price' => 'integer',
            'discount' => 'integer',
            'gift_product' => 'string',
        ];

        public function orderProduct(): HasMany {
            return $this->hasMany(OrderProductModel::class, 'product_id', 'id');
        }

        public function returnOfGoods(): HasMany {
            return $this->hasMany(ReturnProductModel::class, 'product_id', 'id');
        }
    }
